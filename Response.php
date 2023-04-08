<?php

namespace app\core;

/**
 * Class Response
 * 
 * @package app\core
 */
class Response
{
    /**
     * Sets the response ode for http requests.
     * 
     * @param int $code 
     * @return void 
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect(string $url)
    {
        header('Location: ' . $url);
    }
}
