<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DiffController;
use App\Controllers\HomeController;
use App\Controllers\InviteController;
use App\Controllers\RegisterController;
use App\Middleware\AuthMiddleware;
use App\Wrappers\Env;
use App\Wrappers\Plates;
use App\Wrappers\Session;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception\HttpExceptionInterface;
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

$router->get('/logout', [AuthController::class, 'logout']);

// Diff
$router->group('/diff', function (RouteGroup $route) {
    $route->get('/', [DiffController::class, 'index']);
    $route->post('/', [DiffController::class, 'post']);
    $route->post('/apply', [DiffController::class, 'apply']);
});

// Register
$router->group('/register', function (RouteGroup $route) {
    $route->get('/', [RegisterController::class, 'index']);
    $route->post('/', [RegisterController::class, 'post']);
});

$router->group('/invites', function (RouteGroup $route) {
    $route->get('/', [InviteController::class, 'index']);
});

$response = null;

try {
    $response = $router->dispatch($request);
} catch (HttpExceptionInterface $e) {
    $response = new HtmlResponse(Plates::renderError($e->getMessage()), $e->getStatusCode());
} catch (\Throwable $e) {
    $response = new HtmlResponse(Plates::renderError($e->getMessage()), 500);
}

// send the response to the browser
(new SapiEmitter)->emit($response);
