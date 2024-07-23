<?php

namespace core;

use core\helpers\HtmlHelper;
use core\helpers\UrlHelper;
use core\Session;

class View
{
	protected $layout = 'layouts/default'; // Default layout
	protected $Html;
	protected $Url;
	protected $Session;

	private $title;
	private $name;
	private $view;

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
