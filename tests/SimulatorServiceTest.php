<?php

declare(strict_types=1);

namespace EgcServices\Tests;

use EgcServices\Army\Domain\Army;
use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Game\Domain\Game;
use EgcServices\Game\Persistence\GameMapper;
use EgcServices\Simulator\Domain\Randomizer;
use EgcServices\Simulator\Domain\SimulatorService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SimulatorServiceTest extends TestCase
{
    public function testNoAttack(): void
    {
        /** @var GameMapper&MockObject */
        $gameMapper = $this->getMockBuilder(GameMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $game = new Game();
        $game->setNext(1);

        $gameMapper->method('selectById')
            ->willReturn($game);

        $gameMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var ArmyMapper&MockObject */
        $armyMapper = $this->getMockBuilder(ArmyMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(10);
        $army1->setStrategy(Army::STRATEGY_RANDOM);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender2');
        $army2->setUnits(10);

        $army3 = new Army();
        $army3->setId(3);
        $army3->setName('defender3');
        $army3->setUnits(10);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                $army3,
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(101);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(false, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army2->getName(), $result->getDefender());
        $this->assertSame('', $result->getStrategy());
        $this->assertSame(0, $result->getDamage());
    }

    public function testRandomStrategy(): void
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

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(10);
        $army1->setStrategy(Army::STRATEGY_RANDOM);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender2');
        $army2->setUnits(10);

        $army3 = new Army();
        $army3->setId(3);
        $army3->setName('defender3');
        $army3->setUnits(10);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                $army3,
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(-1);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(true, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army2->getName(), $result->getDefender());
        $this->assertSame('random', $result->getStrategy());
        $this->assertSame(5, $result->getDamage());
    }

    public function testWeakestStrategy(): void
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

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(10);
        $army1->setStrategy(Army::STRATEGY_WEAKEST);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender2');
        $army2->setUnits(10);

        $army3 = new Army();
        $army3->setId(3);
        $army3->setName('defender3');
        $army3->setUnits(50);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                $army3,
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(-1);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(true, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army2->getName(), $result->getDefender());
        $this->assertSame('weakest', $result->getStrategy());
        $this->assertSame(5, $result->getDamage());
    }

    public function testStrongestStrategy(): void
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

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(10);
        $army1->setStrategy(Army::STRATEGY_STRONGEST);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender2');
        $army2->setUnits(10);

        $army3 = new Army();
        $army3->setId(3);
        $army3->setName('defender3');
        $army3->setUnits(50);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                $army3,
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(-1);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(true, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army3->getName(), $result->getDefender());
        $this->assertSame('strongest', $result->getStrategy());
        $this->assertSame(5, $result->getDamage());
    }

    public function testBigDamage(): void
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

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(90);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender');
        $army2->setUnits(10);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                new Army(),
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(-1);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(true, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army2->getName(), $result->getDefender());
        $this->assertSame('random', $result->getStrategy());
        $this->assertSame(45, $result->getDamage());
    }

    public function testSmallDamage(): void
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

        $army1 = new Army();
        $army1->setId(1);
        $army1->setName('attacker');
        $army1->setUnits(1);

        $army2 = new Army();
        $army2->setId(2);
        $army2->setName('defender');
        $army2->setUnits(90);

        $armyMapper->method('selectForSimulation')
            ->willReturn([
                $army1,
                $army2,
                new Army(),
                new Army(),
                new Army(),
            ]);

        $armyMapper->expects($this->once())
            ->method('update')
            ->willReturn(1);

        /** @var Randomizer&MockObject */
        $randomizer = $this->getMockBuilder(Randomizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $randomizer->method('randomInt')
            ->willReturn(-1);

        $randomizer->method('randomFromArray')
            ->willReturn($army2);

        $simulator = new SimulatorService($gameMapper, $armyMapper, $randomizer);
        $result = $simulator->runRound(1);

        $this->assertSame(true, $result->getStatus());
        $this->assertSame($army1->getName(), $result->getAttacker());
        $this->assertSame($army2->getName(), $result->getDefender());
        $this->assertSame('random', $result->getStrategy());
        $this->assertSame(1, $result->getDamage());
    }
}
