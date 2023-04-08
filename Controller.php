<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

/**
 * Class Controller
 * 
 * Base controller class handling all common controller logic.
 * 
 * @package app\core
 */
class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /**
     * @var \app\core\middlewares\BaseMiddleware[]
     */
    protected array $middlewares = [];

    /**
     * Sets the layout template to be used.
     * 
     * @param string $layout 
     * @return void 
     */
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Displays the compiled view and any parameters passed.
     * 
     * @param string $view 
     * @param array $params 
     * @return string|false 
     */
    public function render(string $view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    /**
     * @param \app\core\middlewares\BaseMiddleware $middleware 
     * @return void 
     */
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return \app\core\middlewares\BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
