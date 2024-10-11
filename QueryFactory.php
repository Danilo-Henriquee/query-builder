<?php 
class QueryFactory {
    private $db;
    private $usuarioLogado;
    private $lojaLogado;

    private string $tableName;
    private array $properties;

    private array $expectedQueryStrings = [];

    public function __construct($db, $usuarioLogado, $lojaLogado) {
        $this->db = $db;
        $this->usuarioLogado = $usuarioLogado;
        $this->lojaLogado = $lojaLogado;
    }

    public function initialize(string $className) {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class $className does not exist.");
        }

        $reflection = new ReflectionClass($className);

        /** @disregard */
        $this->properties = get_class_vars($className);
        /** @disregard */
        $tableName = $reflection->getConstant("tableName");

        if ($tableName == "") {
            throw new InvalidArgumentException("Class $className does not have defined tableName constant");
        }

        $this->tableName = $tableName;

        return $this;
    }

    public function appendExpectedWhereParams(Params $params) {
        foreach ($params->getParams() as $param) {
            $mapKey = $param->getQueryStringUrlName();
            
            $this->expectedQueryStrings[$mapKey] = $param;
        }
        
        return $this;
    }

    public function bindQueryStringsToWhereConditions(array &$params): array {
        $conditions = [];
        foreach ($params as $queryString => $queryStringValue) {
            $param = $this->expectedQueryStrings[$queryString];
            
            if ($param) {
                $conditions[] = Condition::builder()
                    ->columnName($param->getDbColumnName())
                    ->comparisonOperator($param->getOperator())
                    ->value($queryStringValue)->prepareValueToQuery()
                    ->build();
            }
        }
        return $conditions;
    }

    public function newSelectQuery() {
        $selectQuery =  new Select([
            "tableName" => $this->tableName,
            "columns"   => $this->properties,
        ], $this->db, $this->usuarioLogado, $this->lojaLogado);

        return $selectQuery;
    }

    public function insert() {
        
    }

    public function update() {

    }

    public function delete() {

    }
}

?>