<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2012
 */
class SessionPage extends TPage{

	public $Session;
	public $GlobalPath;

	public function onLoad($param)
	{
//            echo session_id("597cuj6jo9ikuj3i3qnkdggkc3");
//            echo "<pre>  ssss"; print_r($_SESSION);die();
		parent::onLoad($param);

		$srv = $_SERVER["SERVER_NAME"];
		$this->GlobalPath = "http://" . ($srv == "localhost" ? $srv . preg_replace('/^\/([\w\d]+)(\/[\w\W]*)/', "/$1", $_SERVER["REQUEST_URI"]) : $srv);
		$this->Session = new SessionSGO();

	}

}
