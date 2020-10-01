<?php

declare(strict_types=1);

namespace EgcServices\Base\Domain;

use JsonSerializable;

abstract class BaseEntity implements JsonSerializable
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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
