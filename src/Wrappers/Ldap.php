<?php

namespace App\Wrappers;

use App\Models\User;
use LDAP\Connection;

class Ldap
{
    private Connection $conn;
    private string $base;

    public function __construct()
    {
        $config = Env::ldap();
        $this->base = $config['base'];

        $conn = ldap_connect($config['host'], $config['port']);

        if ($conn === false) {
            throw new \Exception("Could not connect to ldap");
        }

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $this->conn = $conn;

        $r = ldap_bind($this->conn, $config['dn'], $config['password']);
        if (!$r) {
            throw new \Exception("Could not bind to ldap");
        }
    }

    public function __destruct()
    {
        ldap_unbind($this->conn);
    }

    public function getUsers(): array
    {
        $users = [];
        $sr = ldap_search($this->conn, $this->base, "(objectClass=person)");

        if ($sr === false) {
            throw new \Exception("Could not search for users");
        }

        $data = ldap_get_entries($this->conn, $sr);

        if ($data['count'] === 0) {
            return $users;
        }

        for ($i = 0; $i < $data['count']; $i++) {
            $user = $data[$i];

            // Ignoramos cuenta de administrador
            if ($user['uid'][0] !== 'admin') {
                $users[] = User::fromLdap($user);
            }
        }

        return $users;
    }

    public function addUser(User $user): bool
    {
        return ldap_add(
            ldap: $this->conn,
            dn: "uid={$user->username},ou=people,{$this->base}",
            entry: [
                'objectClass' => 'person',
                'uid' => $user->username,
                'cn' => $user->username,
                'givenName' => $user->firstName,
                'sn' => $user->lastName,
                'mail' => $user->email,
                'user_id' => $user->username,
                // TODO: User password not working
                'userPassword' => $user->password,
            ],
        );
    }

    public function removeUser(User $user): bool
    {
        return ldap_delete(
            ldap: $this->conn,
            dn: "uid={$user->username},ou=people,{$this->base}",
        );
    }
}
