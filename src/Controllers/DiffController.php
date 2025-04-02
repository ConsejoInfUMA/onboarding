<?php
namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class DiffController {
    public static function index(ServerRequestInterface $request): Response {
        return new HtmlResponse(Plates::render('views/diff'));
    }

    public static function post(ServerRequestInterface $request): Response {
        // TODO
        return new RedirectResponse(Env::app_url('/'));
    }
}
