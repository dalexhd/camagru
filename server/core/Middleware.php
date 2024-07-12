<?php

namespace core;

class Middleware
{
	protected $name;

	public function __construct()
	{
		$this->name = get_class($this);
	}

	public function handle()
	{
		return true;
	}
}
