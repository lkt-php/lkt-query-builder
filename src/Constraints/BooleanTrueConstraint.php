<?php

namespace Lkt\QueryBuilding\Constraints;

class BooleanTrueConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        return "{$this->column}=1";
    }
}