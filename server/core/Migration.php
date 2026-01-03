<?php

namespace core;

/**
 * Migration class
 * 
 * This class is used to run migrations.
 * It's a simple class that runs all the migrations in the migrations folder. It's based on the modern cakephp migrations system.
 * So we support full down and up migrations.
 * 
 */
class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Run all migrations
     * 
     * We iterate over all the migrations and run them.
     * Up method is called for each migration (defined in the migration file)
     * 
     * @return void
     */
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

    /**
     * Get all migrations
     * 
     * We get all the migrations in the migrations folder.
     * 
     * @return array
     */
    private function getMigrations()
    {
        return array_diff(scandir('../migrations'), ['.', '..']);
    }
}
