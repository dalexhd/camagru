<?php

use core\Controller;

class SearchController extends Controller
{
	public function index()
	{
		$this->View->render('search', ['message' => 'Hello, World!'], 'Search Page');
	}
}
