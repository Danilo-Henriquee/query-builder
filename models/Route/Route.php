<?php 

class Route {
    private string $method;
    private string $route;
    private array $queryStrings;

    public  function __construct($method, $route, $queryStrings) {
        $this->method = $method;
        $this->route = $route;
        $this->queryStrings = $queryStrings;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getQueryStrings() {
        return $this->queryStrings;
    }

    public static function builder() {
        return new RouteBuilder();
    }
}
 
?>