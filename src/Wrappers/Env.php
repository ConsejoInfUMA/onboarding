<?php

namespace App\Wrappers;

class Env
{
    public static function parse(string $path): void
    {
        $arr = parse_ini_file($path);

        if ($arr === false) {
            return;
        }

        foreach ($arr as $key => $val) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }

    public static function app_url(string $path, ?array $query = null): string
    {
        $base = $_ENV['APP_URL'] ?? 'http://localhost:8080';
        $queryStr = '';

        if ($query !== null) {
            $queryStr = '?' . http_build_query($query);
        }

        return $base . $path . $queryStr;
    }

    public static function instance_url(string $path): string
    {
        $base = $_ENV['INSTANCE_URL'] ?? 'http://localhost';
        return $base . $path;
    }

    public static function db(): array
    {
        $host = $_ENV["DB_HOST"] ?? "127.0.0.1";
        $port = $_ENV["DB_PORT"] ?? 3306;
        $user = $_ENV["DB_USER"] ?? "";
        $password = $_ENV["DB_PASSWORD"] ?? "";
        $name = $_ENV["DB_NAME"] ?? "onboarding";

        return [
            "host" => $host,
            "port" => $port,
            "database" => $name,
            "username" => $user,
            "password" => $password
        ];
    }

    public static function ldap(): array
    {
        $uri = $_ENV["LDAP_URI"] ?? "ldap://127.0.0.1:3306";
        $username = $_ENV["LDAP_USERNAME"] ?? '';
        $password = $_ENV["LDAP_PASSWORD"] ?? null;
        $base = $_ENV["LDAP_BASE"] ?? null;

        return [
            "uri" => $uri,
            "username" => $username,
            "password" => $password,
            "base" => $base,
        ];
    }

    public static function mail(): array
    {
        $host = $_ENV['MAIL_HOST'] ?? 'localhost';
        $port = $_ENV['MAIL_PORT'] ?? 25;
        $username = $_ENV['MAIL_USERNAME'] ?? '';
        $password = $_ENV['MAIL_PASSWORD'] ?? '';
        $secure = $_ENV['MAIL_SECURE'] ?? '';
        $from = $_ENV['MAIL_FROM'] ?? '';

        return [
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'secure' => $secure,
            'from' => $from,
        ];
    }

    public static function csv_columns(): array
    {
        $firstName = $_ENV['CSV_COLUMN_FIRSTNAME'] ?? '';
        $lastName = $_ENV['CSV_COLUMN_LASTNAME'] ?? '';
        $email = $_ENV['CSV_COLUMN_EMAIL'] ?? '';

        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ];
    }
}
