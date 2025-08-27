<?php

namespace App\Controllers;

use App\Wrappers\Db;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class InviteController
{
    public static function index(ServerRequestInterface $request): Response
    {
        $db = new Db;
        $invites = $db->getInvites();
        return new HtmlResponse(Plates::render('views/invites', [
            'users' => $invites,
        ]));
    }
}
