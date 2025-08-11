<?php
namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Plates;
use App\Wrappers\Session;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class AuthController {
    public static function index(ServerRequestInterface $request): Response {
        return new HtmlResponse(Plates::render('views/login'));
    }

    public static function post(ServerRequestInterface $request): Response {
        $body = $request->getParsedBody();
        if ($body === null) {
            return new HtmlResponse(Plates::renderError('No body sent'));
        }

        if (!password_verify($body["password"], Env::app_password())) {
            return new HtmlResponse(Plates::renderError('Invalid password'));
        }

        Session::setLoggedIn(true);

        return new RedirectResponse(Env::app_url('/'));
    }
}
