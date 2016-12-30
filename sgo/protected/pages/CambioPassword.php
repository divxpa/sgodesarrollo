<?php
class CambioPassword extends PageBaseSP{
	public function onLoad($param){
		parent::onLoad($param);
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{
		
		if($this->IsValid){
			$id = $this->Session->get("usr_id");
			$finder = UsuarioRecord::finder();
			$usuario = $finder->findByPk($id);
			$usuario->Password = md5($this->txtNuevaContrasena->Text);
			
			try{
				$usuario->save();
				$this->Response->Redirect("?page=Logout");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function cvContrasenaAcual_OnServerValidate($sender, $param)
	{
		$contrasenaActual = md5($this->txtContrasenaActual->Text);
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Password = :contrasenaActual ';
		$criteria->Parameters[':contrasenaActual'] = $contrasenaActual;
		$id = $this->Session->get("usr_id");
		
		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdUsuario = :idusuario';
			$criteria->Parameters[':idusuario'] = $id;
		}

		$finder = UsuarioRecord::finder();
		$usuario = $finder->find($criteria);
		
		if (!is_object($usuario)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

}

?>