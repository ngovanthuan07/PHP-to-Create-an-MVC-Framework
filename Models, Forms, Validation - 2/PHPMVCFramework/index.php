<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

$app = new Application(__DIR__);

$app->router->get('/',[new SiteController, 'home']);


$app->router->get('/contact', [new SiteController, 'contact']);
$app->router->post('/contact', [new SiteController,'handleContact']);

$app->router->get('/login', [new AuthController, 'login']);
$app->router->post('/login', [new AuthController,'login']);
$app->router->get('/register', [new AuthController, 'register']);
$app->router->post('/register', [new AuthController,'register']);
$app->run();