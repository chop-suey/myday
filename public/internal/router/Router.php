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
                : $this->handleRoute($path, $this->routes[$index]);
            $this->sendResponse($response);
        } else {
            $this->sendResponse($this->getErrorResponse(404));
        }
    }

    private function addRoute($method, $pattern, $handler) {
        array_push($this->routes, new Route($method, $pattern, $handler));
    }

    private function handleRoute($path, $route) {
        try {
            $request = new Request();
            $request->setPath($path);
            $request->setQueryParameters($this->getQueryParameters());
            $response = new Response();
            $this->runMiddlewares($request, $response);
            return $response->isSent()
                ? $response
                : $route->handle($request, $response);
        } catch (Exception $e) {
            error_log($e);
            return $this->getErrorResponse(500);
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
        $response = $this->getErrorResponse(405);
        $response->setHeader("Allow", implode(", ", $methods));
        return $response;
    }

    private function getErrorResponse($code, $message = NULL) {
        $response = new Response();
        $response->sendHttpStatus($code, $message);
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

    private function runMiddlewares($request, $response) {
        foreach($this->middlewares as $middleware) {
            $middleware($request, $response);
            if ($response->isSent()) {
                break;
            }
        }
    }

    private function sendResponse($response) {
        $this->checkAndSendRedirect($response);
        http_response_code($response->getCode());
        foreach($response->getHeaders() as $header) {
            header($header);
        }
        echo $response->getBody() ? $response->getBody() : "";
    }

    private function checkAndSendRedirect($response) {
        if ($response->isRedirect()) {
            $redirectPath = $response->getRedirectPath();
            header("Location: $redirectPath", TRUE, 303);
            exit();
        }
    }
}
?>