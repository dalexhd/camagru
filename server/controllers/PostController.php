<?php

use core\Controller;

class PostController extends Controller
{
	public function index()
	{
		$this->View->render('post/index', ['message' => 'Hello, World!'], 'Post Page');
	}

	public function create()
	{
		$this->View->render('post/create', ['message' => 'Hello, World!'], 'Post Create Page');
	}
}
