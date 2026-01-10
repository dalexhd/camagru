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
 * The base controller that all other controllers extend.
 * It provides common utilities like loading models, rendering views, and handling sessions.
 * Think of it as the toolbox for your logic.
 */
class Controller
{
    protected helpers\UrlHelper $Url;
    protected View $View;
    protected Session $Session;
    protected Response $Response;
    protected File $File;

    /**
     * Constructor
     * 
     * Sets up all the helpers we need.
     * We initialize View, Response, Session, etc. so they're ready to use in any controller.
     */
    public function __construct($router)
    {
        $this->Url = new UrlHelper($router);
        $this->View = new View($router);
        $this->Response = new Response();
        $this->Session = new Session();
        $this->File = new File();
    }

    /**
     * Check if request is POST
     * 
     * Simple helper to see if the user submitted a form.
     * 
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get POST data
     * 
     * Retrieves data from $_POST.
     * If key is null, returns everything.
     * If key doesn't exist, returns default.
     * Safely handles missing keys.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getPostData($key = null, $default = null)
    {
        if ($key === null)
            return $_POST;
        return $_POST[$key] ?? $default;
    }

    /**
     * Set a flash message
     * 
     * Pass-through to Session::setFlash.
     * Shows a message to the user on the next page load.
     * 
     * @param string $type
     * @param string $message
     * @return void
     */
    protected function flash($type, $message)
    {
        $this->Session->setFlash($type, $message);
    }

    /**
     * Redirect to a route
     * 
     * Uses the UrlHelper to find the route by name and redirect the user.
     * Stops execution immediately after redirecting.
     * 
     * @param string $name
     * @param array $params
     * @return void
     */
    protected function redirect($name, $params = [])
    {
        $this->Url->redirect($name, $params);
    }

    /**
     * Render a view
     * 
     * Passes data to the View class and renders the template.
     * 
     * @param string $view
     * @param array $data
     * @param string $title
     * @return void
     */
    protected function render($view, $data = [], $title = null)
    {
        $this->View->render($view, $data, $title);
    }

    /**
     * Get current user ID
     * 
     * Helper to get the logged-in user's ID from session.
     * 
     * @return int|null
     */
    protected function userId()
    {
        return $this->Session->get('user_id');
    }

    /**
     * Get current user nickname
     * 
     * Helper to get the logged-in user's nickname from session.
     * 
     * @return string|null
     */
    protected function userNickname()
    {
        return $this->Session->get('user_nickname');
    }
}
