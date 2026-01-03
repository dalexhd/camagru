<?php

namespace core;

use PDO;

/**
 * Database class
 * 
 * This class is used to handle database operations.
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

    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
