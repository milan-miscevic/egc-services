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
        $entity->setId((int) $data['id']);
        $entity->setName((string) $data['name']);

        return $entity;
    }

    /**
     * @param Game $entity
     */
    public function extract(BaseEntity $entity): array
    {
        return [
            'name' => $entity->getName(),
        ];
    }
}
