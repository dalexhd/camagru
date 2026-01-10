<?php

namespace core\helpers;

/**
 * UrlHelper class
 * 
 * Helper for generating and managing URLs.
 * Works closely with the Router to generate named routes.
 * This is how we avoid hardcoding URLs everywhere.
 */
class UrlHelper
{
	private $baseUrl;
	private \core\Router $router;

	public function __construct(\core\Router $router)
	{
		$baseUrl = getenv('BASE_URL');
		if (!$baseUrl) {
			$this->baseUrl = '';
		} else {
			$this->baseUrl = rtrim($baseUrl, '/');
		}
		$this->router = $router;
	}

	/**
	 * Get the base URL
	 * 
	 * We can define the base URL in the .env file, or we can use the current URL.
	 * 
	 * @return string
	 */
	public function getBaseUrl()
	{
		if (!empty($this->baseUrl)) {
			return $this->baseUrl;
		}

		// Little check to see if the user is using HTTPS.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		return $protocol . $domainName;
	}

	/**
	 * Generate a link from a route name
	 * 
	 * Takes a route name (like 'home') and returns the ful URL.
	 * 
	 * @param string $name
	 * @param array $params
	 * @return string
	 */
	public function link($name, $params = [])
	{
		$url = $this->router->generate($name, $params);
		return ($this->baseUrl ?? '') . $url;
	}

	/**
	 * Generate an absolute link
	 * 
	 * Returns the full URL including protocol and domain.
	 * Useful for emails or external links.
	 * 
	 * @param string $name
	 * @param array $params
	 * @return string
	 */
	public function absoluteLink($name, $params = [])
	{
		$path = $this->link($name, $params);
		// In case the path already contains the protocol, we return it as is.
		// This is why we check if === 0. This means that the first occurrence of http is at the beginning of the string.
		if (strpos($path, 'http') === 0) {
			return $path;
		}
		return $this->getBaseUrl() . $path;
	}

	/**
	 * Check if a route is active
	 * 
	 * Returns true if the current URL matches the route name.
	 * Great for highliting navigation menu items.
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function isActive($name)
	{
		$currentUrl = $_SERVER['REQUEST_URI'];
		$generatedUrl = $this->router->generate($name);
		return $currentUrl === $generatedUrl;
	}

	/**
	 * Get the name of the active route
	 * 
	 * Trys to find which named route matches the current URL.
	 * 
	 * @return string|null
	 */
	public function getActiveName()
	{
		$currentUrl = $_SERVER['REQUEST_URI'];
		foreach ($this->router->getRoutes() as $route) {
			$generatedUrl = $this->router->generate($route->name);
			if ($currentUrl === $generatedUrl) {
				return $route->name;
			}
		}
		return null;
	}

	/**
	 * Get URL for an asset
	 * 
	 * Appends the path to the base URL.
	 * 
	 * @param string $path
	 * @return string
	 */
	public function asset($path)
	{
		return $this->baseUrl . '/' . $path;
	}

	/**
	 * Redirect to a named route
	 * 
	 * Sends a Location header and exits.
	 * 
	 * @param string $name
	 * @param array $params
	 * @return void
	 */
	public function redirect($name, $params = [])
	{
		$url = $this->router->generate($name, $params);
		header("Location: {$this->baseUrl}{$url}");
		exit;
	}

	/**
	 * Redirect to a specific URL
	 * 
	 * Sends a Location header and exits.
	 * 
	 * @param string $url
	 * @return void
	 */
	public function redirectToUrl($url)
	{
		header("Location: {$url}");
		exit;
	}
}
