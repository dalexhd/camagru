<?php

use core\Migration;

class CreatePostsTable extends Migration
{
	public function up()
	{
		$this->db->exec("
			CREATE TABLE IF NOT EXISTS posts (
				id INT AUTO_INCREMENT PRIMARY KEY,
				creator INT NOT NULL,
				title VARCHAR(255) NOT NULL,
				body TEXT,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				FOREIGN KEY (creator) REFERENCES users(id) ON DELETE CASCADE
			)
		");
	}

	public function down()
	{
		$this->db->exec("DROP TABLE IF EXISTS posts");
	}
}
