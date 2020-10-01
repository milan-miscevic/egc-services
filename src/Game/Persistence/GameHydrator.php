<?php

declare(strict_types=1);

namespace EgcServices\Game\Persistence;

use EgcServices\Base\Domain\BaseEntity;
use EgcServices\Base\Persistence\BaseHydrator;
use EgcServices\Game\Domain\Game;

/**
 * @extends BaseHydrator<Game>
 */
class GameHydrator extends BaseHydrator
{
    /**
     * @param Game $entity
     */
    public function hydrate(BaseEntity $entity, array $data): Game
    {
        if ($data['next'] !== null) {
            $data['next'] = (int) $data['next'];
        }

        $entity->setId((int) $data['id']);
        $entity->setName((string) $data['name']);
        $entity->setStatus((string) $data['status']);
        $entity->setNext($data['next']);

        return $entity;
    }
}
