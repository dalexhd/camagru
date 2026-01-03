<?php

namespace core\helpers;

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

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		return $protocol . $domainName;
	}

	public function link($name, $params = [])
	{
		$url = $this->router->generate($name, $params);
		return ($this->baseUrl ?? '') . $url;
	}

	public function absoluteLink($name, $params = [])
	{
		$path = $this->link($name, $params);
		if (strpos($path, 'http') === 0) {
			return $path;
		}
		return $this->getBaseUrl() . $path;
	}

	/**
	 * Check if the current URL matches the generated URL
	 * 
	 * @param mixed $name
	 * @return bool
	 */
	public function isActive($name)
	{
		$currentUrl = $_SERVER['REQUEST_URI'];
		$generatedUrl = $this->router->generate($name);
		return $currentUrl === $generatedUrl;
	}

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

	public function asset($path)
	{
		return $this->baseUrl . '/' . $path;
	}

	public function redirect($name, $params = [])
	{
		$url = $this->router->generate($name, $params);
		header("Location: {$this->baseUrl}{$url}");
		exit;
	}

	public function redirectToUrl($url)
	{
		header("Location: {$url}");
		exit;
	}
}
