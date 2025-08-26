<?php

namespace App\Controllers;

use App\Models\User;
use App\Wrappers\DataHandler;
use App\Wrappers\Plates;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class DiffController
{
    /**
     * From parsed CSV file, show users to be added, deleted and unmodified.
     */
    public static function post(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if (!($body !== null && array_key_exists('users', $body))) {
            return new HtmlResponse(Plates::renderError('No body sent'));
        }

        $csvUsers = array_map(fn($user) => User::fromArray($user), $body['users']);

        $handler = new DataHandler;
        $localUsers = $handler->getUsers();

        # Usuarios que están en en el CSV pero no en el LDAP
        $usersAdd = array_diff($csvUsers, $localUsers);
        # Usuarios que están tanto en el CSV como en el LDAP
        $usersOk = array_intersect($csvUsers, $localUsers);
        # Usuarios que están en el LDAP pero no en el CSV
        $usersRemove = array_diff($localUsers, $csvUsers);

        sort($usersAdd);
        sort($usersOk);
        sort($usersRemove);

        return new HtmlResponse(Plates::render('views/diff', [
            'usersAdd' => $usersAdd,
            'usersOk' => $usersOk,
            'usersRemove' => $usersRemove,
        ]));
    }

    /**
     * Confirm changes and add / remove users.
     */
    public static function apply(ServerRequestInterface $request): Response
    {
        $body = $request->getParsedBody();
        if ($body === null) {
            return new HtmlResponse(Plates::renderError('No body sent'));
        }

        $handler = new DataHandler;

        $errors = [
            'usersAdd' => [],
            'usersRemove' => [],
        ];

        if (array_key_exists('usersAdd', $body)) {
            // Invite user
            $usersAdd = array_map(fn($user) => User::fromArray($user), $body['usersAdd']);
            foreach ($usersAdd as $user) {
                $ok = $handler->inviteUser($user);
                if (!$ok) {
                    $errors['usersAdd'][] = $user;
                }
            }
        }

        if (array_key_exists('usersRemove', $body)) {
            // Delete user from LDAP or invite
            $usersRemove = array_map(fn($user) => User::fromArray($user), $body['usersRemove']);
            foreach ($usersRemove as $user) {
                $ok = $handler->removeUser($user);
                if (!$ok) {
                    $errors['usersRemove'][] = $user;
                }
            }
        }

        return new HtmlResponse(Plates::render('views/diffApply', [
            'errors' => $errors,
        ]));
    }
}
