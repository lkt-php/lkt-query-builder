<?php

namespace Lkt\QueryBuilding\Constraints;

class StringEqualConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $column = $this->column;
        $value = $this->value;
        if (strpos($value, 'COMPRESS(') === 0) {
            return "{$column}={$value}";
        }

        $v = addslashes(stripslashes($value));
        $prepend = $this->getTablePrepend();
        return "{$prepend}{$column}='{$v}'";
    }
}