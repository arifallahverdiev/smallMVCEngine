<?php

namespace App\Core;

class Session
{
    public function __construct()
    {
        $this->sessionStart();
    }

    public function sessionStart(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function sessionEnd(): void
    {
        session_destroy();
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function addFlash(string $message, string $type): void
    {
        $flashes = $this->get('flashes');
        $flashes[] = [
            'message' => $message,
            'type' => $type
        ];
        $this->set('flashes', $flashes);
    }

    public function getFlashes(): array
    {
        $flashes = $this->get('flashes');
        $this->delete('flashes');
        return $flashes ?? [];
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function isAuth(): bool
    {
        return $this->has('auth');
    }
}