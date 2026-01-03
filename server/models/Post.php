<?php

namespace app\models;

use core\Model;
use core\Session;
use app\models\User;
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

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function findByCreator($creatorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE creator = :creatorId ORDER BY created_at DESC");
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

        // Get current logged-in user id if available
        $userId = Session::get('user_id');

        $sql = "SELECT {$this->table}.*, 
                       COUNT(post_likes.id) as likes_count,
                       CASE WHEN :user_id IS NOT NULL AND EXISTS (
                           SELECT 1 FROM post_likes pl 
                           WHERE pl.post_id = {$this->table}.id AND pl.user_id = :user_id
                       ) THEN 1 ELSE 0 END AS liked_by_user,
                       CASE WHEN :user_id IS NOT NULL AND {$this->table}.creator = :user_id THEN 1 ELSE 0 END AS is_owner
                FROM {$this->table}
                LEFT JOIN post_likes ON {$this->table}.id = post_likes.post_id
                GROUP BY {$this->table}.id
                ORDER BY {$this->table}.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        // Bind user id (can be null if not logged in)
        $stmt->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare reusable statements for comments and author
        $commentsStmt = $this->db->prepare(
            "SELECT post_comments.*, post_comments.created_at as created_at, users.nickname, users.avatar, users.id, users.name
             FROM post_comments
             INNER JOIN users ON post_comments.user_id = users.id
             WHERE post_comments.post_id = :post_id
             ORDER BY post_comments.created_at DESC"
        );

        $authorStmt = $this->db->prepare(
            "SELECT nickname, avatar, id, name FROM users WHERE id = :creator_id LIMIT 1"
        );

        foreach ($rows as &$row) {
            $row['liked_by_user'] = (bool) $row['liked_by_user'];
            $row['is_owner'] = (bool) $row['is_owner'];

            // Load comments safely with bound parameter
            $commentsStmt->bindValue(':post_id', (int) $row['id'], PDO::PARAM_INT);
            $commentsStmt->execute();
            $row['comments'] = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Load author safely with bound parameter
            $authorStmt->bindValue(':creator_id', (int) $row['creator'], PDO::PARAM_INT);
            $authorStmt->execute();
            $author = $authorStmt->fetch(PDO::FETCH_ASSOC);
            if ($author && empty($author['avatar'])) {
                $author['avatar'] = User::DEFAULT_AVATAR;
            }
            $row['author'] = $author;

            // Process comment avatars
            foreach ($row['comments'] as &$comment) {
                if (empty($comment['avatar'])) {
                    $comment['avatar'] = User::DEFAULT_AVATAR;
                }
            }
        }

        return $rows;
    }
}
