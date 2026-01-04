<?php

use core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home', [], 'Home Page');
    }
}
