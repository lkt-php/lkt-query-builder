<?php

namespace Lkt\QueryBuilding\Constraints;

class IsNotNullConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        return "{$this->column} IS NOT NULL";
    }
}