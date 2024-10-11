<?php

class Param {
    private string $queryStringUrlName;
    private string $dbColumnName;
    private string $operator;

    public function __construct($queryStringUrlName, $dbColumnName, $operator) {
        $this->queryStringUrlName = $queryStringUrlName;
        $this->dbColumnName = $dbColumnName;
        $this->operator = $operator;
    }

    public function getQueryStringUrlName() {
        return $this->queryStringUrlName;
    }

    public function getDbColumnName() {
        return $this->dbColumnName;
    }
    
    public function getOperator() {
        return $this->operator;
    }

    public static function builder() {
        return new ParamBuilder();
    }
}

?>