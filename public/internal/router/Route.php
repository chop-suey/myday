<?php
require_once('Request.php');
require_once('Response.php');

class Route {
    private $method;
    private $pattern;
    private $handler;
    private $parameters = array();

    public function __construct($method, $pattern, $handler) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    
    public function getMethod() {
        return $this->method;
    } 
    
    public function match($path) {
        $match = preg_match("#^$this->pattern$#", $path, $this->parameters) === 1;
        if ($match) {
            array_shift($this->parameters);
        }
        return $match;
    }

    public function handle($request, $response) {
        $request->setRouteParameters($this->parameters);
        $this->getHandler()($request, $response);
        return $response;
    }

    private function getHandler() {
        return $this->handler;
    }
}
?>