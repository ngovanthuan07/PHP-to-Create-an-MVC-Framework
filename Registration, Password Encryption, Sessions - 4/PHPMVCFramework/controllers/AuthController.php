<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;

class AuthController extends Controller
{
    public function login(){
        $this->setLayout('auth');

        return $this->render('login');
    }



    public function register(Request  $request){
        $errors = [];
        $user = new User();
        if($request->isPost()){
            $user->loadData($request->getBody());
            if($user->validate() && $user->register()){
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
            }
            return $this->render('register', [
                'model' => $user
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }
}