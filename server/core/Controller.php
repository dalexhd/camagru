<?php

class Controller {
    protected $layout = 'layouts/default'; // Default layout

    public function __construct() {
        // Any initialization logic for controllers can go here
    }

    protected function setLayout($layout) {
        $this->layout = $layout;
    }

    protected function render($view, $data = [], $title = null) {
        extract($data);
        ob_start();
        require "../app/templates/{$view}.php";
        $content = ob_get_clean();
        require "../app/templates/{$this->layout}.php";
    }
}
