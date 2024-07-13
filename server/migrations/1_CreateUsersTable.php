<?php

use core\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down()
    {
        $this->db->exec("DROP TABLE IF EXISTS users");
    }
}
