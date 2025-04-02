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

    public static function fromJson(array $data): self
    {
        return new User(
            firstName: $data['firstName'],
            lastName: $data['surname'],
            email: $data['email'],
        );
    }

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function __toString(): string {
        return $this->username;
    }

    private function __buildUsername(): string
    {
        return explode('@', $this->email)[0];
    }
}
