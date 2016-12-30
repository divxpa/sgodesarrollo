<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Fuente de Financiamiento";
				$this->Refresh($id);
			}
			
		}

	}

	public function Refresh($idFuenteFinanciamiento){
		$finder = FuenteFinanciamientoRecord::finder();
		$fuenteFinanciamiento = $finder->findByPk($idFuenteFinanciamiento);
		$this->txtDescripcion->Text = $fuenteFinanciamiento->Descripcion;
		$this->txtCodigo->Text = $fuenteFinanciamiento->CodigoFufi;
	}

	public function cvCodigo_OnServerValidate($sender, $param)
	{
		$codigo = $this->txtCodigo->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'CodigoFufi like :codigo ';
		$criteria->Parameters[':codigo'] = $codigo;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdFuenteFinanciamiento <> :idfuentefinanciamiento';
			$criteria->Parameters[':idfuentefinanciamiento'] = $id;
		}

		$finder = FuenteFinanciamientoRecord::finder();
		$fufi = $finder->find($criteria);

		if (is_object($fufi)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.FuenteFinanciamiento.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = FuenteFinanciamientoRecord::finder();
				$fufi = $finder->findByPk($id);
			}
			else{
				$fufi = new FuenteFinanciamientoRecord();
			}

			$fufi->Descripcion = mb_strtoupper($this->txtDescripcion->Text, 'utf-8');
			$fufi->CodigoFufi = $this->txtCodigo->Text;

			try{
				$fufi->save();
				$this->Response->Redirect("?page=Admin.FuenteFinanciamiento.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>