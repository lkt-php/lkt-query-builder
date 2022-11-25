<?php

namespace Lkt\QueryBuilding\Constraints;

class DecimalGreaterOrEqualThanConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $v = addslashes(stripslashes((float)$this->value));
        return "{$this->column}>={$v}";
    }
}