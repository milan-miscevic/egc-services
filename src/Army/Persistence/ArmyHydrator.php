<?php

declare(strict_types=1);

namespace EgcServices\Army\Persistence;

use EgcServices\Army\Domain\Army;
use EgcServices\Base\Domain\BaseEntity;
use EgcServices\Base\Persistence\BaseHydrator;

/**
 * @extends BaseHydrator<Army>
 */
class ArmyHydrator extends BaseHydrator
{
    /**
     * @param Army $entity
     */
    public function hydrate(BaseEntity $entity, array $data): Army
    {
        $entity->setId((int) $data['id']);
        $entity->setName((string) $data['name']);
        $entity->setUnits((int) $data['units']);
        $entity->setStrategy((string) $data['strategy']);
        $entity->setPosition((int) $data['position']);
        $entity->setGameId((int) $data['game_id']);

        return $entity;
    }

    public function extract(BaseEntity $entity): array
    {
        $data = parent::extract($entity);

        $data['game_id'] = $data['gameId'];
        unset($data['gameId']);

        return $data;
    }
}
