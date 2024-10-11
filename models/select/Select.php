<?php require_once __DIR__ . '/SelectBase.php' ?>

<?php 

class Select extends SelectBase {
    private string $selectString = "";
    private string $fromString = "";
    private string $whereString = "";

    private array $joinsStrings = [];

    private mysqli_result $queryResult;

    public function __construct(array $configs, $db, $usuarioLogado, $lojaLogado) {
        $this->tableName = $configs["tableName"];
        $this->columns = $configs["columns"];

        $this->definePropsFromRawArrayReflection();

        $this->db = $db;
        $this->usuarioLogado = $usuarioLogado;
        $this->lojaLogado = $lojaLogado;
    }

    public function select(?Columns $onlyColumns = null, ?Columns $excludedColumns = null) {
        $this->onlyColumns = $onlyColumns;
        $this->excludedColumns = $excludedColumns;

        $this->selectString = "SELECT {$this->setupSelectColumns()} ";
        return $this;
    }

    public function from(string $tableName = "") {
        if ($tableName != "") $this->tableName = $tableName;
        $this->fromString = "FROM {$this->tableName} t ";
        return $this;
    }

    public function where(array $conditions = []) {
        if ($conditions != []) {
            $this->whereConditions = $conditions;
            $this->validadeArgumentConditions();
            $this->whereString = "WHERE {$this->setupWhereConditions()} ";
        }
        return $this;
    }

    public function leftJoin(LeftJoin $leftJoin) {
        $this->joinsAliases[$leftJoin->getTableName()] = "t$this->currentTableIndex";
        $this->joinsStrings[] = $this->setupLeftJoin($leftJoin);
        
        $columnsToJoin = $leftJoin->getColumnsToJoin();

        if ($columnsToJoin != []) {
            $this->selectString .= ", {$this->setupJoinColumns($columnsToJoin)}";
        }

        $this->currentTableIndex++;

        return $this; 
    }

    public function execute() {
        $sql  = "";
        
        $sql .= $this->selectString;
        $sql .= $this->fromString;

        if ($this->joinsStrings != []) {
            foreach ($this->joinsStrings as $join) {
                $sql .= $join;
            }
        }

        $sql .= $this->whereString;
        exit($sql);
        $conn = $this->db->getConnection();

        if ($this->isPreparedStatements()) {
            $statement = $conn->prepare($sql);
            $this->prepareStatements($statement);

            return $this->getDataFromResult();
        }

        $this->queryResult = $conn->query($sql);
        return $this->getDataFromResult();
    }

    private function validadeArgumentConditions() {
        foreach ($this->whereConditions as $condition) {
            if (!$condition instanceof Condition) {
                throw new InvalidArgumentException("Condition must be type of Condition");
            }
        }
    }

    private function isPreparedStatements(): bool {
        return $this->whereValuesTypes != "" && $this->whereValues != [];
    }

    private function prepareStatements($statement) {
        $statement->bind_param($this->whereValuesTypes, ...$this->whereValues);
        $statement->execute();

        $this->queryResult = $statement->get_result();
    }

    private function getDataFromResult(): array {
        $items = [];
        while ($row = $this->queryResult->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }
}

?> 