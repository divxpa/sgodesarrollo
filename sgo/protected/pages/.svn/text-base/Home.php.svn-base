<?php

class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			//si es rol de relaciones institucionales lo mando al calendario
			if($this->Session["SPIdRol"]==6){
				$this->Response->Redirect("?page=Prensa.Home");
			}

		}

	}

}

?>