<?php

namespace Lkt\QueryBuilding;

use Lkt\QueryBuilding\Enums\JoinType;
use Lkt\QueryBuilding\Helpers\ColumnHelper;
use Lkt\QueryBuilding\Traits\PaginationTrait;
use Lkt\QueryBuilding\Traits\WhereConstraints;
use function Lkt\Tools\Arrays\implodeWithAND;

class Query
{
    use WhereConstraints,
        PaginationTrait;

    protected array $columns = [];
    protected string $table = '';
    protected string $tableAlias = '';
    protected array $where = [];
    protected array $data = [];

    protected array $constraints = [];
    protected string $orderBy = '';

    protected array $joins = [];
    protected array $unions = [];

    protected array $joinedBuilders = [];
    protected array $joinedBuildersRelation = [];

    final public function leftJoin(Query $query, $joinedBuilderColumn, $thisBuilderColumn): static
    {
        $k = $query->getJoinKey();
        $this->joinedBuilders[$k] = $query;
        $this->joinedBuildersRelation[$k] = [JoinType::left, $joinedBuilderColumn, $thisBuilderColumn];
        return $this;
    }

    final public function rightJoin(Query $query, $joinedBuilderColumn, $thisBuilderColumn): static
    {
        $k = $query->getJoinKey();
        $this->joinedBuilders[$k] = $query;
        $this->joinedBuildersRelation[$k] = [JoinType::right, $joinedBuilderColumn, $thisBuilderColumn];
        return $this;
    }

    /**
     * @deprecated
     */
    final public function union(Query $builder, string $alias): static
    {
        $this->unions[$alias] = $builder;
        return $this;
    }

    final public function orderBy(string $orderBy): static
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    final public function setColumns(array $columns): static
    {
        $this->columns = $columns;
        return $this;
    }

    final protected function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    final public function setTableAlias(string $alias): static
    {
        $this->tableAlias = $alias;
        return $this;
    }

    public static function table(string $table): static
    {
        return (new static())->setTable($table);
    }

    final public function updateData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    final public function where(Where $where): static
    {
        $this->and[] = $where;
        return $this;
    }

    final public function constraint(string $where): static
    {
        $this->constraints[] = $where;
        return $this;
    }

    final public function join(Join $join): static
    {
        $this->joins[] = $join;
        return $this;
    }

    public function getQueryWhere(): string
    {
        $where = [];

        $whereConstraints = $this->whereConstraintsToString();
        if ($whereConstraints !== '') {
            $where[] = $whereConstraints;
        }

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
     * @deprecated
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
                    $pagination = $this->getPaginationString();

                    if ($this->orderBy !== '') {
                        $orderBy = " ORDER BY {$this->orderBy}";
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
     * @deprecated
     */
    private function buildColumns(): string
    {
        $r = [];
        foreach ($this->columns as $column) {
            $r[] = ColumnHelper::buildColumnString($column, $this->table);
        }

        return implode(',', $r);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getTableAlias(): string
    {
        return $this->tableAlias;
    }

    public function hasTableAlias(): bool
    {
        return $this->tableAlias !== '';
    }

    public function getJoinKey(): string
    {
        if ($this->hasTableAlias()) {
            return $this->getTableAlias();
        }
        return $this->getTable();
    }

    public function hasJoinedBuilders(): bool
    {
        return count($this->joinedBuilders) > 0;
    }

    /**
     * @return static[]
     */
    public function getJoinedBuilders(): array
    {
        return $this->joinedBuilders;
    }

    public function getJoinedBuildersRelations(): array
    {
        return $this->joinedBuildersRelation;
    }

    public function getJoinedBuildersRelation(string $key): ?array
    {
        return $this->joinedBuildersRelation[$key];
    }

    public function getTableNameOrAlias(): string
    {
        if ($this->hasTableAlias()) {
            return $this->getTableAlias();
        }
        return $this->getTable();
    }

    public function getTableNameWithAlias(): string
    {
        if ($this->hasTableAlias()) {
            return "{$this->getTable()} AS {$this->getTableAlias()}";
        }
        return $this->getTable();
    }

    public function getJoinString(string $joinType, $joinedColumn, $otherBuilderColumn): string
    {
        $additional = '';
        if ($this->hasJoinedBuilders()) {
            foreach ($this->getJoinedBuilders() as $key => $joinedBuilder) {
                $joinData = $this->getJoinedBuildersRelation($key);
                $additional .= $joinedBuilder->getJoinString($joinData[0], $joinData[1], $this->formatJoinedColumn($joinData[2]));
            }
        }

        $_joinType = strtoupper($joinType);
        $joinedColumn = $this->formatJoinedColumn($joinedColumn);
        $table = $this->getTableNameWithAlias();
        return "{$_joinType} JOIN {$table} ON ({$joinedColumn} = {$otherBuilderColumn}) {$additional}";
    }

    public function formatJoinedColumn($joinedColumn)
    {
        if (is_string($joinedColumn)) {
            $table = $this->getTableNameOrAlias();
            return "{$table}.{$joinedColumn}";
        }
        return $joinedColumn;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }

    public function hasOrder(): bool
    {
        return $this->orderBy !== '';
    }

    public function getOrder(): string
    {
        return $this->orderBy;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}