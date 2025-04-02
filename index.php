<?php

require __DIR__ . '/vendor/autoload.php';

use App\Wrappers\Env;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// Parse .env file
Env::parse(__DIR__ . '/.env');

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new League\Route\Router;

$router->map('GET', '/', function (ServerRequestInterface $request): ResponseInterface {
    $response = new Laminas\Diactoros\Response;
    $response->getBody()->write('<h1>Hello, World!</h1>');
    return $response;
});

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
