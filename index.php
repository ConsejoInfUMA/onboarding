<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;
use App\Wrappers\Env;
use App\Wrappers\Session;

require __DIR__ . '/vendor/autoload.php';

// Parse .env file
Env::parse(__DIR__ . '/.env');

// Start session
Session::start();

$router = new \Bramus\Router\Router();

$router->get('/', [HomeController::class, 'index']);
$router->post('/', [HomeController::class, 'post']);

$router->mount('/register', function() use ($router) {
    $router->get('/', [RegisterController::class, 'index']);
    $router->post('/', [RegisterController::class, 'post']);
});

$router->mount('/login', function () use ($router) {
    $router->get('/', [AuthController::class, 'index']);
    $router->post('/', [AuthController::class, 'post']);
});

$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [AuthController::class, 'dashboard']);

$router->run();
