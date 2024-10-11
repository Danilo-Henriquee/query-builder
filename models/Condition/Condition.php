<?php 

class Condition {
    private string $columnName;
    private string $comparisonOperator;
    private mixed $value;

    private string $valueTypeForPreparedStatements;

    public function __construct($columnName, $comparisonOperator, $value, $valueTypeForPreparedStatements) {
        $this->columnName = $columnName;            
        $this->comparisonOperator = $comparisonOperator;        
        $this->value = $value;
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function getComparisonOperator() {
        return $this->comparisonOperator;
    }

    public function getValue() {
        return $this->value;
    }

    public function getValueTypeForPreparedStatements() {
        return $this->valueTypeForPreparedStatements;
    }

    public static function builder() {
        return new ConditionBuilder();
    }
}

?>