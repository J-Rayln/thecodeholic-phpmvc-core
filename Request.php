<?php

namespace app\core;

/**
 * Class Request
 * 
 * @package app\core
 */
class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return $path = substr($path, 0, $position);
    }

    /**
     * Returns the server request method converted to a lowercase string.
     * 
     * @return string 
     */
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Returns true|false if the request method is equal to GET.
     * 
     * @return bool 
     */
    public function isGet()
    {
        return $this->method() === 'get';
    }

    /**
     * Returns true|false if the request method is equal to POST.
     * 
     * @return bool 
     */
    public function isPost()
    {
        return $this->method() === 'post';
    }

    /**
     * Takes input from a form submission and sanitizes the data.
     * 
     * @return void 
     */
    public function getBody()
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        $body = [];
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
