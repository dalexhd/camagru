<?php

use core\Controller;

class HomeController extends Controller
{
    /**
     * Show the home page.
     * 
     * Just renders the main view. Nothing fancy here.
     * 
     * @return void
     */
    public function index()
    {
        $this->render('home', [], 'Home Page');
    }
}
