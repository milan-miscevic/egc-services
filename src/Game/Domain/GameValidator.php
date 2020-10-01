<?php

declare(strict_types=1);

namespace EgcServices\Game\Domain;

use EgcServices\Base\Domain\BaseValidator;
use Laminas\Validator\StringLength;

class GameValidator extends BaseValidator
{
    /**
     * @var array<string, array<string, array>>
     */
    protected static array $rules = [
        'name' => [
            StringLength::class => [
                'min' => 3,
            ],
        ],
    ];
}
