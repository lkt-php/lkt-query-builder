<?php

namespace Lkt\QueryBuilding;

use Lkt\QueryBuilding\Traits\WhereConstraints;

class Where
{
    use WhereConstraints;

    /**
     * @deprecated
     * @return static
     */
    public static function getInstance(): self
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
     * @param string $column
     * @param string $value
     * @return static
     */
    public static function stringEqual(string $column, string $value): self
    {
        return (new static())->andStringEqual($column, $value);
    }

    /**
     * @return static
     */
    public static function raw(string $value): self
    {
        return (new static())->andRaw($value);
    }

    /**
     * @param string $column
     * @param string $value
     * @return static
     */
    public static function stringLike(string $column, string $value): self
    {
        return (new static())->andStringLike($column, $value);
    }

    /**
     * @param string $column
     * @param $value
     * @return static
     */
    public static function integerEqual(string $column, $value): self
    {
        return (new static())->andIntegerEqual($column, $value);
    }

    /**
     * @param string $column
     * @param $value
     * @return static
     */
    public static function integerNot(string $column, $value): self
    {
        return (new static())->andIntegerNot($column, $value);
    }

    /**
     * @param string $column
     * @param $value
     * @return static
     */
    public static function decimalEqual(string $column, $value): self
    {
        return (new static())->andDecimalEqual($column, $value);
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