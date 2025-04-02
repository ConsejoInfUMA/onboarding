<?php
namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Plates;
use App\Wrappers\Session;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class AuthController {
    public static function index(ServerRequestInterface $request): Response {
        $template = Plates::render('views/login');

        $response = new Response();
        $response->getBody()->write($template);
        return $response;
    }

    public static function post(ServerRequestInterface $request): Response {
        $body = $request->getParsedBody();
        if ($body === null) {
            // TODO: Show proper error
            return new RedirectResponse(Env::app_url('/login'));
        }

        if (!password_verify($body["password"], Env::app_password())) {
            // TODO: Show proper error
            return new RedirectResponse(Env::app_url('/login'));
        }

        Session::setLoggedIn(true);

        return new RedirectResponse(Env::app_url('/'));
    }
}
