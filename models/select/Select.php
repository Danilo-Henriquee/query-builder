<?php require_once('./models/select/SelectBase.php') ?>

<?php 

class Select extends SelectBase {
    private string $selectString = "";
    private string $fromString = "";
    private string $whereString = "";

    private array $joinsStrings = [];

    public function __construct(array $configs, $db, $usuarioLogado, $lojaLogado) {
        $this->tableName = $configs["tableName"];
        $this->columns = $configs["columns"];

        $this->db = $db;
        $this->usuarioLogado = $usuarioLogado;
        $this->lojaLogado = $lojaLogado;
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

    public function where(array $conditions = []) {
        if ($conditions != []) {
            $this->whereConditions = $conditions;
            $this->whereString = "WHERE {$this->setupWhereConditions()} ";
        }
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
        $sql  = "";
        
        $sql .= $this->selectString;
        $sql .= $this->fromString;

        if ($this->joinsStrings != []) {
            foreach ($this->joinsStrings as $join) {
                $sql .= $join;
            }
        }

        $sql .= $this->whereString;
        $conn = $this->db->getConnection();

        $items = [];
        if ($this->whereValuesTypes != "" && $this->whereValues != []) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($this->whereValuesTypes, ...$this->whereValues);
            $stmt->execute();
    
            $result = $stmt->get_result();

            while ($row =  $result->fetch_assoc()) {
                $items[] = $row;
            }

            return $items;
        }

        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }
}

?> 