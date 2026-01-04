<?php

namespace core;

use core\Database;
use PDO;

/**
 * Model class
 * 
 * This class is used to interact with the database.
 * It's a simple class that extends the PDO class. Here we define useful methods for database interaction.
 * 
 */
class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Paginate the model.
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function paginate($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update a record by id.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        if (empty($data)) {
            return false;
        }

        // Dynamically build the SET part of the query
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "`$key` = :$key"; // Using backticks to prevent SQL keyword conflicts
        }
        $setQuery = implode(', ', $setClauses);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setQuery WHERE id = :id");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute(); // Returns true on success, false on failure
    }

    /**
     * Find a record by id.
     * 
     * @param int $id
     * @return array
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a record by id.
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
