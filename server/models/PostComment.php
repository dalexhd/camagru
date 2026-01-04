<?php

namespace app\models;

use core\Model;
use PDO;

class PostComment extends Model
{
    protected string $table = 'post_comments';


    /**
     * Create a new comment for a post.
     * 
     * Insert the comment into the database.
     * Simple enough.
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
