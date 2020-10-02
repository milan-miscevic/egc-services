<?php

declare(strict_types=1);

namespace EgcServices\Simulator\Domain;

use EgcServices\Army\Domain\Army;
use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Base\Domain\BaseService;
use EgcServices\Base\Persistence\Exception\EntityNotFound;
use EgcServices\Game\Domain\Game;
use EgcServices\Game\Persistence\GameMapper;
use EgcServices\Simulator\Domain\Exception\GameFinished;
use EgcServices\Simulator\Domain\Exception\GameNotFound;
use EgcServices\Simulator\Domain\Exception\InvalidStrategy;
use EgcServices\Simulator\Domain\Exception\NotEnoughArmies;

class SimulatorService extends BaseService
{
    const ARMY_COUNT = 5;
    const ACTIVE_ARMY_COUNT = 2;

    private GameMapper $gameMapper;
    private ArmyMapper $armyMapper;
    private Randomizer $randomizer;

    public function __construct(GameMapper $gameMapper, ArmyMapper $armyMapper, Randomizer $randomizer)
    {
        $this->gameMapper = $gameMapper;
        $this->armyMapper = $armyMapper;
        $this->randomizer = $randomizer;
    }

    public function runRound(int $gameId): SimulatorResult
    {
        // check the game

        try {
            /** @var Game $game */
            $game = $this->gameMapper->selectById($gameId);
        } catch (EntityNotFound $ex) {
            throw new GameNotFound();
        }

        if ($game->getStatus() !== Game::STATUS_ACTIVE) {
            throw new GameFinished();
        }

        // check the armies

        $armies = $this->armyMapper->selectForSimulation($gameId);

        if (count($armies) < static::ARMY_COUNT) {
            throw new NotEnoughArmies();
        }

        $activeArmies = [];

        foreach ($armies as $army) {
            if ($army->getUnits() > 0) {
                $activeArmies[$army->getId()] = $army;
            }
        }

        if (count($activeArmies) < static::ACTIVE_ARMY_COUNT) {
            $game->setStatus(Game::STATUS_FINISHED);
            $this->gameMapper->update($game);
            throw new GameFinished();
        }

        // find an attacker

        if ($game->getNext() !== null && isset($activeArmies[$game->getNext()])) {
            /** @psalm-suppress PossiblyNullArrayOffset */
            $attacker = $activeArmies[$game->getNext()];
            /** @psalm-suppress PossiblyNullArrayOffset */
            unset($activeArmies[$game->getNext()]);
        } else {
            $sliced = array_slice($activeArmies, 0, 1);
            $attacker = array_shift($sliced);
        }

        // find a defender

        /**
         * @var Army $attacker
         * @phpstan-ignore-next-line
         */
        $strategy = $attacker->getStrategy();

        if ($strategy === Army::STRATEGY_RANDOM) {
            $defender = $this->randomizer->randomFromArray($activeArmies);
        } elseif ($strategy === Army::STRATEGY_WEAKEST) {
            $defenderKey = null;
            $units = PHP_INT_MAX;

            foreach ($activeArmies as $key => $army) {
                if ($army->getUnits() < $units) {
                    $defenderKey = $key;
                    $units = $army->getUnits();
                }
            }

            /**
             * @var Army $defender
             * @psalm-suppress PossiblyNullArrayOffset
             */
            $defender = $activeArmies[$defenderKey];
        } elseif ($strategy === Army::STRATEGY_STRONGEST) {
            $defenderKey = null;
            /** @psalm-suppress MixedAssignment */
            $units = PHP_INT_MIN;

            foreach ($activeArmies as $key => $army) {
                if ($army->getUnits() > $units) {
                    $defenderKey = $key;
                    $units = $army->getUnits();
                }
            }

            /**
             * @var Army $defender
             * @psalm-suppress PossiblyNullArrayOffset
             */
            $defender = $activeArmies[$defenderKey];
        } else {
            // for protection, currently unreachable
            throw new InvalidStrategy();
        }

        // attack

        usleep($attacker->getUnits() * 10); // reload time

        $attackChance = $this->randomizer->randomInt(0, 100);

        if ($attackChance <= $attacker->getUnits()) { // a successful attack
            if ($attacker->getUnits() > 1) {
                $damage = (int) ($attacker->getUnits() * 0.5);
            } else {
                $damage = 1;
            }

            $defenderUnits = $defender->getUnits();

            if ($defenderUnits < $damage) {
                $defender->setUnits(0);
                unset($activeArmies[$defender->getId()]);
            } else {
                $defender->setUnits($defenderUnits - $damage);
            }

            if (count($activeArmies) + 1 < static::ACTIVE_ARMY_COUNT) {
                $game->setStatus(Game::STATUS_FINISHED);
            }

            $result = new SimulatorResult(
                true,
                $attacker->getName(),
                $defender->getName(),
                $strategy,
                $damage
            );
        } else {
            $result = new SimulatorResult(
                false,
                $attacker->getName(),
                $defender->getName()
            );
        }

        $next = null;

        foreach ($activeArmies as $army) {
            /** @var Army $army */
            if ($army->getPosition() > $attacker->getPosition()) {
                $next = $army;
                break;
            }
        }

        if ($next === null) {
            $sliced = array_slice($activeArmies, 0, 1);
            $next = array_shift($sliced);
        }

        if ($next !== null) {
            $game->setNext($next->getId());
        }

        $this->gameMapper->update($game);
        $this->armyMapper->update($defender);

        return $result;
    }
}
