<?php

declare(strict_types=1);

namespace EgcServices\Game\Domain;

use EgcServices\Base\Domain\BaseEntity;
use JsonSerializable;

class Game extends BaseEntity implements JsonSerializable
{
    private string $name = '';

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
