<?php

declare(strict_types=1);

namespace EgcServices\Game\Domain;

use EgcServices\Base\Domain\BaseService;
use EgcServices\Game\Persistence\GameMapper;

class GameService extends BaseService
{
    private GameMapper $gameMapper;

    public function __construct(GameMapper $gameMapper)
    {
        $this->gameMapper = $gameMapper;
    }

    /**
     * @return Game[]
     */
    public function getAll(): array
    {
        return $this->gameMapper->selectAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function add($data): int
    {
        return $this->gameMapper->insert($data);
    }
}
