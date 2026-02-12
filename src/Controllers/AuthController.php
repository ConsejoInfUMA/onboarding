<?php

namespace App\Controllers;

use App\Wrappers\Db;
use App\Wrappers\Ldap;
use App\Wrappers\Plates;
use App\Wrappers\Session;

/**
 * Admin auth controller
 */
class AuthController extends Controller
{
    /**
     * Login page.
     */
    public static function index(): void
    {
        echo Plates::render('views/login');
    }

    /**
     * Login form sent.
     */
    public static function post(): void
    {
        $password = $_POST['password'] ?? null;
        if ($password === null) {
            self::_renderError(400, 'Invalid body');
            return;
        }

        try {
            new Ldap($password);
            Session::setLoggedIn(true);
            self::_redirect('/dashboard');
        } catch (\Exception $e) {
            self::_renderError(401, 'Could not login');
        }
    }

    public static function dashboard(): void
    {
        if (!Session::isLoggedIn()) {
            self::_redirect('/login');
            return;
        }

        $db = new Db();
        $users = $db->getInvites();

        echo Plates::render('views/invites', [
            'users' => $users,
        ]);
    }

    /**
     * Destroy session.
     */
    public static function logout(): void
    {
        Session::destroy();
        self::_redirect('/login');
    }
}
