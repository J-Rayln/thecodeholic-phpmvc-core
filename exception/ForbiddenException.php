<?php

namespace app\core\exception;

use Exception;

/**
 * Class ForbiddenException.
 * 
 * Handles exception errors when a page or content is restricted.
 * 
 * @package app\core\exception
 */
class ForbiddenException extends Exception
{
    protected $message = 'You don\'t have permission to access this page';
    protected $code = 403;
}
