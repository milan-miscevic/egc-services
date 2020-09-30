<?php

declare(strict_types=1);

namespace EgcServices\Game\Persistence;

use EgcServices\Base\Persistence\BaseMapper;
use EgcServices\Game\Domain\Game;
use Laminas\Db\Adapter\AdapterInterface;

/**
 * @extends BaseMapper<GameHydrator, Game>
 */
class GameMapper extends BaseMapper
{
    protected static string $table = 'game';

    public function __construct(AdapterInterface $adapter, GameHydrator $hydrator)
    {
        $this->adapter = $adapter;
        $this->hydrator = $hydrator;
    }

    protected function createNewEntity(): Game
    {
        return new Game();
    }
}
