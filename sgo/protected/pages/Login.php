<?php



class Login extends PageBaseSP{

	public function onLoad($param){$srv = $_SERVER["SERVER_NAME"];
		parent::onLoad($param);
		header("Location: $this->GlobalPath");
		exit;
	}

	public function cvUser_OnServerValidate($sender, $param){
		$finder = UsuarioRecord::finder();
		$usuario = $finder->findBy_Username_And_Password_And_Activo($this->txtUsername->Text,md5($this->txtPassword->Text),1);

		if(is_object($usuario)){
			$param->IsValid = true;
		}
		else{
			$param->IsValid = false;
		}

	}

	public function btnIngresar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$finder = UsuarioRecord::finder();
			$usuario = $finder->findBy_Username_And_Password_And_Activo($this->txtUsername->Text,md5($this->txtPassword->Text),1);
			$this->Session->set("usr_id", $usuario->IdUsuario);
			$this->Session->set("usr_nombreApellido", $usuario->ApellidoNombre);
			$this->Session->set("usr_username", $usuario->Username);
			$this->Session->set("usr_sgo_idOrganismo", $usuario->IdOrganismo);
			$this->Session->set("usr_sgo_idRol", $usuario->IdRol);

			if(!is_null($usuario->IdOrganismo)){
				$finder = OrganismoRecord::finder();
				$organismo = $finder->findByPk($usuario->IdOrganismo);
				$this->Session->set("usr_sgo_nombreOrganismo", $organismo->Nombre);
			}
			else{
				$this->Session->set("usr_sgo_nombreOrganismo", "");
			}

			$this->SaveIngreso($usuario->IdUsuario, true);
			$this->Response->Redirect("?page=Home");
		}

	}

}

?>