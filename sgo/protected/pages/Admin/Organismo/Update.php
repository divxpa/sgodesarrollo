<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Organismo";
				$this->Refresh($id);
			}
			else{
				$codigo = $this->CreateDataSource("OrganismoPeer", "SiguienteCodigo");
				$this->txtCodigo->Text = $codigo[0]["Codigo"];
			}
			
		}

	}

	public function Refresh($idOrganismo){
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($idOrganismo);
		$this->txtNombre->Text = $organismo->Nombre;
		$this->txtCodigo->Text = $organismo->PrefijoCodigo;
		$this->chkComitente->Checked = $organismo->Comitente;
	}

	public function cvCodigo_OnServerValidate($sender, $param)
	{
		$codigo = $this->txtCodigo->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'PrefijoCodigo like :codigo ';
		$criteria->Parameters[':codigo'] = $codigo;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdOrganismo <> :idorganismo';
			$criteria->Parameters[':idorganismo'] = $id;
		}

		$finder = OrganismoRecord::finder();
		$organismo = $finder->find($criteria);

		if (is_object($organismo)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.Organismo.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = OrganismoRecord::finder();
				$organismo = $finder->findByPk($id);
			}
			else{
				$organismo = new OrganismoRecord();
			}

			$organismo->Nombre = mb_strtoupper($this->txtNombre->Text, 'utf-8');
			$organismo->PrefijoCodigo = $this->txtCodigo->Text;
			$organismo->Comitente = $this->chkComitente->Checked;

			try{
				$organismo->save();
				$this->Response->Redirect("?page=Admin.Organismo.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>