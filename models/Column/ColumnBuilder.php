<?php 

class ColumnBuilder {
    private string $columnName;
    private string $alias = "";

    public function columnName(string $columnName) {
        $this->columnName = $columnName;
        return $this;
    }

    public function alias(string $alias = "") {
        $this->alias = $alias;
        return $this;
    }

    public function build() {
        return new Column($this->columnName, $this->alias);
    }
}

?>