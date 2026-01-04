<?php

namespace app\models;

use core\Model;
use PDO;

class User extends Model
{
	protected $table = 'users';
	const DEFAULT_AVATAR = 'img/default_avatar.png';

	/**
	 * Find a user by email.
	 * 
	 * @param string $email
	 * @return array
	 */
	public function findByEmail($email)
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Find a user by id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function findById($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Find a user by nickname.
	 * 
	 * @param string $nickname
	 * @return array
	 */
	public function findByNickname($nickname)
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nickname = :nickname LIMIT 1");
		$stmt->bindParam(':nickname', $nickname);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Find a user by verification token.
	 * 
	 * @param string $token
	 * @return array
	 */
	public function findByVerificationToken($token)
	{
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE verification_token = :token LIMIT 1");
		$stmt->bindParam(':token', $token);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Update a user.
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
	 * Create a new user.
	 * 
	 * @param string $name
	 * @param string $nickname
	 * @param string $email
	 * @param string $password
	 * @param string $verificationToken
	 * @return bool|string
	 */
	public function create($name, $nickname, $email, $password, $verificationToken)
	{
		$stmt = $this->db->prepare("INSERT INTO {$this->table} (name, nickname, email, password, verification_token, verified, notifications_enabled) VALUES (:name, :nickname, :email, :password, :verification_token, 0, 1)");
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':nickname', $nickname);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':verification_token', $verificationToken);
		$stmt->execute();
		return $this->db->lastInsertId();
	}
}
