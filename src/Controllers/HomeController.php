<?php

namespace App\Controllers;

use App\Wrappers\Csv;
use App\Wrappers\DataHandler;
use App\Wrappers\Plates;

class HomeController extends Controller
{
    public static function index(): void
    {
        echo Plates::render('views/home');
    }

    public static function post(): void
    {
        $rawEmail = $_POST['email'] ?? null;
        if ($rawEmail === null || filter_var($rawEmail, FILTER_VALIDATE_EMAIL) === false) {
            self::_renderError(400, 'Invalid body');
            return;
        }

        $handler = new DataHandler();

        $email = trim($rawEmail);
        if ($handler->checkUser($email)) {
            self::_renderError(400, 'User already exists');
            return;
        }

        $user = Csv::findUserByEmail($email);
        if ($user === null) {
            self::_renderError(400, 'User doesn\'t exist');
            return;
        }

        $ok = $handler->inviteUser($user);
        if (!$ok) {
            self::_renderError(500, 'There was an error inviting the user');
            return;
        }

        echo Plates::render('views/invited');
    }
}
