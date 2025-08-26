<?php

namespace App\Wrappers;

use League\Plates\Engine;

class Plates
{
    public static function render(string $template, array $data = []): string
    {
        $engine = new Engine(__DIR__ . '/../../templates');

        $engine->registerFunction('url', fn(string $path, ?array $query = null) => Env::app_url($path, $query));
        $engine->registerFunction('instance_url', fn(string $path) => Env::instance_url($path));
        $engine->registerFunction('isLoggedIn', fn() => Session::isLoggedIn());

        return $engine->render($template, $data);
    }

    public static function renderError(string $error): string
    {
        return self::render('views/error', [
            'error' => $error
        ]);
    }
}
