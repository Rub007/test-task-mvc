<?php

namespace components;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public static function flash(string $key, $value): void
    {
        $_SESSION['__request'][$key] = $value;
    }

    public static function store(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function removeRequestSessions(): void
    {
        unset($_SESSION['__request']);
    }

    public static function has(string $name): bool
    {
        return isset($_SESSION[$name]) ?? isset($_SESSION['__request'][$name]);
    }
}