<?php 

class SelectBase {
    protected $db;
    protected $usuarioLogado;
    protected $lojaLogado;

    protected string $tableName;

    /* SELECT */
    protected array $columns;
    protected array $excludedColumns;

    /* WHERE */
    protected array $whereConditions;
    protected string $whereValuesTypes = "";
    protected array $whereValues = [];

    /* LEFT JOIN */
    protected int $currentTableIndex = 1;

    private function filterColumns(): array {
        return array_filter($this->columns, function ($element) {
            return !in_array($element, $this->excludedColumns);
        });
    }

    private function setupColumnsAlias() {
        $columnsWithAlias = [];
        foreach ($this->columns as $column => $dbColumn) {
            $columnsWithAlias[] = "t.$dbColumn as $column";
        }

        $this->columns = $columnsWithAlias;
    }

    private function setupMultipleConditions($conditions) {
        $whereConditions = [];
        foreach ($conditions as $condition) {
            $column = $condition[0];
            $aritmetic = $condition[1];
            $value = $this->formatWhereConditionValue($condition[2]);
            $this->whereValues[] = $value;
            $this->whereValuesTypes .= $this->appendWhereParamType($condition[2]);

            $whereConditions[] = "t.$column $aritmetic $value ";
        }
        return implode("AND ", $whereConditions);
    }

    private function formatWhereConditionValue(mixed $value) {
        if (!is_numeric($value)) {
            return "'$value'";
        }
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return intval($value); 
        }
        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return floatval($value);
        }
    }

    private function appendWhereParamType($value) {
        $this->whereValuesTypes .= gettype($value)[0];
    }

    protected function setupSelectColumns(): string {
        $this->setupColumnsAlias();

        $columns = $this->columns;

        if ($this->excludedColumns != []) {
            $columns = $this->filterColumns();
        }

        $columnsString = "";
        $columnsString = implode(", ", $columns);

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

        $column = $condition[0];
        $aritmetic = $condition[1];
        $value = $this->formatWhereConditionValue($condition[2]);
        $this->whereValues[] = $value;
        $this->appendWhereParamType($condition[2]);

        $whereString = "$column $aritmetic $value";

        return $whereString;
    }

    protected function setupLeftJoin(string $joinTableName, array $condition): string {
        $index = $this->currentTableIndex;
        
        $leftJoinString = "";

        $leftJoinString  = "LEFT JOIN $joinTableName t$index ";
        $leftJoinString .= "ON t.{$condition[1]} {$condition[0]} t$index.{$condition[1]} ";

        return $leftJoinString;
    }

    protected function setupJoinColumns(array $columns): string {
        $index = $this->currentTableIndex;
        
        $columnArr = [];
        foreach ($columns as $column) {
            $columnName = $column[0];
            $alias = $column[1];

            if ($alias != "" && $alias != null) {
                $columnArr[] = "t$index.$columnName as $alias ";
                continue;
            }

            $columnArr[] = "t$index.$columnName ";
        }
        
        return implode(", ", $columnArr);
    }
}

?>