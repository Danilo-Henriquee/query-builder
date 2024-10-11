<?php 

class ConditionBuilder {
    private string $columnName;
    private string $comparisonOperator;
    private mixed $value;

    private string $valueTypeForPreparedStatements;

    public function columnName(string $columnName) {
        $this->columnName = $columnName;
        return $this;
    }

    public function comparisonOperator(string $comparisonOperator) {
        $this->comparisonOperator = $comparisonOperator;
        return $this;
    }

    public function value(mixed $value) {
        $this->value = $value;
        $this->setValueTypeForPreparedStatements();

        return $this;
    }

    public function prepareValueToQuery() {
        if (!is_numeric($this->value)) {
            $this->value = "'$this->value'";
        }
        if (filter_var($this->value, FILTER_VALIDATE_FLOAT) !== false) {
            $this->value = floatval($this->value);
        }
        if (filter_var($this->value, FILTER_VALIDATE_INT) !== false) {
            $this->value = intval($this->value);
        }
 
        return $this;
    }

    public function build() {
        return new Condition(
            $this->columnName,
            $this->comparisonOperator,
            $this->value,
            $this->valueTypeForPreparedStatements
        );
    }

    private function setValueTypeForPreparedStatements() {
        $type = gettype($this->value);
        $this->valueTypeForPreparedStatements = $type[0];
        
        return $this;
    }
}

?>