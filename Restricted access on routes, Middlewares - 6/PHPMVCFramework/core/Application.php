<?php

namespace app\core;
class Application
{
    public string $layout = 'main';

    public static string $ROOT_DIR;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public static Application $app;

    public ?Controller $controller = null;
    public Session $session;
    public ?DbModel $user; // ? vo hieu hoa nguoi dung hien tai

    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);

        $primaryKeyValue = $this->session->get('user');
        if($primaryKeyValue){
            $primaryKey = (new $this->userClass) -> primaryKey();
            $this->user = (new $this->userClass) -> findOne([$primaryKeyValue => $primaryKeyValue]);
        } else{
            $this->user = null;
        }

    }

    public function run()
    {
        try{
            echo $this->router->resolve();
        }catch(\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView('error/_error', [
                    'exception' => $e
            ]);
        }
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryKeyValue = $user->{$primaryKey};
        $this->session->set('user', $primaryKeyValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return  self::$app->user ?? false;
    }
}


