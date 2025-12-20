<?php

use core\Migration;

class AddVerificationToUsers extends Migration
{
    public function up()
    {
        $this->db->exec("
            ALTER TABLE users
            ADD COLUMN verified BOOLEAN DEFAULT 0,
            ADD COLUMN verification_token VARCHAR(255) NULL
        ");

        // Set existing users to verified to avoid locking them out
        $this->db->exec("UPDATE users SET verified = 1 WHERE verified = 0");
    }

    public function down()
    {
        $this->db->exec("
            ALTER TABLE users
            DROP COLUMN verified,
            DROP COLUMN verification_token
        ");
    }
}
