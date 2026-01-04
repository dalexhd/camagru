<?php

namespace core;

use PDO;

/**
 * Database class
 * 
 * This class is used to handle database operations.
 * It follows the Singleton pattern, so we only have one connection.
 * We use PDO because it's secure and supports prepared statements.
 */
class Database
{
    private static $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE');
            $this->pdo = new PDO($dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get the database instance
     * 
     * If the instance doesn't exist, we create it.
     * If it does, we return it.
     * Simple as that.
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection
     * 
     * Returns the raw PDO object so we can run querys.
     * 
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
}
