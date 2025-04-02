<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DiffController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Wrappers\Env;
use App\Wrappers\Session;
use League\Route\RouteGroup;

// Parse .env file
Env::parse(__DIR__ . '/.env');

// Start session
Session::start();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new League\Route\Router;
$router->middleware(new AuthMiddleware);

$router->get('/', [HomeController::class, 'index']);
$router->post('/', [HomeController::class, 'post']);

// Auth
$router->group('/login', function (RouteGroup $route) {
    $route->get('/', [AuthController::class, 'index']);
    $route->post('/', [AuthController::class, 'post']);
});

// Diff
$router->group('/diff', function (RouteGroup $route) {
    $route->get('/', [DiffController::class, 'index']);
    $route->post('/', [AuthController::class, 'post']);
});

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
