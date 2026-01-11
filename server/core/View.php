<?php

namespace core;

use core\helpers\HtmlHelper;
use core\helpers\UrlHelper;
use core\Session;
use core\Security;

/**
 * View class
 * 
 * Handles rendering the UI.
 * Combines templates, layouts, and data to produce HTML.
 * Also provides access to helpers like Html and Url.
 */
class View
{
	public string $layout = 'layouts/default'; // Default layout
	public HtmlHelper $Html;
	public UrlHelper $Url;
	public Session $Session;

	public string $title;
	public string $name;
	public string $view;

	/**
	 * View constructor.
	 * 
	 * The main goal of this constructor is to allow the view to use helpers and session.
	 * We simplify for example:
	 * - $_SESSION['user'] to $this->Session->get('user').
	 * - Setting the page title to $this->setTitle('...').
	 * - <a href="..."> to $this->Url->link('...'). Since we use named routes, in case we change the route, we only need to change it in one place .
	 * 
	 * @param mixed $router
	 */
	public function __construct($router)
	{
		$this->Html = new HtmlHelper();
		$this->Url = new UrlHelper($router);
		$this->Session = new Session();
		$this->name = getenv('SERVER_APP_NAME') ?: 'Camgru';
		$this->title = $this->name;
	}

	/**
	 * Set page title
	 * 
	 * Appends to the default title.
	 * Keeps things consistent across the site.
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title .= ' - ' . $title;
	}

	/**
	 * Set layout
	 * 
	 * Switch layouts on the fly.
	 * Useful for login pages or dashboards that look different.
	 * For now we dont use it
	 * 
	 * @param string $layout
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * Render the view
	 * 
	 * The big show!
	 * Extracts data variables so they are available in the template.
	 * Bufers output so we can capture the view content and inject it into the layout.
	 * 
	 * @param string $view
	 * @param array $data
	 * @param string $title
	 */
	public function render($view, $data = [], $title = null)
	{
		extract($data);
		ob_start();
		$this->view = $view;
		require "../templates/{$view}.php";
		// Content is the output of the view template. So we use it in the layout. We use clean to get the content without the layout.
		$content = ob_get_clean();
		require "../templates/{$this->layout}.php";
	}

	/**
	 * Render an element
	 * 
	 * Renders a small, reusable chunk of HTML.
	 * Like a sidebar, or a comment box.
	 * 
	 * @param string $component
	 * @param array $data
	 */
	public function element($component, $data = [])
	{
		extract($data);
		require "../templates/element/{$component}.php";
	}

	/**
	 * Render a partial
	 * 
	 * Similar to an element, but usually for larger sections.
	 * It holds basically the custom x-camgru-templates.
	 * 
	 * @param string $component
	 * @param array $data
	 */
	public function partial($component, $data = [])
	{
		extract($data);
		require "../templates/partials/{$component}.php";
	}

	/**
	 * Escape output for XSS protection
	 * 
	 * Escapes special characters to prevent XSS attacks when displaying user data.
	 * This should be used whenever displaying user-generated content in templates.
	 * 
	 * Example usage in templates:
	 * <?php echo $this->escape($userInput); ?>
	 * 
	 * @param mixed $output The data to escape
	 * @return string The escaped string safe for HTML output
	 */
	public function escape($output)
	{
		return Security::escapeOutput($output);
	}
}
