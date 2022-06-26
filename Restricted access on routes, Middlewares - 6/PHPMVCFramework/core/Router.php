<?php

namespace app\core;
use app\core\exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback){
        $this->routes['get'][$path] = $callback; // Array(['get'] => Array([$path] => $callback))
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }
    public function resolve()
   {
       $path = $this->request->getPath();
       $method = $this->request->method();
        // kiem tra request nguoi dung co trong routes hay ko => gia tri : false
       $callback = $this->routes[$method][$path] ?? false;

       if($callback === false){
           Application::$app ->response->setStatusCode(404);
           throw new NotFoundException();
       }
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){ // [Class::class, 'string'] || example: [new Class, 'string'] => arr[0] = new Class && arr[1] = 'string' -> name of method
            /** @var Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }
        //[[Class::class, "string" or name of "method" '], 'param'] => return method
        return call_user_func($callback,$this->request, $this->response); // run: call Class -> method of Class -> transfer param in method
    }

    public function renderView($view, $params = []){
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view,$params);
        return str_replace('{{content}}',$viewContent, $layoutContent);
    }

    public function renderContent($viewContent){
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}',$viewContent, $layoutContent);
    }


    protected function layoutContent(){
        $layout = Application::$app->layout;
        if(Application::$app->controller){
            $layout = Application::$app ->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params){
        foreach($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}