<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Base\Domain\BaseService;
use EgcServices\Base\Persistence\Exception\EntityNotFound;
use EgcServices\Game\Domain\Game;
use EgcServices\Game\Persistence\GameMapper;
use EgcServices\Simulator\Domain\Exception\GameFinished;
use EgcServices\Simulator\Domain\Exception\GameNotFound;
use EgcServices\Simulator\Domain\Exception\NotEnoughArmies;

class SimulatorService extends BaseService
{
    const ARMY_COUNT = 5;

    private GameMapper $gameMapper;
    private ArmyMapper $armyMapper;

    public function __construct(GameMapper $gameMapper, ArmyMapper $armyMapper)
    {
        $this->gameMapper = $gameMapper;
        $this->armyMapper = $armyMapper;
    }

    public function runRound(int $gameId): int
    {
        try {
            /** @var Game $game */
            $game = $this->gameMapper->selectById($gameId);
        } catch (EntityNotFound $ex) {
            throw new GameNotFound();
        }

        if ($game->getStatus() !== Game::STATUS_ACTIVE) {
            throw new GameFinished();
        }

        $armies = $this->armyMapper->selectForSimulation($gameId);

        if (count($armies) < static::ARMY_COUNT) {
            throw new NotEnoughArmies();
        }

        return $gameId;
    }
}
