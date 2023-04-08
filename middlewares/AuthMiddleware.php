<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

/**
 * Class AuthMiddlewares
 * 
 * @package app\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * AuthMiddleWares constructor.
     * 
     * @param array $actions 
     * @return void 
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * Applies middlewares.
     * 
     * @return void 
     * @throws \app\core\exception\ForbiddenException 
     */
    public function execute()
    {
        // Restrict access to non-logged-in users.
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
