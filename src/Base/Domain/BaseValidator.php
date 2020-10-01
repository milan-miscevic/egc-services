<?php

declare(strict_types=1);

namespace EgcServices\Base\Domain;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\ValidatorChain;

abstract class BaseValidator
{
    /**
     * @var array<string, array<string, array>>
     */
    protected static array $rules = [];

    /**
     * @var array<string, array<string, string>>
     */
    protected array $errors = [];

    /**
     * @param mixed $data
     */
    public function isValid($data): bool
    {
        $valid = true;

        foreach (static::$rules as $field => $validators) {
            if (!isset($data[$field])) {
                $valid = false;
                continue;
            }

            $chain = new ValidatorChain();

            foreach ($validators as $className => $options) {
                /** @var AbstractValidator $validator */
                $validator = new $className($options);
                $chain->attach($validator);
            }

            /** @psalm-suppress MixedArrayAccess */
            $valid = $valid && $chain->isValid($data[$field]);
            /** @var array<string, string> $messages */
            $messages = $chain->getMessages();
            $this->errors = array_merge_recursive($this->errors, [$field => $messages]);
        }

        return $valid;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
