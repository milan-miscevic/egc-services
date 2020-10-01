<?php

declare(strict_types=1);

namespace EgcServices\Base\Persistence;

use EgcServices\Base\Domain\BaseEntity;

/**
 * @template T of BaseEntity
 */
abstract class BaseHydrator
{
    /**
     * @param T $entity
     * @param array<string, mixed> $data
     * @return T
     */
    abstract public function hydrate(BaseEntity $entity, array $data): BaseEntity;

    /**
     * @param T $entity
     * @return array<string, mixed>
     */
    public function extract(BaseEntity $entity): array
    {
        $data = $entity->jsonSerialize();
        unset($data['id']);

        return $data;
    }
}
