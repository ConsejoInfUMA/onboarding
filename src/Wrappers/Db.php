<?php

namespace App\Wrappers;

use App\Models\User;

/**
 * DB Wrapper using SQLite3
 */
class Db
{
    private \SQLite3 $client;

    public function __construct()
    {
        // SQLite3 expects a path to the database file
        $this->client = new \SQLite3(__DIR__ . '/../../data.db');
    }

    /**
     * Get all invites available.
     *
     * @return User[]
     */
    public function getInvites(): array
    {
        $users = [];
        $results = $this->client->query('SELECT username, firstName, lastName, email FROM invites');

        if ($results !== false) {
            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $users[] = User::fromArray($row);
            }
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

        $stmt->bindValue(1, $user->username);
        $stmt->bindValue(2, $user->firstName);
        $stmt->bindValue(3, $user->lastName);
        $stmt->bindValue(4, $user->email);
        $stmt->bindValue(5, $token);

        $result = $stmt->execute();
        return $result ? $token : null;
    }

    /**
     * Find invite by token.
     */
    public function getInviteByToken(string $token): ?User
    {
        $stmt = $this->client->prepare('SELECT id, username, firstName, lastName, email, token FROM invites WHERE token=:token');
        $stmt->bindValue(':token', $token);

        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

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
        $stmt = $this->client->prepare('SELECT COUNT(*) as count FROM invites WHERE email=:email');
        $stmt->bindValue(':email', $email);

        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        return ($row && $row['count'] > 0);
    }

    /**
     * Remove invite by email.
     */
    public function removeInviteByEmail(string $email): bool
    {
        $stmt = $this->client->prepare('DELETE FROM invites WHERE email=:email');
        $stmt->bindValue(':email', $email);

        return (bool)$stmt->execute();
    }

    private function __generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
