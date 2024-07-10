<?php

use core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->View->render('home', ['message' => 'Hello, World!'], 'Home Page');
    }
}
