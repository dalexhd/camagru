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

	public function create($name, $email, $password)
	{
		$stmt = $this->db->prepare("INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		return $this->db->lastInsertId();
	}
}
