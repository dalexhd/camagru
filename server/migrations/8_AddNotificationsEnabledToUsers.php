<?php

use core\Migration;

class AddNotificationsEnabledToUsers extends Migration
{
    public function up()
    {
        $this->db->exec("ALTER TABLE users ADD COLUMN notifications_enabled TINYINT(1) DEFAULT 1");
    }

    public function down()
    {
        $this->db->exec("ALTER TABLE users DROP COLUMN notifications_enabled");
    }
}
