<?php

namespace Lkt\QueryBuilding\Constraints;

class StringInConstraint extends AbstractConstraint
{
    public function __toString(): string
    {
        if (count($this->value) === 0) {
            return '';
        }
        $values = array_map(function($v){ return addslashes(stripslashes($v));}, $this->value);
        $value = "('".implode("','", $values)."')";

        $prepend = $this->getTablePrepend();
        if ($this->hasBinaryMode()) $prepend = "BINARY {$prepend}";
        return "{$prepend}{$this->column} IN {$value}";
    }
}