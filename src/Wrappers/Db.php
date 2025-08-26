<?php

namespace App\Wrappers;

use App\Models\User;

/**
 * DB Wrapper
 */
class Db
{
    private \mysqli $client;

    public function __construct()
    {
        $db = Env::db();
        $this->client = new \mysqli($db['host'], $db['username'], $db['password'], $db['database'], $db['port']);
    }

    public function __destruct()
    {
        $this->client->close();
    }

    /**
     * Get all invites available.
     *
     * @return User[]
     */
    public function getInvites(): array
    {
        $users = [];
        $result = $this->client->query('SELECT username, firstName, lastName, email FROM invites');
        foreach ($result as $user) {
            $users[] = User::fromArray($user);
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
        $stmt->bind_param('sssss', $user->username, $user->firstName, $user->lastName, $user->email, $token);
        $ok = $stmt->execute();
        return $ok ? $token : null;
    }

    /**
     * Find invite by token.
     */
    public function getInviteByToken(string $token): ?User
    {
        $stmt = $this->client->prepare('SELECT id, username, firstName, lastName, email, token FROM invites WHERE token=?');
        $stmt->bind_param('s', $token);
        $ok = $stmt->execute();

        if (!$ok) {
            return null;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return User::fromArray($row);
    }

    /**
     * Check if invite exists by email.
     */
    public function checkInviteExistsByEmail(string $email): bool
    {
        $stmt = $this->client->prepare('SELECT id FROM invites WHERE email=?');
        $stmt->bind_param('s', $email);
        $ok = $stmt->execute();

        if (!$ok) {
            return false;
        }

        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    /**
     * Remove invite by email.
     */
    public function removeInviteByEmail(string $email): bool
    {
        $stmt = $this->client->prepare('DELETE FROM invites WHERE email=?');
        $stmt->bind_param('s', $email);
        $ok = $stmt->execute();
        return $ok;
    }

    private function __generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
