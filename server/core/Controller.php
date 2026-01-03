<?php

namespace core;

use core\helpers\UrlHelper;
use core\Session;
use core\View;
use core\Response;
use core\File;

/**
 * Controller class
 * 
 */
class Controller
{
    protected helpers\UrlHelper $Url;
    protected View $View;
    protected Session $Session;
    protected Response $Response;
    protected File $File;

    public function __construct($router)
    {
        $this->Url = new UrlHelper($router);
        $this->View = new View($router);
        $this->Response = new Response();
        $this->Session = new Session();
        $this->File = new File();
    }

    public function loadModel($model)
    {
        $model = 'app\models\\' . $model;
        return new $model();
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getPostData($key = null, $default = null)
    {
        if ($key === null)
            return $_POST;
        return $_POST[$key] ?? $default;
    }

    protected function validateCSRF($viewOnFailure = null, $data = [])
    {
        if (!\core\Security::verifyCSRFToken($this->getPostData('csrf_token') ?? '')) {
            $this->flash('error', 'Security token mismatch. Please try again.');
            if ($viewOnFailure) {
                $this->render($viewOnFailure, $data);
                exit;
            }
            return false;
        }
        return true;
    }

    protected function flash($type, $message)
    {
        $this->Session->setFlash($type, $message);
    }

    protected function redirect($name, $params = [])
    {
        $this->Url->redirect($name, $params);
    }

    protected function render($view, $data = [], $title = null)
    {
        $this->View->render($view, $data, $title);
    }

    protected function userId()
    {
        return $this->Session->get('user_id');
    }

    protected function userNickname()
    {
        return $this->Session->get('user_nickname');
    }
}
