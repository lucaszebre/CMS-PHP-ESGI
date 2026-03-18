<?php

declare(strict_types=1);

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        $this->render('login', [
            'error' => isset($_GET['error']) ? trim((string) $_GET['error']) : '',
            'success' => isset($_GET['success']) ? trim((string) $_GET['success']) : '',
        ]);
    }

    public function showRegister(): void
    {
        $this->render('register', [
            'error' => isset($_GET['error']) ? trim((string) $_GET['error']) : '',
            'success' => isset($_GET['success']) ? trim((string) $_GET['success']) : '',
        ]);
    }

    public function login(): void
    {
        if (empty($_POST)) {
            $this->redirect('/login');
        }

        $password = $_POST['password'] ?? '';
        $email = strtolower(trim($_POST['email'] ?? ''));

        $result = $this->authService->login($email, $password);

        if (!$result['success']) {
            $this->redirect('/login?error=' . urlencode($result['error']));
        }

        $this->redirect('/');
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }

    public function register(): void
    {
        if (empty($_POST)) {
            $this->redirect('/register');
        }

        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $username = ucwords(strtolower(trim($_POST['username'] ?? '')));
        $email = strtolower(trim($_POST['email'] ?? ''));

        $result = $this->authService->register($email, $username, $password, $passwordConfirm);

        if (!$result['success']) {
            $this->redirect('/register?error=' . urlencode($result['error']));
        }

        $this->redirect('/login');
    }
}
