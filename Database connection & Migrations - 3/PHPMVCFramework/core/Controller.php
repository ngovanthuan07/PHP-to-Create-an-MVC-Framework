<?php

namespace app\core;

class Controller
{
    public string $layout = 'main';
    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function render($view, $param = []){
        return Application::$app->router->renderView($view,$param);
    }


}