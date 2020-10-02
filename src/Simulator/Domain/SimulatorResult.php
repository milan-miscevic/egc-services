<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

class SimulatorResult
{
    private bool $status;
    private string $attacker;
    private string $defender;
    private string $strategy;
    private int $damage;

    public function __construct(
        bool $status,
        string $attacker = '',
        string $defender = '',
        string $strategy = '',
        int $damage = 0
    ) {
        $this->status = $status;
        $this->attacker = $attacker;
        $this->defender = $defender;
        $this->strategy = $strategy;
        $this->damage = $damage;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getAttacker(): string
    {
        return $this->attacker;
    }

    public function getDefender(): string
    {
        return $this->defender;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }
}
