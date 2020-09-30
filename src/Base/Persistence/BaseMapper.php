<?php

declare(strict_types=1);

namespace EgcServices\Base\Persistence;

use EgcServices\Base\Domain\BaseEntity;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;

/**
 * @template T1 of BaseHydrator
 * @template T2 of BaseEntity
 */
abstract class BaseMapper
{
    protected static string $table = '';

    protected AdapterInterface $adapter;

    /** @var T1 */
    protected BaseHydrator $hydrator;

    /**
     * @return T2[]
     */
    public function selectAll(): array
    {
        $sql = new Sql($this->adapter);
        /** @var Select $select */
        $select = $sql->select(static::$table);
        $statement = $sql->prepareStatementForSqlObject($select);
        $rows = $statement->execute();

        $result = [];

        /** @var array<string, string> $row */
        foreach ($rows as $row) {
            /** @var T2 $entity */
            $entity = $this->hydrator->hydrate($this->createNewEntity(), $row);
            $result[] = $entity;
        }

        return $result;
    }

    /**
     * @return T2
     */
    abstract protected function createNewEntity(): BaseEntity;

    /**
     * @param array<string, mixed> $data
     */
    public function insert($data): int
    {
        $sql = new Sql($this->adapter);
        /** @var Insert $insert */
        $insert = $sql->insert(static::$table);
        $insert->values($data);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        return (int) $result->getGeneratedValue();
    }
}
