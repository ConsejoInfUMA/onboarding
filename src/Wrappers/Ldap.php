<?php

namespace App\Wrappers;

use App\Models\User;
use LDAP\Connection;

class Ldap
{
    private Connection $conn;
    private string $base;

    public function __construct(?string $password = null)
    {
        $config = Env::ldap();
        $this->base = $config['base'];

        $conn = @ldap_connect($config['uri']);

        if ($conn === false) {
            throw new \Exception("Could not connect to ldap");
        }

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $this->conn = $conn;

        $r = @ldap_bind($this->conn, $this->__buildUserDn($config['username']), $password ?? $config['password']);
        if (!$r) {
            throw new \Exception("Could not bind to ldap");
        }
    }

    public function __destruct()
    {
        ldap_unbind($this->conn);
    }

    /**
     * Check if a user exists in LDAP by their email address.
     * @param string $email
     * @return bool
     */
    public function checkUserExistsByEmail(string $email): bool
    {
        // 1. Define the search filter
        // We escape the email to prevent LDAP injection
        $filter = "(mail=" . ldap_escape($email, "", LDAP_ESCAPE_FILTER) . ")";

        // 2. Search only for the 'dn' to keep the response lightweight
        $search = @ldap_search($this->conn, $this->base, $filter, ['dn']);

        if (!$search) {
            return false;
        }

        // 3. Count the entries found
        $count = ldap_count_entries($this->conn, $search);

        return $count > 0;
    }

    /**
     * Add a user to LDAP.
     */
    public function addUser(User $user, string $password): bool
    {
        $dn = $this->__buildUserDn($user->username);
        $added = ldap_add(
            ldap: $this->conn,
            dn: $dn,
            entry: [
                'objectClass' => 'person',
                'uid' => $user->username,
                'cn' => $user->getFullName(),
                'givenName' => $user->firstName,
                'sn' => $user->lastName,
                'mail' => $user->email,
                'user_id' => $user->username,
            ],
        );

        if (!$added) {
            return false;
        }

        return ldap_exop_passwd($this->conn, $dn, '', $password);
    }

    public function removeUser(User $user): bool
    {
        return ldap_delete(
            ldap: $this->conn,
            dn: $this->__buildUserDn($user->username)
        );
    }

    private function __buildUserDn(string $username): string
    {
        return "uid={$username},{$this->base}";
    }
}
