<?php

declare(strict_types=1);

namespace App\Services;

class CsrfService
{
    private const TOKEN_KEY = 'csrf_token';

    public static function generate(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_KEY] = $token;
        return $token;
    }

    public static function validate(string $token): bool
    {
        $sessionToken = $_SESSION[self::TOKEN_KEY] ?? null;

        if ($sessionToken === null) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::generate(), ENT_QUOTES, 'UTF-8') . '">';
    }
}
