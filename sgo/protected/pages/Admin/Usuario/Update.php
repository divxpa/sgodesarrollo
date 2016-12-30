<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Usuario";
				$this->Refresh($id);
			}
			
		}

	}

	public function LoadDataRelated(){
		$idUsuario = $this->Session->get("usr_id");
		$finder = UsuarioRecord::finder();
		$usuario = $finder->findByPk($idUsuario);
		
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = OrganismoRecord::finder();
		$criteria2 = new TActiveRecordCriteria;
		$criteria2->OrdersBy['Nombre'] = 'asc';

		if($usuario->IdRol!=1){
			$criteria->Condition = 'IdOrganismo = :idorganismo ';
			$criteria->Parameters[':idorganismo'] = $usuario->IdOrganismo;
			$criteria2->Condition = 'IdRol not in (1, 6)';
		}
		else{
			$this->ddlOrganismo->PromptValue="0";
			$this->ddlOrganismo->PromptText="Seleccione";
		}

		$organismos = $finder->findAll($criteria);
		$this->ddlOrganismo->DataSource = $organismos;
		$this->ddlOrganismo->dataBind();

		$finder = RolRecord::finder();
		$roles = $finder->findAll($criteria2);
		$this->ddlRol->DataSource = $roles;
		$this->ddlRol->dataBind();
	}

	public function Refresh($idUsuario){
		$idUsuarioAdmin = $this->Session->get("usr_id");
		$finder = UsuarioRecord::finder();
		$usuario = $finder->findByPk($idUsuario);
		$usuarioAdmin = $finder->findByPk($idUsuarioAdmin);

		if($usuarioAdmin->IdRol!=1){

			if($usuarioAdmin->IdOrganismo!=$usuario->IdOrganismo){
				$this->Response->Redirect("?page=Usuario.Home");
			}

		}

		$this->ddlOrganismo->SelectedValue = $usuario->IdOrganismo;
		$this->txtApellidoNombre->Text = $usuario->ApellidoNombre;
		$this->txtUsername->Text = $usuario->Username;
		$this->ddlRol->SelectedValue = $usuario->IdRol;
		$this->chkActivo->Checked = $usuario->Activo;
		$this->txtPassword->Visible = false;
		$this->labelContrasena->Visible = true;
		$this->requiredContrasena->Enabled = false;
	}

	public function cvUsername_OnServerValidate($sender, $param)
	{
		$username = $this->txtUsername->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Username like :username ';
		$criteria->Parameters[':username'] = $username;
		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdUsuario <> :username';
			$criteria->Parameters[':username'] = $id;
		}

		$finder = UsuarioRecord::finder();
		$usuario = $finder->find($criteria);

		if (is_object($usuario)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.Usuario.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = UsuarioRecord::finder();
				$usuario = $finder->findByPk($id);
			}
			else{
				$usuario = new UsuarioRecord();
				$usuario->Password= md5($this->txtPassword->Text);
			}

			$usuario->ApellidoNombre = mb_strtoupper($this->txtApellidoNombre->Text, 'utf-8');
			$usuario->Username = $this->txtUsername->Text;

			if($this->ddlOrganismo->SelectedValue!="" and $this->ddlOrganismo->SelectedValue!="0"){
				$usuario->IdOrganismo = $this->ddlOrganismo->SelectedValue;
			}
			else{
				$usuario->IdOrganismo = 0;
			}

			if($this->chkActivo->Checked){
				$usuario->Activo = 1;
			}
			else{
				$usuario->Activo = 0;
			}

			$usuario->IdRol = $this->ddlRol->SelectedValue;

			//$usuario->IdLocalidad = 1;
			//$usuario->Email = 'eee';
//F::pd($usuario);
//F::pd($usuario->save());
			try{
				$usuario->save();
				$this->Response->Redirect("?page=Admin.Usuario.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>