<?php
class Request {
    private $path;
    private $headers;
    private $query;
    private $parameters;
    private $body;

    /**
     * Set the request path. Subsequent calls have no effect.
     */
    public function setPath($path) {
        if (!$this->path) {
            $this->path = $path;
        }
    }

    /**
     * Set the request query Parameters. Subsequent calls have no effect.
     */
    public function setQueryParameters($query) {
        if (!$this->query) {
            $this->query = $query;
        }
    }

    /**
     * Set the request route Parameters. Subsequent calls have no effect.
     */
    public function setRouteParameters($parameters) {
        if (!$this->parameters) {
            $this->parametes = $parameters;
        }
    }

    /**
     * Set the request headers. Subsequent calls have no effect.
     */
    public function setHeaders($headers) {
        if (!$this->headers) {
            $this->headers = $headers;
        }
    }

    /**
     * Set the request body. Subsequent calls have no effect.
     */
    public function setBody($body) {
        if (!$this->body) {
            $this->body = $body;
        }
    }

    public function getPath() {
        return $this->path;
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeader($key) {
        return is_array($this->headers)
            ? $this->headers[$key]
            : NULL;
    }

    public function getRouteParameter($key) {
        return is_array($this->parameters)
            ? $this->parameters[$key]
            : NULL;
    }

    public function getQueryParameter($key) {
        return is_array($this->query)
            ? $this->query[$key]
            : NULL;
    }
}
?>