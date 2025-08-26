<?php

namespace App\Models;

class User
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $username;

    public function __construct(string $firstName, string $lastName, string $email, ?string $username = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->username = $username ?? $this->__buildUsername();
    }

    public static function fromArray(array $data): self
    {
        return new User(
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            email: $data['email'],
            username: $data['username'] ?? null,
        );
    }

    public static function fromLdap(array $data): self
    {
        return new User(
            firstName: $data['first_name'][0],
            lastName: $data['last_name'][0],
            email: $data['mail'][0],
            username: $data['uid'][0],
        );
    }

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function __toString(): string
    {
        return $this->email;
    }

    private function __buildUsername(): string
    {
        return explode('@', $this->email)[0];
    }
}
