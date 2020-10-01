<?php

declare(strict_types=1);

namespace EgcServices\Base\Persistence;

use EgcServices\Base\Domain\BaseEntity;
use EgcServices\Base\Persistence\Exception\EntityNotFound;
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
    public function selectById(int $id): BaseEntity
    {
        $sql = new Sql($this->adapter);
        /** @var Select $select */
        $select = $sql->select(static::$table);
        $select->where(['id' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $rows = $statement->execute();
        /** @var false|array<string, mixed> $row */
        $row = $rows->current();

        if ($row === false) {
            throw new EntityNotFound();
        }

        /** @var T2 $entity */
        $entity = $this->hydrator->hydrate($this->createNewEntity(), $row);

        return $entity;
    }

    /**
     * @return T2
     */
    abstract protected function createNewEntity(): BaseEntity;

    /**
     * @param T2 $entity
     */
    public function insert(BaseEntity $entity): int
    {
        $sql = new Sql($this->adapter);
        /** @var Insert $insert */
        $insert = $sql->insert(static::$table);
        $data = $this->hydrator->extract($entity);
        $insert->values($data);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        return (int) $result->getGeneratedValue();
    }
}
