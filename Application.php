<?php

namespace app\core;

use app\core\Controller;
use app\core\db\Database;
use app\core\db\DbModel;

/**
 * Class Application
 * 
 * @package app\core
 */
class Application
{
    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?UserModel $user;
    public View $view;
    public static Application $app;
    public ?Controller $controller = null;

    /**
     * Application constructor
     * 
     * @param string $rootPath Root path of the application relative to the public/index.php file.
     * @param array $config Array of config parameters.
     * @return void 
     */
    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    /**
     * Determines if a user is logged in.
     * 
     * @return bool 
     */
    public static function isGuest()
    {
        return !self::$app->user;
    }

    /**
     * Shortcut method to output the appropriate view file for the given
     * request as determined by the Router. 
     * 
     * @return void 
     */
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    /**
     * @return \app\core\Controller 
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param \app\core\Controller $controller 
     * @return void 
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Sets the user primary key in session once a user is logged in.
     * 
     * @param \app\core\UserModel $user 
     * @return true 
     */
    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    /**
     * Logs a user out.
     * 
     * @return void 
     */
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}
