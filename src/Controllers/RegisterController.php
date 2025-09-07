<?php

namespace App\Controllers;

use App\Wrappers\Db;
use App\Wrappers\Ldap;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class RegisterController
{
    /**
     * User register page.
     *
     * Requires a token sent by the user via query.
     */
    public static function index(ServerRequestInterface $request): Response
    {
        $query = $request->getQueryParams();
        if (!isset($query['token'])) {
            http_response_code(400);
            return new HtmlResponse(Plates::renderError('No token sent'));
        }

        $db = new Db;
        $token = $query['token'];

        $user = $db->getInviteByToken($token);

        return new HtmlResponse(Plates::render('views/register', [
            'user' => $user,
            'token' => $token,
        ]));
    }

    /**
     * User register form sent.
     *
     * Requires token, password + confirm and username sent via POST body.
     */
    public static function post(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if ($body === null) {
            http_response_code(400);
            return new HtmlResponse(Plates::renderError('No body sent'));
        }

        if (!isset($body['token'], $body['password'], $body['password_confirm'], $body['username'])) {
            http_response_code(400);
            return new HtmlResponse(Plates::renderError('Invalid body'));
        }

        if ($body['password'] !== $body['password_confirm']) {
            http_response_code(400);
            return new HtmlResponse(Plates::renderError('Passwords do not match'));
        }

        $db = new Db;
        $ldap = new Ldap;

        $user = $db->getInviteByToken($body['token']);
        if ($user === null) {
            http_response_code(400);
            return new HtmlResponse(Plates::renderError('Invalid token'));
        }

        $user->username = trim($body['username']);

        $ok = $ldap->addUser($user, $body['password']);
        if (!$ok) {
            http_response_code(500);
            return new HtmlResponse(Plates::renderError('There was an error creating the user'));
        }

        return new HtmlResponse(Plates::render('views/registerOk'));
    }
}
