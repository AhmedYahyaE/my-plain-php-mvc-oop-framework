<?php
namespace app\core;


class Response {
    public function setHTTPResponseStatusCode(int $HTTPResponseCodeNumber) {
        http_response_code($HTTPResponseCodeNumber);
    }

    public function redirectTo(string $URLToBeRedirectedTo) {
        return header('Location: ' . $URLToBeRedirectedTo);
    }

}