<?php

declare(strict_types=1);

namespace EgcServices\Game\Domain;

use EgcServices\Base\Domain\BaseService;
use EgcServices\Base\Domain\Exception\InvalidData;
use EgcServices\Game\Persistence\GameMapper;

class GameService extends BaseService
{
    private GameMapper $gameMapper;
    private GameValidator $gameValidator;

    public function __construct(GameMapper $gameMapper, GameValidator $gameValidator)
    {
        $this->gameMapper = $gameMapper;
        $this->gameValidator = $gameValidator;
    }

    /**
     * @return Game[]
     */
    public function getAll(): array
    {
        return $this->gameMapper->selectAll();
    }

    public function getById(int $id): Game
    {
        return $this->gameMapper->selectById($id);
    }

    /**
     * @param mixed $data
     */
    public function add($data): int
    {
        if (!$this->gameValidator->isValid($data)) {
            $ex = new InvalidData();
            $ex->setErrors($this->gameValidator->getErrors());
            throw $ex;
        }

        $game = new Game();
        /** @psalm-suppress MixedArrayAccess */
        $game->setName((string) $data['name']);

        return $this->gameMapper->insert($game);
    }

    public function update(Game $game): int
    {
        return $this->gameMapper->update($game);
    }
}
