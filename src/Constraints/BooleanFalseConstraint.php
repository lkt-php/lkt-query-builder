<?php

namespace Lkt\QueryBuilding\Constraints;

class BooleanFalseConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        return "{$this->column}=0";
    }
}