<?php

namespace Lkt\QueryBuilding\Traits;

trait WhereStaticConstraints
{
    public static function booleanFalse(string $column)
    {
        return (new static())->andBooleanFalse($column);
    }

    public static function booleanTrue(string $column)
    {
        return (new static())->andBooleanTrue($column);
    }

    public static function datetimeBetween(string $column, $from, $to)
    {
        return (new static())->andDatetimeBetween($column, $from, $to);
    }

    public static function decimalBetween(string $column, $from, $to)
    {
        return (new static())->andDecimalBetween($column, $from, $to);
    }

    public static function decimalEqual(string $column, float $value)
    {
        return (new static())->andDecimalEqual($column, $value);
    }

    public static function decimalGreaterOrEqualThan(string $column, float $value)
    {
        return (new static())->andDecimalGreaterOrEqualThan($column, $value);
    }

    public static function decimalGreaterThan(string $column, float $value)
    {
        return (new static())->andDecimalGreaterThan($column, $value);
    }

    public static function decimalIn(string $column, array $value)
    {
        return (new static())->andDecimalIn($column, $value);
    }

    public static function decimalLowerOrEqualThan(string $column, float $value)
    {
        return (new static())->andDecimalLowerOrEqualThan($column, $value);
    }

    public static function decimalLowerThan(string $column, float $value)
    {
        return (new static())->andDecimalLowerThan($column, $value);
    }

    public static function decimalNot(string $column, float $value)
    {
        return (new static())->andDecimalNot($column, $value);
    }

    public static function decimalNotIn(string $column, array $value)
    {
        return (new static())->andDecimalNotIn($column, $value);
    }

    public static function foreignKeysContains(string $column, $value)
    {
        return (new static())->andForeignKeysContains($column, $value);
    }

    public static function integerBetween(string $column, $from, $to)
    {
        return (new static())->andIntegerBetween($column, $from, $to);
    }

    public static function integerEqual(string $column, int $value)
    {
        return (new static())->andIntegerEqual($column, $value);
    }

    public static function integerGreaterOrEqualThan(string $column, int $value)
    {
        return (new static())->andIntegerGreaterOrEqualThan($column, $value);
    }

    public static function integerGreaterThan(string $column, int $value)
    {
        return (new static())->andIntegerGreaterThan($column, $value);
    }

    public static function integerIn(string $column, array $value)
    {
        return (new static())->andIntegerIn($column, $value);
    }

    public static function integerLowerOrEqualThan(string $column, int $value)
    {
        return (new static())->andIntegerLowerOrEqualThan($column, $value);
    }

    public static function integerLowerThan(string $column, int $value)
    {
        return (new static())->andIntegerLowerThan($column, $value);
    }

    public static function integerNot(string $column, int $value)
    {
        return (new static())->andIntegerNot($column, $value);
    }

    public static function integerNotIn(string $column, array $value)
    {
        return (new static())->andIntegerNotIn($column, $value);
    }

    public static function isNotNull(string $column)
    {
        return (new static())->andIsNotNull($column);
    }

    public static function isNull(string $column)
    {
        return (new static())->andIsNull($column);
    }

    public static function raw(string $value)
    {
        return (new static())->andRaw($value);
    }

    public static function stringBeginsLike(string $column, string $value)
    {
        return (new static())->andStringBeginsLike($column, $value);
    }

    public static function stringEndsLike(string $column, string $value)
    {
        return (new static())->andStringEndsLike($column, $value);
    }

    public static function stringEqual(string $column, string $value)
    {
        return (new static())->andStringEqual($column, $value);
    }

    public static function stringIn(string $column, array $value)
    {
        return (new static())->andStringIn($column, $value);
    }

    public static function stringLike(string $column, string $value)
    {
        return (new static())->andStringLike($column, $value);
    }

    public static function stringNotBeginsLike(string $column, string $value)
    {
        return (new static())->andStringNotBeginsLike($column, $value);
    }

    public static function stringNot(string $column, string $value)
    {
        return (new static())->andStringNot($column, $value);
    }

    public static function stringNotEndsLike(string $column, string $value)
    {
        return (new static())->andStringNotEndsLike($column, $value);
    }

    public static function stringNotIn(string $column, array $value)
    {
        return (new static())->andStringNotIn($column, $value);
    }

    public static function stringNotLike(string $column, string $value)
    {
        return (new static())->andStringNotLike($column, $value);
    }
}