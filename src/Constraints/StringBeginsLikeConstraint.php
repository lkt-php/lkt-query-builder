<?php

namespace Lkt\QueryBuilding\Constraints;

class StringBeginsLikeConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $v = addslashes(stripslashes($this->value));
        return "{$this->column} LIKE '{$v}%'";
    }
}