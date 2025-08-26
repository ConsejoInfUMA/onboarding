<?php

namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Ldap;
use App\Wrappers\Plates;
use App\Wrappers\Session;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Admin auth controller
 */
class AuthController
{
    /**
     * Login page.
     */
    public static function index(ServerRequestInterface $request): Response
    {
        return new HtmlResponse(Plates::render('views/login'));
    }

    /**
     * Login form sent.
     */
    public static function post(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if ($body === null) {
            return new HtmlResponse(Plates::renderError('No body sent'));
        }

        try {
            new Ldap($body['password']);
            Session::setLoggedIn(true);

            return new RedirectResponse(Env::app_url('/'));
        } catch (\Exception $e) {
            return new HtmlResponse(Plates::render('views/login', [
                'error' => 'Invalid password',
            ]));
        }
    }

    /**
     * Destroy session.
     */
    public static function logout(ServerRequestInterface $request): Response
    {
        Session::destroy();
        return new RedirectResponse(Env::app_url('/login'));
    }
}
