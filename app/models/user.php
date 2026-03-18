<?php

require_once dirname(__DIR__, 2) . '/config/database.php';

class User
{

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addUser($email, $username, $password)
    {
        $stmt = $this->db->prepare('INSERT INTO user (email, username, password) VALUES (:email, :username, :password)');
        return $stmt->execute([
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ]);
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

        $stmt = $this->db->prepare("SELECT username,email,role FROM user WHERE id=:id");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        return $user;
    }

    public function getUserByEmail($email)
    {


        $stmt = $this->db->prepare('SELECT username, email, password, role FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        return $user ?? null;
    }

    public function removeUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM user WHERE id=:id");
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        return $user;
    }
}
