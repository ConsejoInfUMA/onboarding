<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DevController;
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

$basePath = Env::app_path();

function path(string $base): string {
    global $basePath;

    return $base . $basePath;
}

$router->get(path('/'), [HomeController::class, 'index']);
$router->post(path('/'), [HomeController::class, 'post']);

// Auth
$router->group(path('/login'), function (RouteGroup $route) {
    $route->get('/', [AuthController::class, 'index']);
    $route->post('/', [AuthController::class, 'post']);
});

$router->get(path('/logout'), [AuthController::class, 'logout']);

// Diff
$router->group(path('/diff'), function (RouteGroup $route) {
    $route->get('/', [DiffController::class, 'index']);
    $route->post('/', [DiffController::class, 'post']);
    $route->post('/apply', [DiffController::class, 'apply']);
});

// Register
$router->group(path(base: '/register'), function (RouteGroup $route) {
    $route->get('/', [RegisterController::class, 'index']);
    $route->post('/', [RegisterController::class, 'post']);
});

$router->group(path(base: '/invites'), function (RouteGroup $route) {
    $route->get('/', [InviteController::class, 'index']);
});

if (Env::app_debug()) {
    $router->group(path('/dev'), function (RouteGroup $route) {
        $route->get('/email', [DevController::class, 'email']);
    });
}

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
