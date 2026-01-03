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
		$this->render('user/profile', ['message' => 'Hello, World!'], 'User Profile Page');
	}

	public function settings()
	{
		$this->render('user/settings', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function accountSettings()
	{
		if ($this->isPost()) {
			$this->validateCSRF('user/settings/account');
			try {
				$toUpdate = [
					"email" => $this->getPostData('email'),
					"nickname" => $this->getPostData('nickname'),
					"name" => $this->getPostData('name'),
					"notifications_enabled" => $this->getPostData('notifications_enabled') ? 1 : 0,
				];

				if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == false) {
					$toUpdate["avatar"] = $this->File->upload($_FILES['avatar'], 'img/profiles');
					if ($this->Session->has('user_avatar')) {
						$this->File->removeIfExists($this->Session->get('user_avatar'));
					}
				}

				if ($this->userModel->update($this->userId(), $toUpdate)) {
					$this->Session->set('user_email', $toUpdate['email']);
					$this->Session->set('user_nickname', $toUpdate['nickname']);
					$this->Session->set('user_name', $toUpdate['name']);
					$this->Session->set('user_notifications_enabled', (bool) $toUpdate["notifications_enabled"]);

					if (isset($toUpdate["avatar"])) {
						$this->Session->set('user_avatar', $this->Url->asset($toUpdate["avatar"]));
					} elseif (!$this->Session->has('user_avatar')) {
						$this->Session->set('user_avatar', $this->Url->asset(User::DEFAULT_AVATAR));
					}
					$this->flash('success', 'Your account has been updated successfully');
				}
			} catch (\Throwable $th) {
				$this->flash('error', $th->getMessage());
			}
		}
		$this->render('user/settings/account', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function securitySettings()
	{
		if ($this->isPost()) {
			$this->validateCSRF('user/settings/security');
			try {
				$password = $this->getPostData('password');
				$confirmPassword = $this->getPostData('confirm_password');
				$currentPassword = $this->getPostData('current_password');

				$user = $this->userModel->findByEmail($this->Session->get('user_email'));

				if (!Security::isValidPassword($password)) {
					$this->flash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
				} else if ($password != $confirmPassword) {
					$this->flash('error', 'Passwords do not match');
				} else if ($user && !Security::verifyPassword($currentPassword, $user['password'])) {
					$this->flash('error', 'Old password does not match');
				} else {
					if ($this->userModel->update($user['id'], ['password' => Security::hashPassword($password)])) {
						$this->flash('success', 'The password has been updated');
					}
				}
			} catch (\Throwable $th) {
				$this->flash('error', $th->getMessage());
			}
		}

		$this->render('user/settings/security', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function view()
	{
		$this->render('user/view', ['message' => 'Hello, World!'], 'User View Page');
	}
}
