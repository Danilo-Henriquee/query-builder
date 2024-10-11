<?php 

class LeftJoin {
    private string $tableName;
    private string $columnName;
    private string $comparisonOperator;
    private Columns $columnsToJoin;
    
    private string $overridedTableName = "";
    private string $overridedColumnName = "";

    public function __construct(string $tableName, string $columnName, string $overridedTableName, string $overridedColumnName, string $comparisonOperator, Columns $columnsToJoin) {        
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->overridedTableName = $overridedTableName;
        $this->overridedColumnName = $overridedColumnName;
        $this->comparisonOperator = $comparisonOperator;
        $this->columnsToJoin = $columnsToJoin;
    }

    public function getTableName() {
        return $this->tableName;
    }    

    public function getColumnName() {
        return $this->columnName;
    }

    public function getOverridedTableName() {
        return $this->overridedTableName;
    }

    public function getCondition() {
        return $this->comparisonOperator;
    }

    public function getColumnsToJoin() {
        return $this->columnsToJoin;
    }

    public function getOverridedColumnName() {
        return $this->overridedColumnName;
    }

    public static function builder(): LeftJoinBuilder {
        return new LeftJoinBuilder();
    }
}

?>