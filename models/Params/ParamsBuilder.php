<?php 

class ParamsBuilder {
    private string $queryStringUrlName;
    private string $dbColumnName;
    private string $operator;

    private array $params = [];

    public function queryStringUrlName($queryStringUrlName) {
        $this->queryStringUrlName = $queryStringUrlName;
        return $this;
    }

    public function dbColumnName($dbColumnName) {
        $this->dbColumnName = $dbColumnName;
        return $this; 
    }

    public function operator($operator) {
        $this->operator = $operator;
        return $this;
    }

    public function attach() {
        try {
            $this->internalAttach();
            return $this;
        }
        catch (InvalidArgumentException $exception) {
            exit(
                json_encode($exception->getMessage())
            );
        }
        
    }

    public function build() {
        return new Params($this->params);
    }

    private function internalAttach() {
        if ($this->queryStringUrlName === "") {
            throw new InvalidArgumentException("Query string name not be empty.");
        }
        if ($this->dbColumnName === "") {
            throw new InvalidArgumentException("Database column name not be empty.");
        }
        if ($this->operator === "") {
            throw new InvalidArgumentException("Operator not be empty.");
        }

        $this->params[] = new Param(
            $this->queryStringUrlName,
            $this->dbColumnName,
            $this->operator
        );
    }

    private function setPropsToEmpty() {
        $this->queryStringUrlName = "";
        $this->dbColumnName = "";
        $this->operator = "";
    }
}

?>