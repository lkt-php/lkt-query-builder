<?php

namespace Lkt\QueryBuilding\Constraints;

class DecimalLowerThanConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $v = addslashes(stripslashes((float)$this->value));
        return "{$this->column}<{$v}";
    }
}