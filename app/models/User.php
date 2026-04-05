<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Config\Database;

class User
{
    public const ROLES = [
        'admin' => 'Admin',
        'editor' => 'Editor',
        'user' => 'User',
    ];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addUser($email, $username, $password, $role = 'user', string $activationToken = ''): int
    {
        $stmt = $this->db->prepare('INSERT INTO user (email, username, password, role, is_active, activation_token) VALUES (:email, :username, :password, :role, 0, :activation_token)');
        $stmt->execute([
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'activation_token' => $activationToken,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare('SELECT id, username, email, role, is_active FROM user ORDER BY id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserById($id, $username, $email, $role)
    {
        $stmt = $this->db->prepare('UPDATE user SET username = :username, email = :email, role = :role WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'role' => $role,
        ]);
    }

    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM user WHERE email = :email AND id != :id');
            $stmt->execute(['email' => $email, 'id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM user WHERE email = :email');
            $stmt->execute(['email' => $email]);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function updateUser($email, $username, $role)
    {
        $stmt = $this->db->prepare('UPDATE user SET username = :username, role = :role WHERE email = :email');

        return $stmt->execute([
            'email' => $email,
            'username' => $username,
            'role' => $role,
        ]);
    }

    public function getUser($id)
    {
        $stmt = $this->db->prepare('SELECT id, username, email, role, is_active FROM user WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT id, username, email, password, role, is_active FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function removeUser($id)
    {
        $stmt = $this->db->prepare('DELETE FROM user WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare('UPDATE user SET password = :password WHERE id = :id');
        return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    public function activate(string $token): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM user WHERE activation_token = :token AND is_active = 0');
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        $update = $this->db->prepare('UPDATE user SET is_active = 1, activation_token = NULL WHERE id = :id');
        return $update->execute(['id' => $user['id']]);
    }
}
