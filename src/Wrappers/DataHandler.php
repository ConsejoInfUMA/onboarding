<?php

namespace App\Wrappers;

use App\Models\User;

/**
 * Helper class that wraps LDAP, DB and Mailer.
 */
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
     * Get both invited and already on LDAP server users.
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

    /**
     * Invite user workflow.
     *
     * Send email with token if it was created and saved in db.
     */
    public function inviteUser(User $user): bool
    {
        $mailOk = false;
        $token = $this->db->createInvite($user);

        if ($token !== null) {
            $mailOk = $this->mailer->sendWelcome($user, $token);
        }

        return $token !== null && $mailOk;
    }

    /**
     * Remove user workflow.
     *
     * Remove from DB if exists, if not from LDAP
     */
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
