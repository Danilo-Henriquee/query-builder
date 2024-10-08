<?php 

class Router {
    private array $routes = [];

    private string $method = "";
    private string $route = ""; 

    private string $requestMethod = "";
    private string $requestRoute = "";
    private array $requestParams = [];

    private QueryFactory $queryFactory;

    private function checkRouteExistence(): bool {
        foreach ($this->routes as $route) {
            if (
                $route["method"] == $this->requestMethod 
                    &&
                $route["route"] == $this->requestRoute
            ) {
                $route["handler"]($this->queryFactory, $this->requestParams);
                return true;
            }
        }
        return false;
    }

    public function setQueryFactory(QueryFactory $queryFactory) {
        $this->queryFactory = $queryFactory;
    }

    public function setAuthenticator(callable $authenticator) {
        $isAuthenticated = $authenticator();

        if (!is_bool($isAuthenticated)) {
            throw new InvalidArgumentException("Authenticator callback must return a boolean value.");
        }
    }

    public function router(string $method, string $route, array &$params) {
        if ($this->routes != []) {
            $this->requestMethod = $method;
            $this->requestRoute = $route;
            $this->requestParams = $params;

            if (!$this->checkRouteExistence()) {
                throw new InvalidArgumentException("Method not allowed or route is not registered.");
            }
        }
    }

    public function get(string $route) {
        $this->method = "GET";
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
}

?>