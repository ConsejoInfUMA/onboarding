<?php

namespace App\Wrappers;

use App\Models\User;

/**
 * DB Wrapper
 */
class Db
{
    private \PDO $client;

    public function __construct()
    {
        $db = Env::db();
        $this->client = new \PDO($db['dsn'], $db['username'], $db['password'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERR_NONE,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    /**
     * Get all invites available.
     *
     * @return User[]
     */
    public function getInvites(): array
    {
        $users = [];
        $stmt = $this->client->query('SELECT username, firstName, lastName, email FROM invites');
        while ($row = $stmt->fetch()) {
            $users[] = User::fromArray($row);
        }

        return $users;
    }

    /**
     * Create an invite for a user.
     *
     * @return ?string Token
     */
    public function createInvite(User $user): ?string
    {
        $token = $this->__generateToken();
        $stmt = $this->client->prepare('INSERT INTO invites(username, firstName, lastName, email, token) VALUES(?, ?, ?, ?, ?)');
        $ok = $stmt->execute([$user->username, $user->firstName, $user->lastName, $user->email, $token]);
        return $ok ? $token : null;
    }

    /**
     * Find invite by token.
     */
    public function getInviteByToken(string $token): ?User
    {
        $stmt = $this->client->prepare('SELECT id, username, firstName, lastName, email, token FROM invites WHERE token=?');
        $ok = $stmt->execute([$token]);

        if (!$ok) {
            return null;
        }

        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return User::fromArray($row);
    }

    /**
     * Check if invite exists by email.
     */
    public function checkInviteExistsByEmail(string $email): bool
    {
        $stmt = $this->client->prepare('SELECT COUNT(*) FROM invites WHERE email=?');
        $ok = $stmt->execute([$email]);

        if (!$ok) {
            return false;
        }

        $num = $stmt->fetchColumn();
        return $num > 0;
    }

    /**
     * Remove invite by email.
     */
    public function removeInviteByEmail(string $email): bool
    {
        $stmt = $this->client->prepare('DELETE FROM invites WHERE email=?');
        $ok = $stmt->execute([$email]);
        return $ok;
    }

    private function __generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
