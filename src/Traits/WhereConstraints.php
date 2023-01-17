<?php

namespace Lkt\QueryBuilding\Traits;

use Lkt\Factory\Schemas\Fields\AbstractField;
use Lkt\Factory\Schemas\Schema;
use Lkt\QueryBuilding\Constraints\AbstractConstraint;
use Lkt\QueryBuilding\Constraints\BooleanFalseConstraint;
use Lkt\QueryBuilding\Constraints\BooleanTrueConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeBeginsLikeConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeBetweenConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeEndsLikeConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeEqualConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeGreaterOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeGreaterOrEqualThanNowConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeGreaterThanConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeGreaterThanNowConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeLikeConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeLowerOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeLowerOrEqualThanNowConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeLowerThanConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeLowerThanNowConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeNotBeginsLikeConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeNotConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeNotEndsLikeConstraint;
use Lkt\QueryBuilding\Constraints\DatetimeNotLikeConstraint;
use Lkt\QueryBuilding\Constraints\DecimalBetweenConstraint;
use Lkt\QueryBuilding\Constraints\DecimalEqualConstraint;
use Lkt\QueryBuilding\Constraints\DecimalGreaterOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalGreaterThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalInConstraint;
use Lkt\QueryBuilding\Constraints\DecimalLowerOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalLowerThanConstraint;
use Lkt\QueryBuilding\Constraints\DecimalNotConstraint;
use Lkt\QueryBuilding\Constraints\DecimalNotInConstraint;
use Lkt\QueryBuilding\Constraints\FieldEqualToFieldConstraint;
use Lkt\QueryBuilding\Constraints\ForeignKeysContainsConstraint;
use Lkt\QueryBuilding\Constraints\IntegerBetweenConstraint;
use Lkt\QueryBuilding\Constraints\IntegerEqualConstraint;
use Lkt\QueryBuilding\Constraints\IntegerGreaterOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerGreaterThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerInConstraint;
use Lkt\QueryBuilding\Constraints\IntegerLowerOrEqualThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerLowerThanConstraint;
use Lkt\QueryBuilding\Constraints\IntegerNotConstraint;
use Lkt\QueryBuilding\Constraints\IntegerNotInConstraint;
use Lkt\QueryBuilding\Constraints\IsNotNullConstraint;
use Lkt\QueryBuilding\Constraints\IsNullConstraint;
use Lkt\QueryBuilding\Constraints\RawConstraint;
use Lkt\QueryBuilding\Constraints\StringBeginsLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringEndsLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringEqualConstraint;
use Lkt\QueryBuilding\Constraints\StringInConstraint;
use Lkt\QueryBuilding\Constraints\StringLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringNotBeginsLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringNotConstraint;
use Lkt\QueryBuilding\Constraints\StringNotEndsLikeConstraint;
use Lkt\QueryBuilding\Constraints\StringNotInConstraint;
use Lkt\QueryBuilding\Constraints\StringNotLikeConstraint;
use Lkt\QueryBuilding\Constraints\SubQueryCountEqualConstraint;
use Lkt\QueryBuilding\DateIntervals\AbstractInterval;
use Lkt\QueryBuilding\Enums\ProcessRule;
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
            if ($constraint instanceof AbstractConstraint) {
                $constraint->setTable($this->getTable(), $this->getTableAlias());
            }
            $r[] = (string)$constraint;
        }


        if ($this->hasJoinedBuilders()) {
            foreach ($this->getJoinedBuilders() as $key => $joinedBuilder) {
                $joinedWhereAux = trim($joinedBuilder->whereConstraintsToString());
                if ($joinedWhereAux) {
                    $r[] = $joinedWhereAux;
                }
            }
        }

        $or = [];
        foreach ($this->or as $constraint) {
            if ($constraint instanceof AbstractConstraint) {
                $constraint->setTable($this->getTable(), $this->getTableAlias());
            }
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

    public function andStringNotLike(string $column, string $value): self
    {
        $this->and[] = StringNotLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringNotLike(string $column, string $value): self
    {
        $this->or[] = StringNotLikeConstraint::define($column, $value);
        return $this;
    }

    public function andStringBeginsLike(string $column, string $value): self
    {
        $this->and[] = StringBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringBeginsLike(string $column, string $value): self
    {
        $this->or[] = StringBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andStringNotBeginsLike(string $column, string $value): self
    {
        $this->and[] = StringNotBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringNotBeginsLike(string $column, string $value): self
    {
        $this->or[] = StringNotBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andStringEndsLike(string $column, string $value): self
    {
        $this->and[] = StringEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringEndsLike(string $column, string $value): self
    {
        $this->or[] = StringEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andStringNotEndsLike(string $column, string $value): self
    {
        $this->and[] = StringNotEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orStringNotEndsLike(string $column, string $value): self
    {
        $this->or[] = StringNotEndsLikeConstraint::define($column, $value);
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

    public function andIntegerIn(string $column, array $values): self
    {
        $this->and[] = IntegerInConstraint::define($column, $values);
        return $this;
    }

    public function orIntegerIn(string $column, array $values): self
    {
        $this->or[] = IntegerInConstraint::define($column, $values);
        return $this;
    }

    public function andDecimalIn(string $column, array $values): self
    {
        $this->and[] = DecimalInConstraint::define($column, $values);
        return $this;
    }

    public function orDecimalIn(string $column, array $values): self
    {
        $this->or[] = DecimalInConstraint::define($column, $values);
        return $this;
    }

    public function andIntegerNotIn(string $column, array $values): self
    {
        $this->and[] = IntegerNotInConstraint::define($column, $values);
        return $this;
    }

    public function orIntegerNotIn(string $column, array $values): self
    {
        $this->or[] = IntegerNotInConstraint::define($column, $values);
        return $this;
    }

    public function andDecimalNotIn(string $column, array $values): self
    {
        $this->and[] = DecimalNotInConstraint::define($column, $values);
        return $this;
    }

    public function orDecimalNotIn(string $column, array $values): self
    {
        $this->or[] = DecimalNotInConstraint::define($column, $values);
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

    public function andIntegerGreaterOrEqualThan(string $column, $value): self
    {
        $this->and[] = IntegerGreaterOrEqualThanConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerGreaterOrEqualThan(string $column, $value): self
    {
        $this->or[] = IntegerGreaterOrEqualThanConstraint::define($column, $value);
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

    public function andIntegerLowerOrEqualThan(string $column, $value): self
    {
        $this->and[] = IntegerLowerOrEqualThanConstraint::define($column, $value);
        return $this;
    }

    public function orIntegerLowerOrEqualThan(string $column, $value): self
    {
        $this->or[] = IntegerLowerOrEqualThanConstraint::define($column, $value);
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

    public function andIsNull(string $column): self
    {
        $this->and[] = IsNullConstraint::define($column);
        return $this;
    }

    public function orIsNull(string $column): self
    {
        $this->or[] = IsNullConstraint::define($column);
        return $this;
    }

    public function andIsNotNull(string $column): self
    {
        $this->and[] = IsNotNullConstraint::define($column);
        return $this;
    }

    public function orIsNotNull(string $column): self
    {
        $this->or[] = IsNotNullConstraint::define($column);
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

    public function andDecimalGreaterOrEqualThan(string $column, $value): self
    {
        $this->and[] = DecimalGreaterOrEqualThanConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalGreaterOrEqualThan(string $column, $value): self
    {
        $this->or[] = DecimalGreaterOrEqualThanConstraint::define($column, $value);
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

    public function andDecimalLowerOrEqualThan(string $column, $value): self
    {
        $this->and[] = DecimalLowerOrEqualThanConstraint::define($column, $value);
        return $this;
    }

    public function orDecimalLowerOrEqualThan(string $column, $value): self
    {
        $this->or[] = DecimalLowerOrEqualThanConstraint::define($column, $value);
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

    public function andDatetimeBetween(string $column, $from, $to): self
    {
        $this->and[] = DatetimeBetweenConstraint::define($column, [$from, $to]);
        return $this;
    }

    public function orDatetimeBetween(string $column, $from, $to): self
    {
        $this->or[] = DatetimeBetweenConstraint::define($column, [$from, $to]);
        return $this;
    }

    public function andDatetimeEqual(string $column, $value): self
    {
        $this->and[] = DatetimeEqualConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeEqual(string $column, $value): self
    {
        $this->or[] = DatetimeEqualConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeNot(string $column, $value): self
    {
        $this->and[] = DatetimeNotConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeNot(string $column, $value): self
    {
        $this->or[] = DatetimeNotConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeGreaterOrEqualThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeGreaterOrEqualThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function orDatetimeGreaterOrEqualThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeGreaterOrEqualThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function andDatetimeGreaterOrEqualThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeGreaterOrEqualThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function orDatetimeGreaterOrEqualThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeGreaterOrEqualThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function andDatetimeGreaterThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeGreaterThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function orDatetimeGreaterThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeGreaterThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function andDatetimeGreaterThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeGreaterThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function orDatetimeGreaterThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeGreaterThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function andDatetimeLowerOrEqualThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeLowerOrEqualThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function orDatetimeLowerOrEqualThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeLowerOrEqualThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function andDatetimeLowerOrEqualThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeLowerOrEqualThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function orDatetimeLowerOrEqualThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeLowerOrEqualThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function andDatetimeLowerThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeLowerThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function orDatetimeLowerThan(string $column, $value, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeLowerThanConstraint::define($column, $value)->setInterval($interval);
        return $this;
    }

    public function andDatetimeLowerThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->and[] = DatetimeLowerThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function orDatetimeLowerThanNow(string $column, AbstractInterval $interval = null): self
    {
        $this->or[] = DatetimeLowerThanNowConstraint::define($column)->setInterval($interval);
        return $this;
    }

    public function andDatetimeLike(string $column, string $value): self
    {
        $this->and[] = DatetimeLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeLike(string $column, string $value): self
    {
        $this->or[] = DatetimeLikeConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeNotLike(string $column, string $value): self
    {
        $this->and[] = DatetimeNotLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeNotLike(string $column, string $value): self
    {
        $this->or[] = DatetimeNotLikeConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeBeginsLike(string $column, string $value): self
    {
        $this->and[] = DatetimeBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeBeginsLike(string $column, string $value): self
    {
        $this->or[] = DatetimeBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeNotBeginsLike(string $column, string $value): self
    {
        $this->and[] = DatetimeNotBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeNotBeginsLike(string $column, string $value): self
    {
        $this->or[] = DatetimeNotBeginsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeEndsLike(string $column, string $value): self
    {
        $this->and[] = DatetimeEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeEndsLike(string $column, string $value): self
    {
        $this->or[] = DatetimeEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andDatetimeNotEndsLike(string $column, string $value): self
    {
        $this->and[] = DatetimeNotEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function orDatetimeNotEndsLike(string $column, string $value): self
    {
        $this->or[] = DatetimeNotEndsLikeConstraint::define($column, $value);
        return $this;
    }

    public function andIntegerBetween(string $column, $from, $to): self
    {
        $this->and[] = IntegerBetweenConstraint::define($column, [$from, $to]);
        return $this;
    }

    public function orIntegerBetween(string $column, $from, $to): self
    {
        $this->or[] = IntegerBetweenConstraint::define($column, [$from, $to]);
        return $this;
    }

    public function andDecimalBetween(string $column, $from, $to): self
    {
        $this->and[] = DecimalBetweenConstraint::define($column, [$from, $to]);
        return $this;
    }

    public function orDecimalBetween(string $column, $from, $to): self
    {
        $this->or[] = DecimalBetweenConstraint::define($column, [$from, $to]);
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

    public function andFieldEqualToField(string $field, string $remoteSchema, string $remoteField): static
    {
        $schema = Schema::get($remoteSchema);
        $remoteFieldAux = $schema->getField($remoteField);

        $tmp = [];
        $table = $schema->getTable();
        if ($table) {
            $tmp[] = $table;
        }
        if ($remoteFieldAux instanceof AbstractField) {
            $tmp[] = $remoteFieldAux->getColumn();
        } else {
            $tmp[] = $remoteField;
        }

        $tmp = implode('.', $tmp);

        $this->and[] = FieldEqualToFieldConstraint::define($field, $tmp);
        return $this;
    }

    public function orFieldEqualToField(string $field, string $remoteSchema, string $remoteField): static
    {
        $schema = Schema::get($remoteSchema);
        $remoteFieldAux = $schema->getField($remoteField);

        $tmp = [];
        $table = $schema->getTable();
        if ($table) {
            $tmp[] = $table;
        }
        if ($remoteFieldAux instanceof AbstractField) {
            $tmp[] = $remoteFieldAux->getColumn();
        } else {
            $tmp[] = $remoteField;
        }

        $tmp = implode('.', $tmp);

        $this->or[] = FieldEqualToFieldConstraint::define($field, $tmp);
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

    public function addStringProcessRule(string $column, $value, string $rule)
    {
        if ($rule === ProcessRule::like) {
            return $this->andStringLike($column, $value);
        }
        if ($rule === ProcessRule::notLike) {
            return $this->andStringNotLike($column, $value);
        }
        if ($rule === ProcessRule::equal) {
            return $this->andStringEqual($column, $value);
        }
        if ($rule === ProcessRule::beginsLike) {
            return $this->andStringBeginsLike($column, $value);
        }
        if ($rule === ProcessRule::notBeginsLike) {
            return $this->andStringNotBeginsLike($column, $value);
        }
        if ($rule === ProcessRule::endsLike) {
            return $this->andStringEndsLike($column, $value);
        }
        if ($rule === ProcessRule::notEndsLike) {
            return $this->andStringNotEndsLike($column, $value);
        }
        if ($rule === ProcessRule::in) {
            if (!is_array($value)) {
                $value = [$value];
            }
            return $this->andStringIn($column, $value);
        }
        if ($rule === ProcessRule::notIn) {
            if (!is_array($value)) {
                $value = [$value];
            }
            return $this->andStringNotIn($column, $value);
        }
        return $this;
    }

    public function addIntegerProcessRule(string $column, $value, string $rule)
    {
        if ($rule === ProcessRule::equal) {
            return $this->andIntegerEqual($column, $value);
        }
        if ($rule === ProcessRule::greaterThan) {
            return $this->andIntegerGreaterThan($column, $value);
        }
        if ($rule === ProcessRule::greaterOrEqualThan) {
            return $this->andIntegerGreaterOrEqualThan($column, $value);
        }
        if ($rule === ProcessRule::lowerThan) {
            return $this->andIntegerLowerThan($column, $value);
        }
        if ($rule === ProcessRule::lowerOrEqualThan) {
            return $this->andIntegerLowerOrEqualThan($column, $value);
        }
        if ($rule === ProcessRule::in) {
            if (!is_array($value)) {
                $value = [$value];
            }
            return $this->andIntegerIn($column, $value);
        }
        if ($rule === ProcessRule::notIn) {
            if (!is_array($value)) {
                $value = [$value];
            }
            return $this->andIntegerNotIn($column, $value);
        }
        return $this;
    }
}