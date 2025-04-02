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

    public function getUsers(): array {
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
}
