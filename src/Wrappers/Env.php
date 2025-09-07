<?php

namespace App\Wrappers;

/**
 * Enviroment variables wrapper
 */
class Env
{
    /**
     * Parse all variables from .env file.
     */
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

    /**
     * Get full app url.
     */
    public static function app_url(string $path, ?array $query = null): string
    {
        $base = $_ENV['APP_URL'] ?? 'http://localhost:8080';
        $queryStr = '';

        if ($query !== null) {
            $queryStr = '?' . http_build_query($query);
        }

        return $base . $path . $queryStr;
    }

    /**
     * Get full instance url where all services provided are.
     */
    public static function instance_url(string $path): string
    {
        $base = $_ENV['INSTANCE_URL'] ?? 'http://localhost';
        return $base . $path;
    }

    /**
     * Get DB data.
     */
    public static function db(): array
    {
        $dsn = $_ENV["DB_DSN"] ?? '';
        $user = $_ENV["DB_USER"] ?? null;
        $password = $_ENV["DB_PASSWORD"] ?? null;

        return [
            "dsn" => $dsn,
            "username" => $user,
            "password" => $password
        ];
    }

    /**
     * Get LDAP data.
     */
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

    /**
     * Get SMTP data.
     */
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

    /**
     * Get CSV headers used for parsing.
     */
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
