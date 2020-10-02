<?php

declare(strict_types=1);

namespace EgcServices\Army\Persistence;

use EgcServices\Army\Domain\Army;
use EgcServices\Base\Persistence\BaseMapper;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;

/**
 * @extends BaseMapper<ArmyHydrator, Army>
 */
class ArmyMapper extends BaseMapper
{
    protected static string $table = 'army';

    public function __construct(AdapterInterface $adapter, ArmyHydrator $hydrator)
    {
        $this->adapter = $adapter;
        $this->hydrator = $hydrator;
    }

    protected function createNewEntity(): Army
    {
        return new Army();
    }

    public function selectHighestPositionForGameId(int $gameId): int
    {
        $sql = new Sql($this->adapter);

        /** @var Select $select */
        $select = $sql->select(static::$table);
        $select->columns(['position']);
        $select->where(['game_id' => $gameId]);
        $select->order('position DESC');
        $select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        /** @var false|array<string, string> $row */
        $row = $rows->current();

        if ($row === false) {
            return 0;
        }

        return (int) $row['position'];
    }

    /**
     * @return Army[]
     */
    public function selectForSimulation(int $gameId): array
    {
        $sql = new Sql($this->adapter);

        /** @var Select $select */
        $select = $sql->select(static::$table);
        $select->order('position ASC');

        $where = new Where();
        $where->equalTo('game_id', $gameId);
        $select->where($where);

        $statement = $sql->prepareStatementForSqlObject($select);
        $rows = $statement->execute();

        $result = [];

        /** @var array<string, mixed> $row */
        foreach ($rows as $row) {
            /** @var Army $entity */
            $entity = $this->hydrator->hydrate($this->createNewEntity(), $row);
            $result[] = $entity;
        }

        return $result;
    }
}
