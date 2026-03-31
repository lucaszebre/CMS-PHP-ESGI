<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;
use App\Services\AuthSession;

class AdminUserController extends Controller
{
    private User $user;
    private AuthSession $authSession;

    public function __construct()
    {
        $this->user = new User();
        $this->authSession = new AuthSession();

        if (!$this->authSession->isLoggedIn()) {
            $this->redirect('/login');
        }

        if (!$this->authSession->isAdmin()) {
            $this->redirect('/');
        }
    }

    public function index(Request $request)
    {
        $this->render('admin/users/index', [
            'users' => $this->user->getAllUsers(),
            'error' => $this->errorMessage(trim($request->query('error'))),
        ]);
    }

    public function showCreate(Request $request)
    {
        $this->render('admin/users/create', [
            'error' => $this->errorMessage(trim($request->query('error'))),
            'roles' => User::ROLES,
        ]);
    }

    public function create(Request $request)
    {
        $email = strtolower(trim($request->input('email')));
        $username = ucwords(strtolower(trim($request->input('username'))));
        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirm');
        $role = $request->input('role');

        if ($password !== $passwordConfirm) {
            $this->redirect('/admin/users/create?error=password-mismatch');
        }

        if (strlen($password) < 8) {
            $this->redirect('/admin/users/create?error=password-too-short');
        }

        if ($this->user->emailExists($email)) {
            $this->redirect('/admin/users/create?error=email-taken');
        }

        if (!$this->isValidRole($role)) {
            $this->redirect('/admin/users/create?error=invalid-role');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->user->addUser($email, $username, $hashedPassword, $role)) {
            $this->redirect('/admin/users/create?error=save-failed');
        }

        $this->redirect('/admin/users');
    }

    public function showEdit(Request $request)
    {
        $user = $this->user->getUser((int) $request->param('id'));

        if (!$user) {
            $this->redirect('/admin/users?error=user-not-found');
        }

        $this->render('admin/users/edit', [
            'user' => $user,
            'error' => $this->errorMessage(trim($request->query('error'))),
            'roles' => User::ROLES,
        ]);
    }

    public function update(Request $request)
    {
        $id = (int) $request->param('id');
        $email = strtolower(trim($request->input('email')));
        $username = ucwords(strtolower(trim($request->input('username'))));
        $role = $request->input('role');

        $existingUser = $this->user->getUser($id);

        if (!$existingUser) {
            $this->redirect('/admin/users?error=user-not-found');
        }

        if (!$this->isValidRole($role)) {
            $this->redirect('/admin/users/edit/' . $id . '?error=invalid-role');
        }

        if ($this->user->emailExists($email, $id)) {
            $this->redirect('/admin/users/edit/' . $id . '?error=email-taken');
        }

        if (!$this->user->updateUserById($id, $username, $email, $role)) {
            $this->redirect('/admin/users/edit/' . $id . '?error=save-failed');
        }

        $this->redirect('/admin/users');
    }

    public function delete(Request $request)
    {
        $id = (int) $request->param('id');

        if ($id === $this->getCurrentUserId()) {
            $this->redirect('/admin/users?error=cannot-delete-self');
        }

        $user = $this->user->getUser($id);

        if ($user) {
            $this->user->removeUser($id);
        }

        $this->redirect('/admin/users');
    }

    private function isValidRole(string $role): bool
    {
        return in_array($role, array_keys(User::ROLES), true);
    }

    private function getCurrentUserId(): ?int
    {
        $email = $_SESSION['user']['email'] ?? null;
        if ($email === null) {
            return null;
        }
        $user = $this->user->getUserByEmail($email);
        return $user ? (int) $user['id'] : null;
    }

    private function errorMessage(string $errorCode): string
    {
        return match ($errorCode) {
            'email-taken' => 'This email is already used by another user.',
            'invalid-role' => 'Invalid role selected.',
            'password-mismatch' => 'Passwords do not match.',
            'password-too-short' => 'Password must be at least 8 characters.',
            'save-failed' => 'The user could not be saved. Please try again.',
            'user-not-found' => 'The user no longer exists.',
            'cannot-delete-self' => 'You cannot delete your own account.',
            default => '',
        };
    }
}
