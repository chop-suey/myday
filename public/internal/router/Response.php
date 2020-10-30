<?php
class Response {
    private $code;
    private $headers = array();
    private $isSent = FALSE;

    private $body;

    function setHeader($key, $value) {
        $this->headers[] = "$key: $value";
    }

    function send($body) {
        if (!$this->isSent) {
            $this->body = $body;
            $this->isSent = TRUE;
        } else {
            throw new Exception("Body is already sent");
        }
    }

    function getHeaders() {
        return $this->headers;
    }

    function isSent() {
        return $this->isSent;
    }

    function getBody() {
        return $this->body;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function getCode() {
        return $this->code;
    }
}
?>