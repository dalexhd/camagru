<?php

namespace app\models;

use core\Model;
use PDO;

class User extends Model
{
	protected $table = 'users';

	public function findByEmail($email)
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

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


	public function create($name, $nickname, $email, $password)
	{
		$stmt = $this->db->prepare("INSERT INTO {$this->table} (name, nickname, email, password) VALUES (:name, :nickname, :email, :password)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':nickname', $nickname);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		return $this->db->lastInsertId();
	}
}
