<?php

namespace Lkt\QueryBuilding\Constraints;

class IsNullConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        return "{$this->column} IS NULL";
    }
}