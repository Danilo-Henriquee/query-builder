<?php 

class ColumnsBuilder {
    private array $columns = [];
    private array $aliases = [];

    private int $pointer = 0;

    public function column($columnName) {
        $this->columns[] = $columnName;
        $this->aliases[] = "";

        $this->pointer++;
        
        return $this;
    }

    public function as($alias) {
        if ($this->pointer === 0) {
            throw new InvalidArgumentException("No column name to bind alias.");
        }
        // Always will reference previous inserted column
        $this->aliases[$this->pointer - 1] = $alias;
        
        return $this;
    }

    public function build() {
        $columns = [];
        
        for ($i = 0; $i < sizeof($this->columns); $i++) {
            $columnName = $this->columns[$i];
            $alias = $this->aliases[$i];
            
            $columns[] = Column::builder()
                ->columnName($columnName)
                ->alias($alias)
                ->build();
        }

        return new Columns($columns);
    }
}

?>