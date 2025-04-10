<?php
namespace App\Wrappers;

class Env {
  public static function parse(string $path): void {
    $arr = parse_ini_file($path);

    if ($arr === false) {
      return;
    }

    foreach ($arr as $key => $val) {
      putenv("$key=$val");
      $_ENV[$key] = $val;
    }
  }

  public static function ldap(): array {
    $host = $_ENV["LDAP_HOST"] ?? "127.0.0.1";
    $port = $_ENV["LDAP_PORT"] ?? 3306;
    $dn = $_ENV["LDAP_DN"] ?? '';
    $password = $_ENV["LDAP_PASSWORD"] ?? null;
    $base = $_ENV["LDAP_BASE"] ?? null;

    return [
      "host" => $host,
      "port" => $port,
      "dn" => $dn,
      "password" => $password,
      "base" => $base,
    ];
  }

  public static function app_url(string $path): string {
    $base = $_ENV['APP_URL'] ?? 'http://localhost:8080';
    return $base . $path;
  }

  public static function app_password(): string {
    return $_ENV['APP_PASSWORD'] ?? '';
  }
}
