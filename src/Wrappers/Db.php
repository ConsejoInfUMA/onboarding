<?php

namespace App\Wrappers;

use App\Models\User;

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

    public function createInvite(User $user): ?string
    {
        $token = $this->__generateToken();
        $stmt = $this->client->prepare('INSERT INTO invites(username, firstName, lastName, email, token) VALUES(?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $user->username, $user->firstName, $user->lastName, $user->email, $token);
        $ok = $stmt->execute();
        return $ok ? $token : null;
    }

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
