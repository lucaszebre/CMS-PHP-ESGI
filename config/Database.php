<?php

declare(strict_types=1);

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = new PDO("mysql:host=db;dbname=" . getenv("MARIADB_DATABASE"), getenv("MARIADB_USER"), getenv("MARIADB_PASSWORD"));
    }

    public static function getInstance(): mixed
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
