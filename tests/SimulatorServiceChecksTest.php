<?php

declare(strict_types=1);

namespace EgcServices\Tests;

use EgcServices\Army\Domain\Army;
use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Base\Persistence\Exception\EntityNotFound;
use EgcServices\Game\Domain\Game;
use EgcServices\Game\Persistence\GameMapper;
use EgcServices\Simulator\Domain\Exception\GameFinished;
use EgcServices\Simulator\Domain\Exception\GameNotFound;
use EgcServices\Simulator\Domain\Exception\NotEnoughArmies;
use EgcServices\Simulator\Domain\Randomizer;
use EgcServices\Simulator\Domain\SimulatorService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SimulatorServiceChecksTest extends TestCase
{
    public function testGameNotFound(): void
    {
        /** @var GameMapper&MockObject */
        $gameMapper = $this->getMockBuilder(GameMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameMapper->method('selectById')
            ->willThrowException(new EntityNotFound());

        /** @var ArmyMapper&MockObject */
        $armyMapper = $this->getMockBuilder(ArmyMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(GameNotFound::class);

        $simulator = new SimulatorService($gameMapper, $armyMapper, new Randomizer());
        $simulator->runRound(1);
    }

    public function testGameFinished(): void
    {
        /** @var GameMapper&MockObject */
        $gameMapper = $this->getMockBuilder(GameMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $game = new Game();
        $game->setStatus(GAME::STATUS_FINISHED);

        $gameMapper->method('selectById')
            ->willReturn($game);

        /** @var ArmyMapper&MockObject */
        $armyMapper = $this->getMockBuilder(ArmyMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(GameFinished::class);

        $simulator = new SimulatorService($gameMapper, $armyMapper, new Randomizer());
        $simulator->runRound(1);
    }

    public function testNotEnoughArmies(): void
    {
        /** @var GameMapper&MockObject */
        $gameMapper = $this->getMockBuilder(GameMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameMapper->method('selectById')
            ->willReturn(new Game());

        /** @var ArmyMapper&MockObject */
        $armyMapper = $this->getMockBuilder(ArmyMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $armyMapper->method('selectForSimulation')
            ->willReturn([]);

        $this->expectException(NotEnoughArmies::class);

        $simulator = new SimulatorService($gameMapper, $armyMapper, new Randomizer());
        $simulator->runRound(1);
    }

    public function testNotEnoughActiveArmies(): void
    {
        /** @var GameMapper&MockObject */
        $gameMapper = $this->getMockBuilder(GameMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameMapper->method('selectById')
            ->willReturn(new Game());

        $gameMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var ArmyMapper&MockObject */
        $armyMapper = $this->getMockBuilder(ArmyMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $army = new Army();
        $army->setUnits(10);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army,
                new Army(),
                new Army(),
                new Army(),
                new Army(),
            ]);

        $this->expectException(GameFinished::class);

        $simulator = new SimulatorService($gameMapper, $armyMapper, new Randomizer());
        $simulator->runRound(1);
    }
}
