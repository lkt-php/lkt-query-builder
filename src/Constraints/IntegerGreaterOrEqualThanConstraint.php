<?php

namespace Lkt\QueryBuilding\Constraints;

class IntegerGreaterOrEqualThanConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $v = addslashes(stripslashes((int)$this->value));
        return "{$this->column}>={$v}";
    }
}