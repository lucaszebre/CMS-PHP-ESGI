<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AuthService
{
    private User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? new User();
    }

    public function authenticate(string $email, string $password): array
    {
        $fetchedUser = $this->user->getUserByEmail($email);

        if (!$fetchedUser || !password_verify($password, $fetchedUser['password'])) {
            return [
                'success' => false,
                'error' => 'Wrong credentials.',
            ];
        }

        unset($fetchedUser['password']);

        return [
            'success' => true,
            'user' => $fetchedUser,
        ];
    }

    public function register(string $email, string $username, string $password, string $passwordConfirm): array
    {
        if ($password !== $passwordConfirm) {
            return [
                'success' => false,
                'error' => 'password are not the same',
            ];
        }

        $fetchedUser = $this->user->getUserByEmail($email);

        if ($fetchedUser) {
            return [
                'success' => false,
                'error' => 'user already present',
            ];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->user->addUser($email, $username, $hashedPassword);

        return ['success' => true];
    }
}
