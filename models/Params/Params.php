<?php 

class Params {
    private array $params;

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public static function builder() {
        return new ParamsBuilder();
    }
}

?>