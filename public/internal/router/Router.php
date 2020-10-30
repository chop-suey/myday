<?php
require_once("Route.php");
require_once("Request.php");
require_once("Response.php");

class Router {
    private $middlewares = array();
    private $routes = array();

    public function use($function) {
        array_push($this->middlewares, $function);
    }

    public function get($pattern, $handler) {
        $this->addRoute("GET", $pattern, $handler);
    }

    // TODO POST, PUT, DELETE
    // get the body / headers for the request

    public function run() {
        $path = $this->getRequestPath();
        $method = $this->getRequestMethod();

        $matchingRoutes = $this->getMatchingRoutes($path);

        if (count($matchingRoutes) > 0) {
            $allowedMethods = $this->getAllowedMethods($matchingRoutes);
            $index = array_search($method, $allowedMethods);
            $response = $index === FALSE
                ? $this->getNotAllowedMethodResponse($allowedMethods)
                : $this->handleRoute($this->routes[$index]);
            $this->sendResponse($response);
        } else {
            $this->sendResponse($this->getErrorResponse(404, "Not found"));
        }
    }

    private function addRoute($method, $pattern, $handler) {
        array_push($this->routes, new Route($method, $pattern, $handler));
    }

    private function handleRoute($route) {
        try {
            $queryParameters = $this->getQueryParameters();
            return $route->handle($queryParameters);
        } catch (Exception $e) {
            return $this->getErrorResponse(500, $e->getMessage());
        } 
    }

    private function getMatchingRoutes($path) {
        return array_filter($this->routes, function($route) use (&$path) {
            return $route->match($path);
        });
    }

    private function getAllowedMethods($routes) {
        return array_map(function($route) {
            return $route->getMethod();
        }, $routes);
    }

    private function getNotAllowedMethodResponse($allowedMethods) {
        $methods = array_unique($allowedMethods);
        $response = $this->getErrorResponse(405, "Method not allowed");
        $response->setHeader("Allow", implode(", ", $methods));
        return $response;
    }

    private function getErrorResponse($code, $message) {
        $response = new Response();
        $response->setCode($code);
        $response->send($message);
        return $response;
    }

    private function getRequestPath() {
        $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
        return $parsedUrl["path"];
    }

    private function getQueryParameters() {
        $query = array();
        parse_str($_SERVER["QUERY_STRING"], $query);
        return $query;
    }

    private function getRequestMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    // private function runMiddlewares() {
    //     foreach($this->middlewares as $middleware) {
    //         if (!$middleware($path, )) {
                
    //         }
    //     }
    // }

    private function sendResponse($response) {
        http_response_code($response->getCode());
        foreach($response->getHeaders() as $header) {
            header($header);
        }
        echo $response->getBody() ? $response->getBody() : "";
    }
}
?>