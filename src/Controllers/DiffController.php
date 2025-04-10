<?php

namespace App\Controllers;

use App\Models\User;
use App\Wrappers\Env;
use App\Wrappers\Ldap;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class DiffController
{
    public static function post(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if (!($body !== null && array_key_exists('users', $body))) {
            // TODO: Show proper error
            return new RedirectResponse(Env::app_url('/login'));
        }

        $csvUsers = array_map(fn($user) => User::fromJson($user), $body['users']);

        $ldap = new Ldap();
        $ldapUsers = $ldap->getUsers();

        # Usuarios que están en en el CSV pero no en el LDAP
        $usersAdd = array_diff($csvUsers, $ldapUsers);
        # Usuarios que están tanto en el CSV como en el LDAP
        $usersOk = array_intersect($csvUsers, $ldapUsers);
        # Usuarios que están en el LDAP pero no en el CSV
        $usersRemove = array_diff($ldapUsers, $csvUsers);

        sort($usersAdd);
        sort($usersOk);
        sort($usersRemove);

        return new HtmlResponse(Plates::render('views/diff', [
            'usersAdd' => $usersAdd,
            'usersOk' => $usersOk,
            'usersRemove' => $usersRemove,
        ]));
    }

    public static function apply(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if ($body === null) {
            // TODO: Show proper error
            return new RedirectResponse(Env::app_url('/login'));
        }

        $ldap = new Ldap();

        if (array_key_exists('usersAdd', $body)) {
            // Agregar usuario a LDAP
            $usersAdd = array_map(fn($user) => User::fromJson($user, true), $body['usersAdd']);
            foreach ($usersAdd as $user) {
                $ldap->addUser($user);
            }
        }

        if (array_key_exists('usersRemove', $body)) {
            // Eliminar usuario de LDAP
            $usersRemove = array_map(fn($user) => User::fromJson($user, true), $body['usersRemove']);
            foreach ($usersRemove as $user) {
                $ldap->removeUser($user);
            }
        }

        return new HtmlResponse(Plates::render('views/diffApply'));
    }
}
