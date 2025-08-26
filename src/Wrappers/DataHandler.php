<?php

namespace App\Wrappers;

use App\Models\User;

class DataHandler
{
    private Ldap $ldap;
    private Db $db;
    private Mail $mailer;

    public function __construct()
    {
        $this->ldap = new Ldap;
        $this->db = new Db;
        $this->mailer = new Mail;
    }

    /**
     * Get invited users and users already on LDAP server.
     *
     * @return User[]
     */
    public function getUsers(): array
    {
        return [
            ...$this->ldap->getUsers(),
            ...$this->db->getInvites(),
        ];
    }

    public function inviteUser(User $user): bool
    {
        $mailOk = false;
        $token = $this->db->createInvite($user);

        if ($token !== null) {
            $mailOk = $this->mailer->sendWelcome($user, $token);
        }

        return $token !== null && $mailOk;
    }

    public function removeUser(User $user): bool
    {
        // Try to find if user didn't accept invite yet.
        $inviteExists = $this->db->checkInviteExistsByEmail($user->email);
        if ($inviteExists) {
            return $this->db->removeInviteByEmail($user->email);
        }

        // If not try to remove ldap
        return $this->ldap->removeUser($user);
    }
}
