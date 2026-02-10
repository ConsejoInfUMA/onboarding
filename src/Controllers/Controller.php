<?php

namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Plates;

abstract class Controller
{
    protected static function _renderError(int $code, string $message): void
    {
        http_response_code($code);
        echo Plates::renderError($message);
    }

    protected static function _redirect(string $path): void
    {
        header('Location: '. Env::app_url($path));
    }
}
