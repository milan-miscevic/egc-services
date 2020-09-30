<?php

declare(strict_types=1);

namespace EgcServices\Base\Domain;

abstract class BaseEntity
{
    protected int $id = 0;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
