<?php

declare(strict_types=1);

namespace EgcServices\Game\Domain;

use EgcServices\Base\Domain\BaseEntity;
use JsonSerializable;
use UnexpectedValueException;

class Game extends BaseEntity implements JsonSerializable
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_FINISHED = 'finished';

    public const STATUSES = [
        self::STATUS_ACTIVE => self::STATUS_ACTIVE,
        self::STATUS_FINISHED => self::STATUS_FINISHED,
    ];

    protected string $name = '';
    protected string $status = self::STATUS_ACTIVE;
    protected ?int $next = null;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStatus(string $status): void
    {
        if (!isset(static::STATUSES[$status])) {
            throw new UnexpectedValueException();
        }

        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setNext(?int $next): void
    {
        $this->next = $next;
    }

    public function getNext(): ?int
    {
        return $this->next;
    }
}
