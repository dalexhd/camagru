<?php

namespace app\models;

use core\Model;
use PDO;

class Post extends Model
{
    protected $table = 'posts';

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByCreator($creatorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE creator = :creatorId");
        $stmt->bindParam(':creatorId', $creatorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($creatorId, $title, $body, $mediaSrc = null)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (creator, title, body, media_src) VALUES (:creatorId, :title, :body, :mediaSrc)");
        $stmt->bindParam(':creatorId', $creatorId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':mediaSrc', $mediaSrc);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function search($query)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE title LIKE :query OR body LIKE :query");
        $stmt->bindValue(':query', "%$query%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($page, $limit)
    {
        $page = (int) $page;
        $limit = (int) $limit;
        $offset = max(0, $page * $limit);
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['comments'] = $this->db->query("SELECT *, post_comments.created_at as created_at FROM post_comments INNER JOIN users ON post_comments.user_id = users.id WHERE post_id = {$row['id']} ORDER BY post_comments.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
            $row['author'] = $this->db->query("SELECT * FROM users WHERE id = {$row['creator']}")->fetch(PDO::FETCH_ASSOC);
        }

        return $rows;
    }
}
