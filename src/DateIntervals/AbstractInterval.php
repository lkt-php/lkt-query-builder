<?php

namespace Lkt\QueryBuilding\DateIntervals;

abstract class AbstractInterval
{
    protected $amount = 0;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    protected function isValid(): bool
    {
        return $this->amount !== 0;
    }

    protected function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public static function define(int $amount = 0)
    {
        return new static($amount);
    }

    abstract function toString(): string;

    public function __toString(): string
    {
        return $this->toString();
    }
}