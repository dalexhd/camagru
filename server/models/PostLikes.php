<?php

namespace app\models;

use core\Model;
use PDO;

class PostLikes extends Model
{
    protected $table = 'post_likes';

    /**
     * Find a like by id.
     * 
     * @param int $id
     * @return array
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Toggle a like. If the user has already liked the post, it will be deleted. Otherwise, it will be created.
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
     * Delete a like. Basically, unlikes a post.
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
