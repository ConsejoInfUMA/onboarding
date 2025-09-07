<?php

namespace App\Controllers;

use App\Models\User;
use App\Wrappers\Mail;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class DevController
{
    public static function email(ServerRequestInterface $request): Response
    {
        $token = 'asd';
        $user = new User(
            firstName: 'Pepe',
            lastName: 'González',
            email: 'pepe@example.com',
            username: 'pepe1234',
        );

        $mail = new Mail;
        $mail->sendWelcome($user, $token);

        return new HtmlResponse("OK");
    }
}
