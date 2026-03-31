<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Config\Database;

class Page
{
    public const MAX_CONTENT_LENGTH = 16000;
    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
        'unpublished' => 'Unpublished',
    ];
    public const UPDATE_RESULT_FAILED = 'failed';
    public const UPDATE_RESULT_NOT_FOUND = 'not-found';
    public const UPDATE_RESULT_SAVED = 'saved';

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

    public function updatePage($id, $title, $content, $status, $author, $date, $slug): string
    {
        $stmt = $this->db->prepare('UPDATE page SET title = :title, content = :content, status = :status, author = :author, date = :date, slug = :slug WHERE id = :id');

        $result = $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'author' => $author,
            'date' => $date,
            'slug' => $slug,
        ]);

        if (!$result) {
            return self::UPDATE_RESULT_FAILED;
        }

        if ($stmt->rowCount() > 0) {
            return self::UPDATE_RESULT_SAVED;
        }

        return $this->getPageById((int) $id) === null
            ? self::UPDATE_RESULT_NOT_FOUND
            : self::UPDATE_RESULT_SAVED;
    }

    public function getAllPages(): array
    {
        $stmt = $this->db->prepare('SELECT id, title, content, status, author, date, slug FROM page ORDER BY date DESC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublishedPages(): array
    {
        $stmt = $this->db->prepare("SELECT id, title, content, status, author, date, slug FROM page WHERE status = 'published' ORDER BY date DESC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, title, content, status, author, date, slug FROM page WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getPage($slug)
    {
        $stmt = $this->db->prepare('SELECT id, title, content, status, author, date, slug FROM page WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);

        $page = $stmt->fetch(PDO::FETCH_ASSOC);

        return $page ?: null;
    }

    public function removePage(int $id)
    {
        $stmt = $this->db->prepare('DELETE FROM page WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function slugExists(string $slug, ?int $currentPageId = null): bool
    {
        if ($currentPageId !== null) {
            $stmt = $this->db->prepare('SELECT 1 FROM page WHERE slug = :slug AND id != :id');
            $stmt->execute(['slug' => $slug, 'id' => $currentPageId]);
        } else {
            $stmt = $this->db->prepare('SELECT 1 FROM page WHERE slug = :slug');
            $stmt->execute(['slug' => $slug]);
        }

        return (bool) $stmt->fetch();
    }
}
