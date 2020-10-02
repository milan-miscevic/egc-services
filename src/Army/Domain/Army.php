<?php

declare(strict_types=1);

namespace EgcServices\Army\Domain;

use EgcServices\Base\Domain\BaseEntity;
use UnexpectedValueException;

class Army extends BaseEntity
{
    public const STRATEGY_RANDOM = 'random';
    public const STRATEGY_WEAKEST = 'weakest';
    public const STRATEGY_STRONGEST = 'strongest';

    public const STRATEGIES = [
        self::STRATEGY_RANDOM => self::STRATEGY_RANDOM,
        self::STRATEGY_WEAKEST => self::STRATEGY_WEAKEST,
        self::STRATEGY_STRONGEST => self::STRATEGY_STRONGEST,
    ];

    protected string $name = '';
    protected int $units = 0;
    protected string $strategy = self::STRATEGY_RANDOM;
    protected int $position = 0;
    protected int $gameId = 0;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setUnits(int $units): void
    {
        $this->units = $units;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function setStrategy(string $strategy): void
    {
        if (!isset(static::STRATEGIES[$strategy])) {
            throw new UnexpectedValueException();
        }

        $this->strategy = $strategy;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }
}
