<?php

use core\Database;

require_once 'core/Database.php';
require_once 'core/Migration.php';

class MigrationRunner
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance()->getConnection();
		$this->createMigrationsTable();
	}

	private function createMigrationsTable()
	{
		$this->db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                run_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
	}

	private function getRunMigrations()
	{
		$stmt = $this->db->query("SELECT migration FROM migrations");
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	private function logMigration($migration)
	{
		$stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
		$stmt->bindParam(':migration', $migration);
		$stmt->execute();
	}

	private function deleteMigration($migration)
	{
		$stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = :migration");
		$stmt->bindParam(':migration', $migration);
		$stmt->execute();
	}

	private function loadMigrations($directory)
	{
		$migrations = [];
		foreach (glob($directory . '/*.php') as $filename) {
			require_once $filename;
			$className = basename(preg_replace('/[0-9]+_/', '', basename($filename)), '.php');
			if (class_exists($className)) {
				$migrations[$className] = new $className();
			}
		}
		return $migrations;
	}

	public function runMigrations()
	{
		$runMigrations = $this->getRunMigrations();
		$migrations = $this->loadMigrations('migrations');

		$count = 0;
		foreach ($migrations as $className => $migration) {
			if (!in_array($className, $runMigrations)) {
				echo "Running migration: $className\n";
				$migration->up();
				$this->logMigration($className);
				$count++;
			}
		}
		if ($count === 0) {
			echo "No new migrations to run. All migrations are up to date.\n";
		}
	}

	public function rollbackMigrations()
	{
		$runMigrations = $this->getRunMigrations();
		$migrations = $this->loadMigrations('migrations');
		$migrationToRollback = array_pop($runMigrations);
		echo "Rolling back migration: $migrationToRollback\n";
		$migrations[$migrationToRollback]->down();
		$this->deleteMigration($migrationToRollback);
	}
}

$runner = new MigrationRunner();
$action = $argv[1] ?? null;

if ($action === 'migrate') {
	$runner->runMigrations();
} elseif ($action === 'rollback') {
	$runner->rollbackMigrations();
} else {
	echo "Invalid action. Use 'migrate' or 'rollback'.\n";
}
