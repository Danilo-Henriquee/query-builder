<?php 

class Router {
    private array $routes = [];

    private string $method = "";
    private string $route = ""; 

    private string $requestMethod = "";
    private string $requestRoute = "";

    private array $requestParams = [];
    private array $requestBody = [];

    private QueryFactory $queryFactory;

    public function setQueryFactory(QueryFactory $queryFactory) {
        $this->queryFactory = $queryFactory;
    }

    public function setAuthenticator(callable $authenticator) {
        $isAuthenticated = $authenticator();

        if (!is_bool($isAuthenticated)) {
            throw new InvalidArgumentException("Authenticator callback must return a boolean value.");
        }
    }

    public function router(string $method, string $route, &$params, $body) {
        if ($this->routes != []) {
            $this->requestMethod = $method;
            $this->requestRoute = $route;

            $this->requestParams = $params;
            
            if ($method == "POST") {
                $this->requestBody = $body;
            }

            if (!$this->checkRouteExistenceAndExecute()) {
                throw new InvalidArgumentException("Method not allowed or route is not registered.");
            }
        }
    }

    public function get(string $route) {
        $this->method = "GET";
        $this->route = $route;
        return $this;
    }

    public function post(string $route) {
        $this->method = "POST";
        $this->route = $route;
        return $this;
    }

    public function handler(callable $endpointHandler) {
        $this->routes[] = [
            "method"  => $this->method,
            "route"   => $this->route,
            "handler" => $endpointHandler
        ];

        $this->method = "";
        $this->route = "";
    }

    private function checkRouteExistenceAndExecute(): bool {
        foreach ($this->routes as $route) {
            if (
                $route["method"] == $this->requestMethod 
                    &&
                $route["route"] == $this->requestRoute
            ) {
                $this->executeRouteHandler($route["handler"]);
                return true;
            }
        }
        return false;
    }

    private function executeRouteHandler(callable $callback) {
        if ($this->method === "POST") {
            $callback($this->queryFactory, $this->requestParams, $this->requestBody);
            return;
        }
        $callback($this->queryFactory, $this->requestParams);
    }
}

?>