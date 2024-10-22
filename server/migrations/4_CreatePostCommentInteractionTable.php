<?php

use core\Migration;

class CreatePostCommentInteractionTable extends Migration
{
	public function up()
	{
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS post_comment_interaction (
                id INT AUTO_INCREMENT PRIMARY KEY,
                comment_id INT NOT NULL,
                user_id INT NOT NULL,
                type ENUM('like', 'dislike') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (comment_id) REFERENCES post_comments(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
	}

	public function down()
	{
	}
}
