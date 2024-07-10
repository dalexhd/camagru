<?php

namespace core;

use core\helpers\UrlHelper;
use core\Session;
use core\View;

class Controller
{
    protected $Url;
    protected $View;
    protected $Session;

    public function __construct($router)
    {
        $this->Url = new UrlHelper($router);
        $this->View = new View($router);
        $this->Session = new Session();
    }
}
