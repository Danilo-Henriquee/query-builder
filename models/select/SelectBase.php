<?php 

class SelectBase {
    protected $db;
    protected $usuarioLogado;
    protected $lojaLogado;

    protected string $tableName;

    /* SELECT */
    protected array $columns;
    protected ?Columns $onlyColumns;
    protected ?Columns $excludedColumns;

    /* WHERE */
    protected array $whereConditions;
    protected string $whereValuesTypes = "";
    protected array $whereValues = [];

    /* LEFT JOIN */
    protected array $joinsAliases;
    protected int $currentTableIndex = 1;

    protected function definePropsFromRawArrayReflection() {
        $columns = [];
        foreach ($this->columns as $columnAlias => $columnDbName) {
            $columns[] = Column::builder()
                ->columnName($columnDbName)
                ->alias($columnAlias)
                ->build();
        }

        $this->columns = $columns;
    }

    protected function setupSelectColumns(): string {
        if ($this->onlyColumns != null && $this->excludedColumns == null) {
            $this->columns = $this->onlyColumns->getColumns();
        }
        
        if ($this->excludedColumns != null && $this->onlyColumns == null) {
            $this->columns = $this->filterExcludedColumns();
        }

        $preparedColumnsToImplode = $this->setupColumnsAlias();

        $columnsString = "";
        
        $columnsString = implode(", ", $preparedColumnsToImplode);
        return $columnsString;
    }

    protected function setupWhereConditions(): string {
        $conditions = $this->whereConditions;

        $whereString = "";

        if (sizeof($conditions) > 1) {
            $whereString = $this->setupMultipleConditions($conditions);
            return $whereString;
        }

        $condition = $conditions[0];

        $this->whereValues[] = $condition->getValue();
        $this->appendWhereParamType($condition->getValue());

        $whereString = "t.{$condition->getColumnName()} {$condition->getComparisonOperator()} ?";

        return $whereString;
    }

    protected function setupLeftJoin(LeftJoin $joinTable): string {
        $index = $this->currentTableIndex;

        $leftJoinString = "";
        
        $leftJoinString  = "LEFT JOIN {$joinTable->getTableName()} t$index ON ";

        if ($joinTable->getOverridedTableName() != "") {
            $joinAlias = $this->joinsAliases[$joinTable->getOverridedTableName()];

            if ($joinTable->getOverridedColumnName() != "") {
                $leftJoinString .= "$joinAlias.{$joinTable->getOverridedColumnName()} {$joinTable->getCondition()} t$index.{$joinTable->getColumnName()} ";
                return $leftJoinString;
            }

            $leftJoinString .= "t$index.{$joinTable->getColumnName()} {$joinTable->getCondition()} t$index.{$joinTable->getColumnName()} ";
            return $leftJoinString;
        }

        $leftJoinString .= "t.{$joinTable->getColumnName()} {$joinTable->getCondition()} t$index.{$joinTable->getColumnName()} ";
        return $leftJoinString;
    }

    protected function setupJoinColumns(Columns $columns): string {
        $index = $this->currentTableIndex;
        
        $columnArr = [];
        foreach ($columns->getColumns() as $column) {
            $columnName = $column->getColumnName();
            $alias = $column->getAlias();

            if ($alias != "" && $alias != null) {
                $columnArr[] = "t$index.$columnName as $alias ";
                continue;
            }

            $columnArr[] = "t$index.$columnName ";
        }
        
        return implode(", ", $columnArr);
    }

    private function setupColumnsAlias(): array {
        return array_map(function ($column) {
            if ($column->getAlias() != "") {
                return "t.{$column->getColumnName()} as {$column->getAlias()}";
            }
            return "t.{$column->getColumnName()}";
        }, $this->columns);
    }

    private function filterExcludedColumns(): array {
        $excludedColumnsNames = array_map(
            function ($column) {
                return $column->getColumnName();
            },
            $this->excludedColumns->getColumns()
        );

        $filteredColumns =  array_filter(
            $this->columns,
            function ($column) use ($excludedColumnsNames) {
                return !in_array($column, $excludedColumnsNames);
            }
        );

        return $filteredColumns;
    }

    private function setupMultipleConditions($conditions) {
        $whereConditions = [];
        foreach ($conditions as $condition) {
            $this->whereValues[] = $condition->getValue();
            $this->whereValuesTypes .= $condition->getValueTypeToPreparedStatement();

            $whereConditions[] = "t.{$condition->getColumnName()} {$condition->getComparisonOperator()} ? ";
        }
        return implode("AND ", $whereConditions);
    }

    private function appendWhereParamType($value) {
        $this->whereValuesTypes .= gettype($value)[0];
    }
}

?>