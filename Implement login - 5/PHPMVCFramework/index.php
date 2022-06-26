<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

$dotenv = Dotenv\Dotenv :: createImmutable (__DIR__);
$dotenv -> load ();

$config = [
    'userClass' => \app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(__DIR__,$config);

$app->router->get('/',[new SiteController, 'home']);


$app->router->get('/contact', [new SiteController, 'contact']);
$app->router->post('/contact', [new SiteController,'handleContact']);

$app->router->get('/login', [new AuthController, 'login']);
$app->router->post('/login', [new AuthController,'login']);
$app->router->get('/register', [new AuthController, 'register']);
$app->router->post('/register', [new AuthController,'register']);
$app->router->get('/logout', [new AuthController,'logout']);
$app->run();