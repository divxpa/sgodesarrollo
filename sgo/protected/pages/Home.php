<?php

class Home extends PageBaseSP{

	public function onLoad($param){
            //if (!isset($_SESSION)) session_start(); 

		parent::onLoad($param);


		if(!$this->IsPostBack){
			//si es rol de relaciones institucionales lo mando al calendario
			if($this->Session->get("usr_sgo_idRol") == 6){
				$this->Response->Redirect("?page=Prensa.Home");
			}

		}

	}

}

?>