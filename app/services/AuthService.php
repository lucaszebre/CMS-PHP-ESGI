<?php

declare(strict_types=1);

class AuthService
{
    private User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? new User();
    }

    public function login(string $email, string $password): array
    {
        $fetchedUser = $this->user->getUserByEmail($email);

        if ($fetchedUser === null || !password_verify($password, $fetchedUser['password'])) {
            return [
                'success' => false,
                'error' => 'Wrong credentials.',
            ];
        }

        $_SESSION['username'] = $fetchedUser['username'];

        return ['success' => true];
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

        if ($fetchedUser !== null) {
            return [
                'success' => false,
                'error' => 'user already present',
            ];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->user->addUser($email, $username, $hashedPassword);

        return ['success' => true];
    }

    public function logout(): void
    {


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
