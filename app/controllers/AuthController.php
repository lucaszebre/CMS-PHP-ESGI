<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Services\AuthService;
use App\Services\AuthSession;

class AuthController extends Controller
{
    private AuthService $authService;
    private AuthSession $authSession;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->authSession = new AuthSession();
    }

    public function showLogin(Request $request): void
    {
        $this->render('login', [
            'error' => trim($request->query('error')),
            'success' => trim($request->query('success')),
        ]);
    }

    public function showRegister(Request $request): void
    {
        $this->render('register', [
            'error' => trim($request->query('error')),
            'success' => trim($request->query('success')),
        ]);
    }

    public function login(Request $request): void
    {
        if (!$request->hasBody()) {
            $this->redirect('/login');
        }

        $password = $request->input('password');
        $email = strtolower(trim($request->input('email')));

        $result = $this->authService->authenticate($email, $password);

        if (!$result['success']) {
            $this->redirect('/login?error=' . urlencode($result['error']));
        }

        $this->authSession->login($result['user']);
        $this->redirect('/');
    }

    public function logout(Request $request): void
    {
        $this->authSession->logout();
        $this->redirect('/login');
    }

    public function register(Request $request): void
    {
        if (!$request->hasBody()) {
            $this->redirect('/register');
        }

        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirm');
        $username = ucwords(strtolower(trim($request->input('username'))));
        $email = strtolower(trim($request->input('email')));

        $result = $this->authService->register($email, $username, $password, $passwordConfirm);

        if (!$result['success']) {
            $this->redirect('/register?error=' . urlencode($result['error']));
        }

        $this->redirect('/login');
    }
}
