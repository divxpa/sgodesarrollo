<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Proveedor";
				$this->Refresh($id);
			}
			
		}

	}

	public function LoadDataRelated(){
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = LocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$this->ddlLocalidad->DataSource = $localidades;
		$this->ddlLocalidad->dataBind();
	}

	public function Refresh($idProveedor){
		$finder = ProveedorRecord::finder();
		$proveedor = $finder->findByPk($idProveedor);
		$this->txtCuit->Text = $proveedor->Cuit;
		$this->txtRazonSocial->Text = $proveedor->RazonSocial;
		$this->txtDomicilio->Text = $proveedor->Domicilio;
		$this->ddlLocalidad->Text = $proveedor->IdLocalidad;
		$this->txtRepresentanteTecnico->Text = $proveedor->RepresentanteTecnico;
		$this->txtTelefono->Text = $proveedor->Telefono;
		$this->txtEmail->Text = $proveedor->Email;

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdProveedor = :idProveedor ';
		$criteria->Parameters[':idProveedor'] = $idProveedor;
		$finder = UteRecord::finder();
		$ute = $finder->findAll($criteria);

		if(count($ute)){
			$this->chkEsUte->Checked = true;
			$this->pnlEsUte->Display = "Dynamic";
			$i = 1;

			foreach ($ute as $u) {
				$finder = ProveedorRecord::finder();
				$proveedorSocio = $finder->findByPk($u->IdProveedorSocio);
				$control = "txtIdProveedor".$i;
				$this->$control->Text = $u->IdProveedorSocio;
				$control = "acpProveedor".$i;
				$this->$control->Text = $proveedorSocio->Cuit." ".$proveedorSocio->RazonSocial;
				$control = "txtPorcentaje".$i;
				$this->$control->Text = $u->PorcentajeSocio;
				$i++;	
			}

		} 

	}

	public function cvCuit_OnServerValidate($sender, $param)
	{
		$cuit = $this->txtCuit->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Cuit like :cuit ';
		$criteria->Parameters[':cuit'] = $cuit;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdProveedor <> :idproveedor';
			$criteria->Parameters[':idproveedor'] = $id;
		}

		$finder = ProveedorRecord::finder();
		$proveedor = $finder->find($criteria);

		if (is_object($proveedor)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function cvRazonSocial_OnServerValidate($sender, $param)
	{
		$razonSocial = mb_strtoupper($this->txtRazonSocial->Text, 'utf-8');
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'RazonSocial like :razonSocial ';
		$criteria->Parameters[':razonSocial'] = $razonSocial;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdProveedor <> :idproveedor';
			$criteria->Parameters[':idproveedor'] = $id;
		}

		$finder = ProveedorRecord::finder();
		$proveedor = $finder->find($criteria);

		if (is_object($proveedor)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.Proveedor.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = ProveedorRecord::finder();
				$proveedor = $finder->findByPk($id);
			}
			else{
				$proveedor = new ProveedorRecord();
			}

			$proveedor->Cuit = $this->txtCuit->Text;
			$proveedor->RazonSocial = mb_strtoupper($this->txtRazonSocial->Text, 'utf-8');
			$proveedor->Domicilio = mb_strtoupper($this->txtDomicilio->Text, 'utf-8');

			if($this->ddlLocalidad->SelectedValue!="" and $this->ddlLocalidad->SelectedValue!="0"){
				$proveedor->IdLocalidad = $this->ddlLocalidad->SelectedValue;
			}
			else{
				$proveedor->IdLocalidad = null;
			}

			$proveedor->RepresentanteTecnico = mb_strtoupper($this->txtRepresentanteTecnico->Text, 'utf-8');
			$proveedor->Telefono = $this->txtTelefono->Text;
			$proveedor->Email = $this->txtEmail->Text;

			try{
				$proveedor->save();

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdProveedor = :idProveedor ';
				$criteria->Parameters[':idProveedor'] = $proveedor->IdProveedor;
				$finder = UteRecord::finder();
				$ute = $finder->findAll($criteria);

				foreach ($ute as $u) {
					$u->delete();
				}

				if($this->txtIdProveedor1->Text!=""){
					$ute = new UteRecord();
					$ute->IdProveedor = $proveedor->IdProveedor;
					$ute->IdProveedorSocio = $this->txtIdProveedor1->Text;
					$ute->PorcentajeSocio = $this->txtPorcentaje1->Text;
					$ute->save();
				}

				if($this->txtIdProveedor2->Text!=""){
					$ute = new UteRecord();
					$ute->IdProveedor = $proveedor->IdProveedor;
					$ute->IdProveedorSocio = $this->txtIdProveedor2->Text;
					$ute->PorcentajeSocio = $this->txtPorcentaje2->Text;
					$ute->save();
				}

				if($this->txtIdProveedor3->Text!=""){
					$ute = new UteRecord();
					$ute->IdProveedor = $proveedor->IdProveedor;
					$ute->IdProveedorSocio = $this->txtIdProveedor3->Text;
					$ute->PorcentajeSocio = $this->txtPorcentaje3->Text;
					$ute->save();
				}

				if($this->txtIdProveedor4->Text!=""){
					$ute = new UteRecord();
					$ute->IdProveedor = $proveedor->IdProveedor;
					$ute->IdProveedorSocio = $this->txtIdProveedor4->Text;
					$ute->PorcentajeSocio = $this->txtPorcentaje4->Text;
					$ute->save();
				}

				if($this->txtIdProveedor5->Text!=""){
					$ute = new UteRecord();
					$ute->IdProveedor = $proveedor->IdProveedor;
					$ute->IdProveedorSocio = $this->txtIdProveedor5->Text;
					$ute->PorcentajeSocio = $this->txtPorcentaje5->Text;
					$ute->save();
				}

				$this->Response->Redirect("?page=Admin.Proveedor.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function chkEsUte_OnCheckedChanged($sender, $param)
	{
		
		if($this->chkEsUte->Checked){
			$this->pnlEsUte->Display = "Dynamic";
		}
		else{
			$this->pnlEsUte->Display = "None";
		}


	}

	public function acpProveedor1_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor1->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresNoUteAutocomplete", $token);

		if (count($data) == 0) {

			$data = array(
				array(
					'IdProveedor' => '',
					'Descripcion' => 'No se encontraron coincidencias.'
				)
			);

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor1_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		$this->txtIdProveedor1->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
	}

	public function acpProveedor2_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor2->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresNoUteAutocomplete", $token);

		if (count($data) == 0) {

			$data = array(
				array(
					'IdProveedor' => '',
					'Descripcion' => 'No se encontraron coincidencias.'
				)
			);

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor2_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		$this->txtIdProveedor2->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
	}

	public function acpProveedor3_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor3->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresNoUteAutocomplete", $token);

		if (count($data) == 0) {

			$data = array(
				array(
					'IdProveedor' => '',
					'Descripcion' => 'No se encontraron coincidencias.'
				)
			);

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor3_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		$this->txtIdProveedor3->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
	}

	public function acpProveedor4_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor4->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresNoUteAutocomplete", $token);

		if (count($data) == 0) {

			$data = array(
				array(
					'IdProveedor' => '',
					'Descripcion' => 'No se encontraron coincidencias.'
				)
			);

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor4_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		$this->txtIdProveedor4->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
	}

	public function acpProveedor5_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor5->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresNoUteAutocomplete", $token);

		if (count($data) == 0) {

			$data = array(
				array(
					'IdProveedor' => '',
					'Descripcion' => 'No se encontraron coincidencias.'
				)
			);

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor5_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		$this->txtIdProveedor5->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
	}

}

?>