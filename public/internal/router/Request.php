<?php
class Request {
    private $query;
    private $parameters;

    public function __construct($queryParameters, $routeParameters) {
        $this->query = $queryParameters;
        $this->parameters = $routeParameters;
    }

    public function getRouteParameter($key) {
        return $this->parameters[$key];
    }

    public function getQueryParameter($key) {
        return $this->query[$key];
    }
}
?>