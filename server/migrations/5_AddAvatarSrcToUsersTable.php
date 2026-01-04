<?php

use core\Migration;

class AddAvatarSrcToUsersTable extends Migration
{
	public function up()
	{
		$this->db->exec("
            ALTER TABLE users
            MODIFY COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER email
		");
	}

	public function down()
	{
		$this->db->exec("
            ALTER TABLE users
            DROP COLUMN avatar
        ");
	}
}
