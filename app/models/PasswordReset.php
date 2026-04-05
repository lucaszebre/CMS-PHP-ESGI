<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Config\Database;

class PasswordReset
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createToken(int $userId, string $token, string $expiresAt): void
    {
        $this->deleteByUserId($userId);

        $stmt = $this->db->prepare('INSERT INTO password_reset (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $stmt->execute([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findByToken(string $token): ?array
    {
        $stmt = $this->db->prepare('SELECT pr.*, u.email FROM password_reset pr JOIN user u ON pr.user_id = u.id WHERE pr.token = :token');
        $stmt->execute(['token' => $token]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function isExpired(array $reset): bool
    {
        return strtotime($reset['expires_at']) < time();
    }

    public function deleteByToken(string $token): void
    {
        $stmt = $this->db->prepare('DELETE FROM password_reset WHERE token = :token');
        $stmt->execute(['token' => $token]);
    }

    public function deleteByUserId(int $userId): void
    {
        $stmt = $this->db->prepare('DELETE FROM password_reset WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
    }
}
