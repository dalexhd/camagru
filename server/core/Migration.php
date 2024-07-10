<?php

namespace core;

class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function run()
    {
        $migrations = $this->getMigrations();
        foreach ($migrations as $migration) {
            require_once '../migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $instance->up();
        }
    }

    private function getMigrations()
    {
        return array_diff(scandir('../migrations'), ['.', '..']);
    }
}
