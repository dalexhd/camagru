<?php

use core\Controller;

class SearchController extends Controller
{
	public function index()
	{
		$this->View->render('search', [], 'Search Page');
	}
}
