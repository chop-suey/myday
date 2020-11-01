<?php
require_once("HttpStatuscodes.php");

class Response {
    private $code;
    private $headers = array();
    private $isSent = FALSE;
    private $isRedirect = FALSE;

    private $redirectPath;
    private $body;

    function setHeader($key, $value) {
        if (preg_match("#^Location#i", $key) === 1) {
            throw new Exception("Redirection not allowed by setting the header");
        }
        $this->headers[] = "$key: $value";
    }

    function send($body) {
        if (!$this->isSent) {
            $this->body = $body;
            $this->isSent = TRUE;
        } else {
            throw $this->getAlreadySentException();
        }
    }

    function sendHttpStatus($code, $body = NULL) {
        if (!$this->isSent) {
            $this->code = $code;
            $this->body = $body === NULL
                ? HttpStatuscodes::getStatusMessage($code)
                : $body;
            $this->isSent = TRUE;
        } else {
            throw $this->getAlreadySentException();
        }
    }

    function redirect($path) {
        if (!$this->isSent) {
            $this->redirectPath = $path;
            $this->isRedirect = TRUE;
            $this->isSent = TRUE;
        } else {
            throw $this->getAlreadySentException();
        }
    }

    function getHeaders() {
        return $this->headers;
    }

    function isSent() {
        return $this->isSent;
    }

    function isRedirect() {
        return $this->isRedirect;
    }

    function getRedirectPath() {
        return $this->redirectPath;
    }

    function getBody() {
        return $this->body;
    }

    function getCode() {
        return $this->code;
    }

    private function getAlreadySentException() {
        return new Exception("Response already sent");
    }
}
?>