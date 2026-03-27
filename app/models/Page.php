<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Config\Database;

class Page
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addPage($title, $content, $status, $author, $date, $slug)
    {
        $stmt = $this->db->prepare('INSERT INTO page (title, content, status, author, date, slug) VALUES (:title, :content, :status, :author, :date, :slug)');

        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'author' => $author,
            'date' => $date,
            'slug' => $slug,
        ]);
    }

    public function updatePage($id, $title, $content, $status, $author, $date, $slug)
    {
        $stmt = $this->db->prepare('UPDATE page SET title = :title, content = :content, status = :status, author = :author, date = :date, slug = :slug WHERE id = :id');

        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'author' => $author,
            'date' => $date,
            'slug' => $slug,
        ]);
    }

    public function getPage($slug)
    {
        $stmt = $this->db->prepare('SELECT id, title, content, status, author, date, slug FROM page WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);

        $page = $stmt->fetch(PDO::FETCH_ASSOC);

        return $page ?? null;
    }

    public function removePage($slug)
    {
        $stmt = $this->db->prepare('DELETE FROM page WHERE slug = :slug');

        return $stmt->execute(['slug' => $slug]);
    }
}
