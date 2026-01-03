<?php

namespace core;

use core\helpers\HtmlHelper;
use core\helpers\UrlHelper;
use core\Session;

/**
 * View class
 * 
 * This class is used to render views. Of course, inspired by the View class from cakephp.
 */
class View
{
	public $layout = 'layouts/default'; // Default layout
	public $Html;
	public $Url;
	public $Session;

	public $title;
	public $name;
	public $view;

	/**
	 * View constructor.
	 * 
	 * The main goal of this constructor is to allow the view to use helpers and session.
	 * We simplify for example:
	 * - $_SESSION['user'] to $this->Session->get('user').
	 * - Setting the page title to $this->setTitle('...').
	 * - <a href="..."> to $this->Url->link('...'). Since we use named routes, in case we change the route, we only need to change it in one place.
	 * 
	 * @param mixed $router
	 */
	public function __construct($router)
	{
		$this->Html = new HtmlHelper();
		$this->Url = new UrlHelper($router);
		$this->Session = new Session();
		$this->name = getenv('SERVER_APP_NAME') ?: 'App';
		$this->title = $this->name;
	}

	public function setTitle($title)
	{
		$this->title = $this->title . ' - ' . $title;
	}

	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	public function render($view, $data = [], $title = null)
	{
		extract($data);
		ob_start();
		$this->view = $view;
		require "../templates/{$view}.php";
		$content = ob_get_clean();
		require "../templates/{$this->layout}.php";
	}

	public function element($component, $data = [])
	{
		extract($data);
		require "../templates/element/{$component}.php";
	}

	public function partial($component, $data = [])
	{
		extract($data);
		require "../templates/partials/{$component}.php";
	}
}
