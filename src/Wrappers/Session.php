<?php
namespace App\Wrappers;

class Session {
    public static function start(): void {
        session_start();
    }

    public static function destroy(): void {
        session_destroy();
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    public static function setLoggedIn(bool $state): void {
        $_SESSION['loggedin'] = $state;
    }
}
