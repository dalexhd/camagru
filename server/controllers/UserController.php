<?php

use core\Controller;

class UserController extends Controller
{
	public function profile()
	{
		$this->View->render('user/profile', ['message' => 'Hello, World!'], 'User Profile Page');
	}

	public function settings()
	{
		$this->View->render('user/settings', ['message' => 'Hello, World!'], 'User Settings Page');
	}

	public function view()
	{
		$this->View->render('user/view', ['message' => 'Hello, World!'], 'User View Page');
	}
}
