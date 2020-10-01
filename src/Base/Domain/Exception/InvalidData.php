<?php

declare(strict_types=1);

namespace EgcServices\Base\Domain\Exception;

use RuntimeException;

class InvalidData extends RuntimeException
{
    /**
     * @var array<string, array<string, string>>
     */
    protected array $errors = [];

    /**
     * @param array<string, array<string, string>> $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
