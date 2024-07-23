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

	public function setHeader($key, $value)
	{
		header("$key: $value");
		return $this;
	}
}
