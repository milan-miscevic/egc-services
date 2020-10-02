<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

use EgcServices\Army\Domain\Army;

class Randomizer
{
    public function randomInt(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * @param Army[] $list
     */
    public function randomFromArray(array $list): Army
    {
        $key = array_rand($list);

        return $list[$key];
    }
}
