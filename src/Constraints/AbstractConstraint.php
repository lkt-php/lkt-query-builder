<?php

namespace Lkt\QueryBuilding\Constraints;

abstract class AbstractConstraint
{
    protected $column = '';
    protected $value = null;
    protected $settings = [];

    public function __construct(string $column, $value = null, array $settings = [])
    {
        $this->column = $column;
        $this->value = $value;
        $this->settings = $settings;
    }

    public static function define(string $column, $value = null, array $settings = [])
    {
        return new static($column, $value, $settings);
    }

    abstract public function __toString(): string;
}