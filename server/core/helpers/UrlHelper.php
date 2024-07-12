<?php

namespace core\helpers;

class UrlHelper
{
	private $baseUrl;
	private $router;

	public function __construct($router)
	{
		$baseUrl = getenv('BASE_URL');
		if (!$baseUrl) {
			$baseUrl = '';
		} else {
			$this->baseUrl = $baseUrl . substr($baseUrl, -1) == '/' ? '' : '/';
		}
		$this->router = $router;
	}

	public function link($name, $params = [])
	{
		$url = $this->router->generate($name, $params);
		return $this->baseUrl . $url;
	}

	public function isActive($name)
	{
		$currentUrl = $_SERVER['REQUEST_URI'];
		$generatedUrl = $this->router->generate($name);
		return $currentUrl === $generatedUrl;
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
}
