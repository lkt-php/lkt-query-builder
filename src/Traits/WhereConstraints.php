<?php

namespace Lkt\QueryBuilding\Traits;

use Lkt\QueryBuilding\Query;
use Lkt\QueryBuilding\Where;

trait WhereConstraints
{
    protected $or = [];
    protected $and = [];

    public function whereConstraintsToString(): string
    {
        $r = [];

        foreach ($this->and as $constraint) {
            $r[] = (string)$constraint;
        }

        $or = [];
        foreach ($this->or as $constraint) {
            $or[] = (string)$constraint;
        }

        $data = [];

        if (isset($r[0])) {
            $data[] = implode(' AND ', $r);
        }

        if (isset($or[0])) {
            $data[] = implode(' OR ', $or);
        }

        if (count($data) > 0) {
            return '(' . implode(' OR ', $data) . ')';
        }
        return '';
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function andStringEqual(string $column, string $value): self
    {
        if (strpos($value, 'COMPRESS(') === 0) {
            $this->and[] = "{$column}={$value}";
        } else {
            $v = addslashes(stripslashes($value));
            $this->and[] = "{$column}='{$v}'";
        }

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function orStringEqual(string $column, string $value): self
    {
        if (strpos($value, 'COMPRESS(') === 0) {
            $this->or[] = "{$column}={$value}";
        } else {
            $v = addslashes(stripslashes($value));
            $this->or[] = "{$column}='{$v}'";
        }

        return $this;
    }


    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function andStringLike(string $column, string $value): self
    {
        $v = addslashes(stripslashes($value));
        $this->and[] = "{$column} LIKE '%{$v}%'";
        return $this;
    }


    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function orStringLike(string $column, string $value): self
    {
        $v = addslashes(stripslashes($value));
        $this->or[] = "{$column} LIKE '%{$v}%'";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function andIntegerEqual(string $column, $value): self
    {
        $v = addslashes(stripslashes((int)$value));
        $this->and[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function orIntegerEqual(string $column, $value): self
    {
        $v = addslashes(stripslashes((int)$value));
        $this->or[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function andIntegerNot(string $column, $value): self
    {
        $v = addslashes(stripslashes((int)$value));
        $this->and[] = "{$column}!={$v}";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function orIntegerNot(string $column, $value): self
    {
        $v = addslashes(stripslashes((int)$value));
        $this->or[] = "{$column}!={$v}";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function andDecimalEqual(string $column, $value): self
    {
        $v = addslashes(stripslashes((float)$value));
        $this->and[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param string $column
     * @param $value
     * @return $this
     */
    public function orDecimalEqual(string $column, $value): self
    {
        $v = addslashes(stripslashes((float)$value));
        $this->or[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function andRaw(string $value): self
    {
        $v = stripslashes($value);
        $this->and[] = $v;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function orRaw(string $value): self
    {
        $v = stripslashes($value);
        $this->or[] = $v;
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function andSubQueryEqual(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes((float)$value));
        $this->and[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function orSubQueryEqual(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes((float)$value));
        $this->or[] = "{$column}={$v}";
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function andSubQueryIn(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes($value));
        $this->and[] = "{$column} IN ({$v})";
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function orSubQueryIn(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes($value));
        $this->or[] = "{$column} IN ({$v})";
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function andColumnInSubQuery(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes($value));
        $this->and[] = "{$v} IN ({$column})";
        return $this;
    }

    /**
     * @param Query $builder
     * @param $value
     * @return $this
     */
    public function orColumnInSubQuery(Query $builder, $value): self
    {
        $column = '(' . $builder->getSelectQuery() . ')';
        $v = addslashes(stripslashes($value));
        $this->or[] = "{$v} IN ({$column})";
        return $this;
    }

    /**
     * @param Where $where
     * @return $this
     */
    final public function andWhere(Where $where): self
    {
        $this->and[] = $where;
        return $this;
    }

    /**
     * @param Where $where
     * @return $this
     */
    final public function orWhere(Where $where): self
    {
        $this->or[] = $where;
        return $this;
    }
}