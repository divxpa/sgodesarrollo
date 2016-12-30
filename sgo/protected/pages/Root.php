<?php


class Root extends SessionPage {

	public function onLoad($param)
	{
		parent::onLoad($param);
		header("Location: $this->GlobalPath"); exit;
	}

}
