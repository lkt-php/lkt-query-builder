<?php

namespace Lkt\QueryBuilding\Constraints;

class ForeignKeysContainsConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $key = $this->column;
        $v = addslashes(stripslashes($this->value));

        if ($v !== '') {
            $t = [];
            $t[] = "{$key} LIKE '%,{$v},%'";
            $t[] = "{$key} LIKE '%,{$v}'";
            $t[] = "{$key} LIKE '{$v},%'";
            $t[] = "{$key} = '{$v}'";
            return '(' . implode(' OR ', $t) . ')';
        }
        return '';
    }
}