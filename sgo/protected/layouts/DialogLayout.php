<?php
/**
 *
 *
 */
class DialogLayout extends TTemplateControl {

	public function onLoad($param){
		parent::onLoad($param);
		$this->ensureChildControls();
	}

}
?>
