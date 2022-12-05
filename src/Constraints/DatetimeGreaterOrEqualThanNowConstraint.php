<?php

namespace Lkt\QueryBuilding\Constraints;

use Lkt\QueryBuilding\DateIntervals\AbstractInterval;
use Lkt\QueryBuilding\Traits\ConstraintWithInterval;

class DatetimeGreaterOrEqualThanNowConstraint extends AbstractConstraint
{
    use ConstraintWithInterval;

    public function __toString(): string
    {
        $column = $this->column;
        $v = 'NOW()';
        if ($this->interval instanceof AbstractInterval) {
            $v .= $this->interval->toString();
        }
        return "{$column} >= {$v}";
    }
}