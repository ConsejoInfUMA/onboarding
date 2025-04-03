<?php

namespace App\Models;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class User
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $username;
    public string $password;

    public function __construct(string $firstName, string $lastName, string $email, ?string $username = null, ?string $password = null, bool $generatePassword = false)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->username = $username ?? $this->__buildUsername();

        if ($generatePassword) {
            $this->password = $this->__buildPassword();
        } else if ($password !== null) {
            $this->password = $password;
        }
    }

    public static function fromJson(array $data, bool $generatePassword = false): self
    {
        return new User(
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            email: $data['email'],
            username: $data['username'] ?? null,
            generatePassword: $generatePassword,
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
        return $this->username;
    }

    private function __buildPassword(): string
    {
        $generator = new ComputerPasswordGenerator();
        $generator
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
        ;

        return $generator->generatePassword();
    }

    private function __buildUsername(): string
    {
        return explode('@', $this->email)[0];
    }
}
