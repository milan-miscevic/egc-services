<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

use EgcServices\Base\Domain\BaseEntity;

class Randomizer
{
    public function randomInt(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * @param BaseEntity[] $list
     */
    public function randomFromArray(array $list): BaseEntity
    {
        $key = array_rand($list);

        return $list[$key];
    }
}
