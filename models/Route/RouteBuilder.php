<?php 

class RouteBuilder {
    private string $method;
    private string $route;
    private array $queryStrings;
    private array $body;

    public function method($method) {
        $this->method = $method;
        return $this;
    }

    public function route($route) {
        $this->route = $route;
        return $this;
    }

    public function queryStrings($queryStrings) {
        $this->queryStrings = $queryStrings;
        return $this;
    }

    public function body($body) {
        if ($this->method !== "POST") {
            throw new InvalidArgumentException("Only POST method can handle body request.");
        }

        $this->body = $body;
    }

    public function build() {
        return new Route(
            $this->method,
            $this->route,
            $this->queryStrings
        );
    }
}

?>