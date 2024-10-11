<?php 

class Column {
    private string $columnName = "";
    private string $alias = "";

    public function __construct(string $columnName, string $alias = "") {
        $this->columnName = $columnName;
        $this->alias = $alias;
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function getAlias() {
        return $this->alias;
    }

    public static function builder() {
        return new ColumnBuilder();
    }
}

?>