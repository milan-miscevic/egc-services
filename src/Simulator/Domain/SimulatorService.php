<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Base\Domain\BaseService;
use EgcServices\Game\Persistence\GameMapper;

class SimulatorService extends BaseService
{
    private GameMapper $gameMapper;
    private ArmyMapper $armyMapper;

    public function __construct(GameMapper $gameMapper, ArmyMapper $armyMapper)
    {
        $this->gameMapper = $gameMapper;
        $this->armyMapper = $armyMapper;
    }

    public function runRound(): int
    {
        return rand(1, 10);
    }
}
