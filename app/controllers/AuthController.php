<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\AuthService;
use App\Services\AuthSession;
use App\Services\MailService;

class AuthController extends Controller
{
    private AuthService $authService;
    private AuthSession $authSession;
    private User $user;
    private PasswordReset $passwordReset;
    private MailService $mailService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->authSession = new AuthSession();
        $this->user = new User();
        $this->passwordReset = new PasswordReset();
        $this->mailService = new MailService();
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

        $activateUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/activate?token=' . $result['activation_token'];

        $body = '<p>Hello ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Thank you for registering. Please activate your account by clicking the link below:</p>'
            . '<p><a href="' . $activateUrl . '">' . $activateUrl . '</a></p>';

        $this->mailService->send($email, 'Activate your account', $body);

        $this->redirect('/login?success=Account created. Please check your email to activate it.');
    }

    public function activate(Request $request): void
    {
        $token = trim($request->query('token'));

        if ($token === '' || !$this->user->activate($token)) {
            $this->redirect('/login?error=Invalid or expired activation link.');
        }

        $this->redirect('/login?success=Account activated. You can now log in.');
    }

    public function showForgotPassword(Request $request): void
    {
        $this->render('forgot-password', [
            'error' => trim($request->query('error')),
            'success' => trim($request->query('success')),
        ]);
    }

    public function forgotPassword(Request $request): void
    {
        $email = strtolower(trim($request->input('email')));

        $fetchedUser = $this->user->getUserByEmail($email);

        if ($fetchedUser) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);

            $this->passwordReset->createToken((int) $fetchedUser['id'], $token, $expiresAt);

            $resetUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token;

            $body = '<p>Hello ' . htmlspecialchars($fetchedUser['username'], ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>You requested a password reset. Click the link below to set a new password:</p>'
                . '<p><a href="' . $resetUrl . '">' . $resetUrl . '</a></p>'
                . '<p>This link expires in 1 hour. If you did not request this, ignore this email.</p>';

            $this->mailService->send($email, 'Password Reset', $body);
        }

        $this->redirect('/forgot-password?success=If an account exists for this email, a reset link has been sent.');
    }

    public function showResetPassword(Request $request): void
    {
        $token = trim($request->query('token'));

        if ($token === '') {
            $this->redirect('/forgot-password?error=Missing reset token.');
        }

        $reset = $this->passwordReset->findByToken($token);

        if (!$reset || $this->passwordReset->isExpired($reset)) {
            $this->redirect('/forgot-password?error=This reset link is invalid or has expired.');
        }

        $this->render('reset-password', [
            'token' => $token,
            'error' => trim($request->query('error')),
        ]);
    }

    public function resetPassword(Request $request): void
    {
        $token = trim($request->input('token'));
        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirm');

        if ($password !== $passwordConfirm) {
            $this->redirect('/reset-password?token=' . urlencode($token) . '&error=Passwords do not match.');
        }

        if (strlen($password) < 8) {
            $this->redirect('/reset-password?token=' . urlencode($token) . '&error=Password must be at least 8 characters.');
        }

        $reset = $this->passwordReset->findByToken($token);

        if (!$reset || $this->passwordReset->isExpired($reset)) {
            $this->redirect('/forgot-password?error=This reset link is invalid or has expired.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->user->updatePassword((int) $reset['user_id'], $hashedPassword);
        $this->passwordReset->deleteByToken($token);

        $this->redirect('/login?success=Your password has been reset. You can now log in.');
    }
}
