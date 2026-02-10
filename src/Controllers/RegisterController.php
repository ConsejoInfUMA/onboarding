<?php

namespace App\Controllers;

use App\Wrappers\Db;
use App\Wrappers\Ldap;
use App\Wrappers\Plates;

class RegisterController extends Controller
{
    /**
     * User register page.
     *
     * Requires a token sent by the user via query.
     */
    public static function index(): void
    {
        $token = $_GET['token'] ?? null;
        if ($token === null) {
            self::_renderError(400, 'No token sent');
            return;
        }

        $db = new Db;

        $user = $db->getInviteByToken($token);
        if ($user === null) {
            self::_renderError(400, 'Invalid token');
            return;
        }

        echo Plates::render('views/register', [
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * User register form sent.
     *
     * Requires token, password + confirm and username sent via POST body.
     */
    public static function post(): void
    {
        $token = $_POST['token'] ?? null;
        $password = $_POST['password'] ?? null;
        $password_confirm = $_POST['password_confirm'] ?? null;
        $username = $_POST['username'] ?? null;

        if ($token === null || $password === null || $password_confirm === null || $username === null) {
            self::_renderError(400, 'Invalid body');
            return;
        }

        if ($password !== $password_confirm) {
            self::_renderError(400, 'Passwords do not match');
            return;
        }

        $db = new Db;
        $ldap = new Ldap;

        $user = $db->getInviteByToken($token);
        if ($user === null) {
            self::_renderError(400, 'Invalid token');
            return;
        }

        $user->username = trim($username);

        $ok = $ldap->addUser($user, $password);
        if (!$ok) {
            self::_renderError(500, 'There was an error creating the user');
            return;
        }

        $db->removeInviteByEmail($user->email);

        echo Plates::render('views/registerOk');
    }
}
