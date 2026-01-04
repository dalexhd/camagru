<?php

namespace app\models;

use core\Model;
use PDO;

class PostComment extends Model
{
    protected $table = 'post_comments';

    /**
     * Find all comments for a post.
     * 
     * @param int $postId
     * @return array
     */
    public function findByPost($postId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE post_id = :postId");
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new comment for a post.
     * 
     * @param int $postId
     * @param int $userId
     * @param string $comment
     * @return bool|string
     */
    public function create($postId, $userId, $comment)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (post_id, user_id, comment) VALUES (:postId, :userId, :comment)");
        $stmt->bindParam(':postId', $postId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}
