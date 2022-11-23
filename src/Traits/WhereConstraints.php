<?php

namespace Lkt\QueryBuilding\Traits;

use Lkt\QueryBuilding\Constraints\BooleanFalseConstraint;
use Lkt\QueryBuilding\Constraints\BooleanTrueConstraint;
use Lkt\QueryBuilding\Constraints\DecimalEqualConstraint;
use Lkt\QueryBuilding\Constraints\DecimalGreaterThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalLowerThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalNotConstraint;
use Lkt\QueryBuilding\Constraints\ForeignKeysContainsConstraint;
use Lkt\QueryBuilding\Constraints\IntegerEqualConstraint;
use Lkt\QueryBuilding\Constraints\IntegerGreaterThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerLowerThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerNotConstraint;
use Lkt\QueryBuilding\Constraints\RawConstraint;
use Lkt\QueryBuilding\Constraints\StringEqualConstraint;
use Lkt\QueryBuilding\Constraints\StringInConstraint;
use Lkt\QueryBuilding\Constraints\StringLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringNotConstraint;
use Lkt\QueryBuilding\Constraints\StringNotInConstraint;
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

    public function andStringEqual(string $column, string $value): self
    {
        $this->and[] = StringEqualConstraint::define($column, $value);
        return $this;
    }

    public function orStringEqual(string $column, string $value): self
    {
        $this->or[] = StringEqualConstraint::define($column, $value);
        return $this;
    }

    public function andStringNot(string $column, string $value): self
    {
        $this->and[] = StringNotConstraint::define($column, $value);
        return $this;
    }

    public function orStringNot(string $column, string $value): self
    {
        $this->or[] = StringNotConstraint::define($column, $value);
        return $this;
    }

    public function andStringLike(string $column, string $value): self
    {
        $this->and[] = StringLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringLike(string $column, string $value): self
    {
        $this->or[] = StringLikeConstraint::define($column, $value);
        return $this;
    }

    public function andStringIn(string $column, array $values): self
    {
        $this->and[] = StringInConstraint::define($column, $values);
        return $this;
    }

    public function orStringIn(string $column, array $values): self
    {
        $this->or[] = StringInConstraint::define($column, $values);
        return $this;
    }

    public function andStringNotIn(string $column, array $values): self
    {
        $this->and[] = StringNotInConstraint::define($column, $values);
        return $this;
    }

    public function orStringNotIn(string $column, array $values): self
    {
        $this->or[] = StringNotInConstraint::define($column, $values);
        return $this;
    }

    public function andIntegerEqual(string $column, $value): self
    {
        $this->and[] = IntegerEqualConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerEqual(string $column, $value): self
    {
        $this->or[] = IntegerEqualConstraint::define($column, $value);
        return $this;
    }

    public function andIntegerNot(string $column, $value): self
    {
        $this->and[] = IntegerNotConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerNot(string $column, $value): self
    {
        $this->or[] = IntegerNotConstraint::define($column, $value);
        return $this;
    }

    public function andIntegerGreaterThan(string $column, $value): self
    {
        $this->and[] = IntegerGreaterThanConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerGreaterThan(string $column, $value): self
    {
        $this->or[] = IntegerGreaterThanConstraint::define($column, $value);
        return $this;
    }

    public function andIntegerLowerThan(string $column, $value): self
    {
        $this->and[] = IntegerLowerThanConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerLowerThan(string $column, $value): self
    {
        $this->or[] = IntegerLowerThanConstraint::define($column, $value);
        return $this;
    }

    public function andDecimalEqual(string $column, $value): self
    {
        $this->and[] = DecimalEqualConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalEqual(string $column, $value): self
    {
        $this->or[] = DecimalEqualConstraint::define($column, $value);
        return $this;
    }

    public function andDecimalNot(string $column, $value): self
    {
        $this->and[] = DecimalNotConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalNot(string $column, $value): self
    {
        $this->or[] = DecimalNotConstraint::define($column, $value);
        return $this;
    }

    public function andDecimalGreaterThan(string $column, $value): self
    {
        $this->and[] = DecimalGreaterThanConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalGreaterThan(string $column, $value): self
    {
        $this->or[] = DecimalGreaterThanConstraint::define($column, $value);
        return $this;
    }

    public function andDecimalLowerThan(string $column, $value): self
    {
        $this->and[] = DecimalLowerThanConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalLowerThan(string $column, $value): self
    {
        $this->or[] = DecimalLowerThanConstraint::define($column, $value);
        return $this;
    }

    public function andBooleanTrue(string $column): self
    {
        $this->and[] = BooleanTrueConstraint::define($column);
        return $this;
    }

    public function orBooleanTrue(string $column): self
    {
        $this->or[] = BooleanTrueConstraint::define($column);
        return $this;
    }

    public function andBooleanFalse(string $column): self
    {
        $this->and[] = BooleanFalseConstraint::define($column);
        return $this;
    }

    public function orBooleanFalse(string $column): self
    {
        $this->or[] = BooleanFalseConstraint::define($column);
        return $this;
    }

    public function andForeignKeysContains(string $column, $value): self
    {
        $this->and[] = ForeignKeysContainsConstraint::define($column, $value);
        return $this;
    }

    public function orForeignKeysContains(string $column, $value): self
    {
        $this->or[] = ForeignKeysContainsConstraint::define($column, $value);
        return $this;
    }

    public function andRaw(string $value): self
    {
        $this->and[] = RawConstraint::define($value);
        return $this;
    }

    public function orRaw(string $value): self
    {
        $this->or[] = RawConstraint::define($value);
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