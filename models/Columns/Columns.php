<?php 

class Columns {
    private array $columns = [];

    public function __construct(array $columns) {
        $this->columns = $columns;
    }

    public function getColumns() {
        return $this->columns;
    }

    public static function builder() {
        return new ColumnsBuilder();
    }
}

?>