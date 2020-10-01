<?php

declare(strict_types=1);

namespace EgcServices\Army\Domain;

use EgcServices\Base\Domain\BaseValidator;
use Laminas\Validator\Between;
use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;

class ArmyValidator extends BaseValidator
{
    /**
     * @var array<string, array<string, array>>
     */
    protected static array $rules = [
        'units' => [
            Between::class => [
                'min' => 80,
                'max' => 100,
            ],
        ],
        'name' => [
            StringLength::class => [
                'min' => 3,
                'max' => 50,
            ],
        ],
        'strategy' => [
            InArray::class => [
                'haystack' => [
                    Army::STRATEGY_RANDOM,
                    Army::STRATEGY_WEAKEST,
                    Army::STRATEGY_STRONGEST,
                ],
            ],
        ],
    ];
}
