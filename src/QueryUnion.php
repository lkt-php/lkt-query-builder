<?php

namespace Lkt\QueryBuilding;

use Lkt\QueryBuilding\Traits\OrderByTrait;
use Lkt\QueryBuilding\Traits\PaginationTrait;
use Lkt\QueryBuilding\Traits\WhereConstraints;

class QueryUnion
{
    use PaginationTrait,
        WhereConstraints,
        OrderByTrait;

    protected array $builders = [];

    protected string $table = 't';
    protected string $select = '*';

    public static function getEmpty(): static
    {
        return new static();
    }

    public function addQuery(Query $query): static
    {
        $this->builders[] = $query;
        return $this;
    }

    final public function setTable(string $table = 't'): static
    {
        $this->table = $table;
        return $this;
    }

    final public function countMode(): static
    {
        $this->select = 'COUNT(*) as Total';
        return $this;
    }

    final public function selectMode(): static
    {
        $this->select = '*';
        return $this;
    }

    final public function toString(): string
    {
        $queries = [];
        foreach ($this->builders as $builder) {
            $queries[] = $builder->getSelectDistinctQuery();
        }

        $query = '(' . implode(') UNION (', $queries) . ')';

        $whereString = $this->getQueryWhere();
        $query = "SELECT {$this->select} FROM ($query) as {$this->table} {$whereString}";

        if ($this->hasOrder()) {
            $query .= " ORDER BY {$this->getOrder()}";
        }
        $pagination = $this->getPaginationString();

        $query .= " {$pagination}";

        return $query;
    }
}