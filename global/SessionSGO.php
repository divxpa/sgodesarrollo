<?php

class SessionSGO {
	
	const SESSION_KEY = 'k0387D_h8eas23';

	public function __construct() 
	{
		self::init();
	}

	public function init() 
	{
		if (!isset($_SESSION)) session_start();

		if (!isset($_SESSION[self::SESSION_KEY])) $_SESSION[self::SESSION_KEY] = [];
	}

	public function start()
	{
		self::init();
	}	

	public function end() 
	{
		session_destroy();
	}

	public function close()
	{
		self::end();
	}

	public function get($name) 
	{
		return isset($_SESSION[self::SESSION_KEY][$name]) ? $_SESSION[self::SESSION_KEY][$name] : false;
	}

	public function set($name, $value) 
	{
		return $_SESSION[self::SESSION_KEY][$name] = $value;
	}

	public function exists($name) 
	{
		return isset($_SESSION[self::SESSION_KEY][$name]);
	}

	public function delete($name) 
	{
		if (isset($_SESSION[self::SESSION_KEY][$name])) unset($_SESSION[self::SESSION_KEY][$name]);
	}

	public function remove($name)
	{
		self::delete($name);
	}

	public function getId()
	{
		return isset($_SESSION) ? session_id() : false;
	}

}