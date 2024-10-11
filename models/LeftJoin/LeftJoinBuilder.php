<?php 

class LeftJoinBuilder {
    private string $tableName;
    private string $columnName;
    private string $comparisonOperator;
    private Columns $columnsToJoin;
    
    private string $overridedTableName = "";
    private string $overridedColumnName = "";

    public function tableName(string $tableName) {
        $this->tableName = $tableName;
        return $this;
    }    

    public function columnName(string $columnName) {
        $this->columnName = $columnName;
        return $this;
    }

    public function comparisonOperator($comparisonOperator) {
        $this->comparisonOperator = $comparisonOperator;
        return $this;
    }

    public function columnsToJoin(Columns $columnsToJoin) {
        $this->columnsToJoin = $columnsToJoin;
        return $this;
    }

    public function overrideTopTableAndCompareTo(string $overrideWithTableName) {
        $this->overridedTableName = $overrideWithTableName; 
        return $this;
    }

    public function usingColumn(string $name) {
        $this->overridedColumnName = $name;
        return $this;
    }

    public function build(): LeftJoin {
        return new LeftJoin(
            $this->tableName,
            $this->columnName,
            $this->overridedTableName,
            $this->overridedColumnName,
            $this->comparisonOperator,
            $this->columnsToJoin
        );
    }
}

?>