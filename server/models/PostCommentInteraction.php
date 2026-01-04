<?php

namespace app\models;

use core\Model;
use PDO;

class PostCommentInteraction extends Model
{
    protected $table = 'post_comment_interaction';

    /**
     * Create a new comment interaction. For now, this feature is not implemented.
     * 
     * @param int $commentId
     * @param string $type
     * @param int $userId
     * @return bool|string
     */
    public function create($commentId, $type, $userId)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, comment_id, type) VALUES (:userId, :commentId, :type)");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':commentId', $commentId);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}
