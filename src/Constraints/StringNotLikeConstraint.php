<?php

namespace Lkt\QueryBuilding\Constraints;

class StringNotLikeConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        $v = addslashes(stripslashes($this->value));
        $prepend = $this->getTablePrepend();
        if ($this->hasBinaryMode()) $prepend = "BINARY {$prepend}";
        return "{$prepend}{$this->column} NOT LIKE '%{$v}%'";
    }
}