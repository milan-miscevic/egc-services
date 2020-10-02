<?php

declare(strict_types=1);

namespace EgcServices\Army\Domain;

use EgcServices\Army\Persistence\ArmyMapper;
use EgcServices\Base\Domain\BaseService;
use EgcServices\Base\Domain\Exception\InvalidData;

class ArmyService extends BaseService
{
    private ArmyMapper $armyMapper;
    private ArmyValidator $armyValidator;

    public function __construct(ArmyMapper $armyMapper, ArmyValidator $armyValidator)
    {
        $this->armyMapper = $armyMapper;
        $this->armyValidator = $armyValidator;
    }

    /**
     * @return Army[]
     */
    public function getAll(): array
    {
        return $this->armyMapper->selectAll();
    }

    /**
     * @param mixed $data
     */
    public function add($data): int
    {
        if (!$this->armyValidator->isValid($data)) {
            $ex = new InvalidData();
            $ex->setErrors($this->armyValidator->getErrors());
            throw $ex;
        }

        $gameId = (int) ($data['gameid'] ?? 0);

        $highestPosition = $this->armyMapper->selectHighestPositionForGameId($gameId);

        $army = new Army();
        /** @psalm-suppress MixedArrayAccess */
        $army->setName((string) $data['name']);
        /** @psalm-suppress MixedArrayAccess */
        $army->setUnits((int) $data['units']);
        /** @psalm-suppress MixedArrayAccess */
        $army->setStrategy((string) $data['strategy']);
        $army->setPosition($highestPosition + 1);
        $army->setGameId($gameId);

        return $this->armyMapper->insert($army);
    }
}
