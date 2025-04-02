<?php
namespace App\Controllers;

use App\Wrappers\Env;
use App\Wrappers\Importer;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\UploadedFile;
use Psr\Http\Message\ServerRequestInterface;

class HomeController {
    public static function index(ServerRequestInterface $request): Response {
        return new HtmlResponse(Plates::render('views/home'));
    }

    public static function post(ServerRequestInterface $request): Response {
        $files = $request->getUploadedFiles();

        if (!array_key_exists('csv', $files)) {
            return new RedirectResponse(Env::app_url('/'));
        }

        /** @var UploadedFile */
        $f = $files['csv'];

        $users = Importer::users($f->getStream()->detach());

        return new HtmlResponse(Plates::render('views/preview', ['users' => $users]));
    }
}
