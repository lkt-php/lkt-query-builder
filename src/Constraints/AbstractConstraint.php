<?php

namespace Lkt\QueryBuilding\Constraints;

abstract class AbstractConstraint
{
    protected $column = '';
    protected $value = null;

    public function __construct(string $column, $value = null)
    {
        $this->column = $column;
        $this->value = $value;
    }

    public static function define(string $column, $value = null)
    {
        return new static($column, $value);
    }

    abstract public function __toString(): string;
}