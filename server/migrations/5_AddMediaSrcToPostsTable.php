<?php

use core\Migration;

class AddMediaSrcToPostsTable extends Migration
{
	public function up()
	{
		$this->db->exec("
            ALTER TABLE posts
            ADD COLUMN media_src VARCHAR(255) AFTER body
		");
	}

	public function down()
	{
        $this->db->exec("
            ALTER TABLE posts
            DROP COLUMN media_src
        ");
	}
}
