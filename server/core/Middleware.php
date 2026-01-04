<?php

namespace core;

/**
 * Middleware class
 * 
 * Base class for all middleware.
 * Middleware allows us to intercept requests before they reach the controller.
 * Useful for auth checks, logging, etc.
 */
class Middleware
{
	protected $name;

	/**
	 * Constructor
	 * 
	 * Just sets the name of the middleware.
	 */
	public function __construct()
	{
		$this->name = get_class($this);
	}

	/**
	 * Handle the request
	 * 
	 * This is where the logic goes.
	 * Return true to let the request pas, or false/redirect to stop it.
	 * 
	 * @return bool|void
	 */
	public function handle()
	{
		return true;
	}
}
