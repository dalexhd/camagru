<?php

namespace app\models;

use core\Model;
use PDO;

class PostLikes extends Model
{
    protected string $table = 'post_likes';

    /**
     * Toggle a like.
     * 
     * If the user has already liked the post, it will be deleted (unliked).
     * Otherwise, it will be created (liked).
     * Returns true if liked, false if unliked.
     * 
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public function toggle($userId, $postId)
    {
        $existingLike = $this->findByUserIdAndPostId($userId, $postId);
        if ($existingLike) {
            $this->delete($existingLike['id']);
        } else {
            $this->create($postId, $userId);
        }
        return $existingLike ? false : true;
    }

    /**
     * Create a new like.
     * 
     * Adds a row to the post_likes table.
     * 
     * @param int $postId
     * @param int $userId
     * @return bool|string
     */
    public function create($postId, $userId)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (post_id, user_id) VALUES (:postId, :userId)");
        $stmt->bindParam(':postId', $postId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $this->db->lastInsertId();
    }


    /**
     * Find if a user has liked a post.
     * 
     * Checks if a record exists for this user and post combo.
     * 
     * @param int $userId
     * @param int $postId
     * @return array
     */
    public function findByUserIdAndPostId($userId, $postId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :userId AND post_id = :postId LIMIT 1");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a like.
     * 
     * Basically, unlikes a post.
     * 
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
