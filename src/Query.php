<?php

namespace Lkt\QueryBuilding;

use Lkt\QueryBuilding\Helpers\ColumnHelper;
use Lkt\QueryBuilding\Traits\WhereConstraints;
use function Lkt\Tools\Arrays\implodeWithAND;

class Query
{
    use WhereConstraints;

    protected $columns = [];
    protected $table = '';
    protected $where = [];
    protected $data = [];

    protected $constraints = [];
    protected $orderBy = '';

    protected $page = -1;
    protected $limit = -1;

    protected $joins = [];
    protected $unions = [];

    /**
     * @param Query $builder
     * @param string $alias
     * @return $this
     */
    final public function union(Query $builder, string $alias): self
    {
        $this->unions[$alias] = $builder;
        return $this;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return $this
     */
    final public function pagination(int $page = 0, int $limit = 0): self
    {
        if ($page < 1) {
            $page = 1;
        }
        --$page;
        $this->page = $page;
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param string $orderBy
     * @return $this
     */
    final public function orderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    final public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    final protected function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string $table
     * @return static
     */
    public static function table(string $table): self
    {
        return (new static())->setTable($table);
    }

    /**
     * @param array $data
     * @return $this
     */
    final public function updateData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param Where $where
     * @return $this
     */
    final public function where(Where $where): self
    {
        $this->and[] = $where;
        return $this;
    }

    /**
     * @param string $where
     * @return $this
     */
    final public function constraint(string $where): self
    {
        $this->constraints[] = $where;
        return $this;
    }

    /**
     * @param Join $join
     * @return $this
     */
    final public function join(Join $join): self
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueryWhere(): string
    {
        $where = [$this->whereConstraintsToString()];
        foreach ($this->constraints as $value) {
            $where[] = $value;
        }

        $whereString = '';
        if (isset($where[0])) {
            $whereString = ' AND ' . implodeWithAND($where);
        }

        return $whereString;
    }

    /**
     * @param string $type
     * @param string $countableField
     * @return string
     */
    protected function getQuery(string $type, string $countableField = ''): string
    {
        $whereString = $this->getQueryWhere();

        switch ($type) {
            case 'select':
            case 'selectDistinct':
            case 'count':
                $from = [];
                foreach ($this->joins as $join) {
                    $from[] = (string)$join;
                }
                $fromString = implode(' ', $from);
                $fromString = str_replace('{{---LKT_PARENT_TABLE---}}', $this->table, $fromString);

                $distinct = '';

                if ($type === 'selectDistinct') {
                    $distinct = 'DISTINCT';
                    $type = 'select';
                }

                if ($type === 'select') {
                    $columns = $this->buildColumns();
                    $orderBy = '';
                    $pagination = '';

                    if ($this->orderBy !== '') {
                        $orderBy = " ORDER BY {$this->orderBy}";
                    }

                    if ($this->page > -1 && $this->limit > -1) {
                        $p = $this->page * $this->limit;
                        $pagination = " LIMIT {$p}, {$this->limit}";

                    } elseif ($this->limit > -1) {
                        $pagination = " LIMIT {$this->limit}";
                    }


                    return "SELECT {$distinct} {$columns} FROM {$this->table} {$fromString} WHERE 1 {$whereString} {$orderBy} {$pagination}";
                }

                if ($type === 'count') {
                    return "SELECT COUNT({$countableField}) AS Count FROM {$this->table} {$fromString} WHERE 1 {$whereString}";
                }
                return '';

            case 'update':
            case 'insert':
                $data = ColumnHelper::makeUpdateParams($this->data);

                if ($type === 'update') {
                    return "UPDATE {$this->table} SET {$data} WHERE 1 {$whereString}";
                }

                if ($type === 'insert') {
                    return "INSERT INTO {$this->table} SET {$data}";
                }
                return '';

            default:
                return '';
        }
    }

    /**
     * @return string
     */
    final public function getSelectQuery(): string
    {
        return $this->getQuery('select');
    }

    /**
     * @return string
     */
    final public function getSelectDistinctQuery(): string
    {
        return $this->getQuery('selectDistinct');
    }

    /**
     * @param string $countableField
     * @return string
     */
    final public function getCountQuery(string $countableField): string
    {
        return $this->getQuery('count', $countableField);
    }

    /**
     * @return string
     */
    final public function getInsertQuery(): string
    {
        return $this->getQuery('insert');
    }

    /**
     * @return string
     */
    final public function getUpdateQuery(): string
    {
        return $this->getQuery('update');
    }

    /**
     * @return string
     */
    private function buildColumns(): string
    {
        $r = [];
        foreach ($this->columns as $column) {
            $r[] = ColumnHelper::buildColumnString($column, $this->table);
        }

        return implode(',', $r);
    }
}