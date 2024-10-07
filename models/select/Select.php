<?php require_once('./models/select/SelectBase.php') ?>

<?php 

class Select extends SelectBase {
    private string $selectString = "";
    private string $fromString = "";
    private string $whereString = "";

    private array $joinsStrings = [];

    public function __construct(array $configs) {
        $this->tableName = $configs["tableName"];
        $this->columns = $configs["columns"];
    }

    public function select(array $excludedColumns = []) {
        $this->excludedColumns = $excludedColumns;
        $this->selectString = "SELECT {$this->setupSelectColumns()} ";
        return $this;
    }

    public function from(string $tableName = "") {
        if ($tableName != "") $this->tableName = $tableName;
        $this->fromString = "FROM {$this->tableName} t ";
        return $this;
    }

    public function where(array $conditions) {
        $this->whereConditions = $conditions;
        $this->whereString = "WHERE {$this->setupWhereConditions()} ";
        return $this;
    }

    public function leftJoin(string $joinTableName, array $condition, array $columnsToJoin = []) {
        $this->joinsStrings[] = $this->setupLeftJoin($joinTableName, $condition);

        if ($columnsToJoin != []) {
            $this->selectString .= ", {$this->setupJoinColumns($columnsToJoin)}";
        }

        $this->currentTableIndex++;

        return $this;
    }

    public function execute() {
        $query  = "";
        
        $query .= $this->selectString;
        $query .= $this->fromString;

        if ($this->joinsStrings != []) {
            foreach ($this->joinsStrings as $join) {
                $query .= $join;
            }
        }

        $query .= $this->whereString;

        exit(json_encode($query));
    }
}

?> 