<?php

namespace core;

use core\helpers\UrlHelper;
use core\Session;
use core\View;
use core\Response;

class Controller
{
    protected $Url;
    protected $View;
    protected $Session;
    protected $Response;

    public function __construct($router)
    {
        $this->Url = new UrlHelper($router);
        $this->View = new View($router);
        $this->Response = new Response();
        $this->Session = new Session();
    }

    public function loadModel($model)
    {
        $model = 'app\models\\' . $model;
        return new $model();
    }
}
