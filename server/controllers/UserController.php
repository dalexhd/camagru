<?php

use core\Controller;
use app\models\User;
use core\Security;

class UserController extends Controller
{
	private $userModel;

	public function __construct($router)
	{
		parent::__construct($router);
		$this->userModel = new User();
	}

	public function profile()
	{
		$this->View->render('user/profile', ['message' => 'Hello, World!'], 'User Profile Page');
	}

	public function settings()
	{
		$this->View->render('user/settings', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function accountSettings()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			try {
				$toUpdate = [
					"email" => $_POST['email'],
					"nickname" => $_POST['nickname'],
					"name" => $_POST['name'],
					"notifications_enabled" => isset($_POST['notifications_enabled']) ? 1 : 0,
				];
				if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == false) {
					$toUpdate["avatar"] = $this->File->upload($_FILES['avatar'], 'img/profiles');
					if ($this->Session->has('user_avatar')) {
						$this->File->removeIfExists($this->Session->get('user_avatar'));
					}
				}
				$updated = $this->userModel->update($this->Session->get('user_id'), $toUpdate);
				if ($updated) {
					$this->Session->set('user_email', $_POST['email']);
					$this->Session->set('user_nickname', $_POST['nickname']);
					$this->Session->set('user_name', $_POST['name']);
					$this->Session->set('user_notifications_enabled', (bool) $toUpdate["notifications_enabled"]);
					if (isset($toUpdate["avatar"])) {
						$this->Session->set('user_avatar', $this->Url->asset($toUpdate["avatar"]));
					}
					$this->Session->setFlash('success', 'Your account has been updated successfully');
				}
			} catch (\Throwable $th) {
				$this->Session->setFlash('error', $th->getMessage());
			}
		}
		$this->View->render('user/settings/account', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function securitySettings()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			try {
				$password = $_POST['password'];
				$confirmPassword = $_POST['confirm_password'];
				$currentPassword = $_POST['current_password'];

				$user = $this->userModel->findByEmail($this->Session->get('user_email'));
				// Validate password complexity
				if (!Security::isValidPassword($password)) {
					$this->Session->setFlash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
				} else if ($password != $confirmPassword) {
					$this->Session->setFlash('error', 'Passwords do not match');
				} else if ($user && !Security::verifyPassword($currentPassword, $user['password'])) {
					$this->Session->setFlash('error', 'Old password does not match');
				} else {
					$password = Security::hashPassword($password);
					if ($this->userModel->update($user['id'], compact('password'))) {
						$this->Session->setFlash('success', 'The password has been updated');
					}
				}
			} catch (\Throwable $th) {
				$this->Session->setFlash('error', $th->getMessage());
			}
		}

		$this->View->render('user/settings/security', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function view()
	{
		$this->View->render('user/view', ['message' => 'Hello, World!'], 'User View Page');
	}
}
