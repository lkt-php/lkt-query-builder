<?php

namespace Lkt\QueryBuilding;

use Lkt\QueryBuilding\Traits\WhereConstraints;
use Lkt\QueryBuilding\Traits\WhereStaticConstraints;

class Where
{
    use WhereConstraints,
        WhereStaticConstraints;

    protected $table = '';
    protected $tableAlias = '';

    public function setTable(string $value)
    {
        $this->table = $value;
        return $this;
    }

    public function setTableAlias(string $value)
    {
        $this->tableAlias = $value;
        return $this;
    }

    public function getTable(): bool
    {
        return $this->table;
    }

    public function getTableAlias(): bool
    {
        return $this->tableAlias;
    }

    /**
     * @deprecated
     * @return static
     */
    public static function getInstance(): self
    {
        return new static();
    }

    /**
     * @return static
     */
    public static function getEmpty()
    {
        return new static();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->whereConstraintsToString();
    }

    /**
     * @param Query $builder
     * @param $value
     * @return static
     */
    public static function subQueryEqual(Query $builder, $value): self
    {
        return (new static())->andSubQueryEqual($builder, $value);
    }

    /**
     * @param Query $builder
     * @param $value
     * @return static
     */
    public static function subQueryIn(Query $builder, $value): self
    {
        return (new static())->andSubQueryIn($builder, $value);
    }

    /**
     * @param Query $builder
     * @param $value
     * @return static
     */
    public static function columnInSubQuery($value, Query $builder): self
    {
        return (new static())->andColumnInSubQuery($builder, $value);
    }

    /**
     * @param Where $builder
     * @return $this
     */
    public function orWhere(Where $builder): self
    {
        $this->or[] = $builder;
        return $this;
    }

    /**
     * @param Where $builder
     * @return $this
     */
    public function andWhere(Where $builder): self
    {
        $this->and[] = $builder;
        return $this;
    }
}