<?php 

class ParamBuilder {
    private string $queryStringUrlName;
    private string $dbColumnName;
    private string $operator;

    public function queryStringUrlName(string $name) {
        $this->queryStringUrlName = $name;
        return $this;
    }

    public function dbColumnName(string $columnName) {
        $this->dbColumnName = $columnName;
        return $this;
    }
    
    public function operator(string $operator) {
        $this->operator = $operator;
        return $this;
    }

    public function build() {
        return new Param(
            $this->queryStringUrlName,
            $this->dbColumnName,
            $this->operator
        );
    }
}

?>