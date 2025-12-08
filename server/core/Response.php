<?php

namespace core;

class Response
{
	protected $response;

	public function __construct()
	{
		$this->response = [];
	}

	public function setResponse($response)
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

	public function status($code)
	{
		http_response_code($code);
		return $this;
	}

	public function setHeader($key, $value)
	{
		header("$key: $value");
		return $this;
	}
}
