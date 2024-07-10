<?php

class CreateUsersTable extends Migration {
    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;";
        $this->db->exec($sql);
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS users;";
        $this->db->exec($sql);
    }
}
