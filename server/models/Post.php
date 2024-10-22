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

    public function create($creatorId, $title, $body)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (creator, title, body) VALUES (:creatorId, :title, :body)");
        $stmt->bindParam(':creatorId', $creatorId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
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
}
