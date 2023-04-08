<?php

namespace app\core\exception;

use Exception;

/**
 * Class NotFoundException.
 * 
 * Handles 404 errors when a page cannot be found.
 * 
 * @package app\core\exception
 */
class NotFoundException extends Exception
{
    protected $message = 'Page Not Found';
    protected $code = 404;
}
