<?php

namespace core;

/**
 * Response class
 * 
 * Inspired by the cakephp response class, this class is used to send responses to the client.
 * We can set the response, the status code, and the headers. All in same -> chainable methods.
 * So for example, we can do $response->setResponse($data)->status(200)->setHeader('Content-Type', 'application/json')->send();
 * 
 */
class Response
{
	protected array $response;

	public function __construct()
	{
		$this->response = [];
	}

	public function setResponse(array $response)
	{
		$this->response = $response;
		return $this;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function send()
	{
		echo json_encode($this->response);
	}

	public function status(int $code)
	{
		http_response_code($code);
		return $this;
	}

	public function setHeader(string $key, string $value)
	{
		header("$key: $value");
		return $this;
	}
}
