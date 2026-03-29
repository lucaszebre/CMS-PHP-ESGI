<?php

declare(strict_types=1);

namespace App\Services;

class AuthSession
{
    public function login(array $user): void
    {
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'email' => $user['email'] ?? null,
            'username' => $user['username'] ?? null,
            'role' => $user['role'] ?? null,
        ];
    }

    public function username(): ?string
    {
        $username = $_SESSION['user']['username'] ?? null;

        return is_string($username) ? $username : null;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
