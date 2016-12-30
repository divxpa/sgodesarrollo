<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Tipo de Obra";
				$this->Refresh($id);
			}
			
		}

	}

	public function Refresh($idTipoObra){
		$finder = TipoObraRecord::finder();
		$tipoObra = $finder->findByPk($idTipoObra);
		$this->txtDescripcion->Text = $tipoObra->Descripcion;
	}

	public function cvTipoObra_OnServerValidate($sender, $param)
	{
		$descripcion = strtoupper($this->txtDescripcion->Text);
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Descripcion like :descripcion ';
		$criteria->Parameters[':descripcion'] = $descripcion;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdTipoObra <> :idtipoobra';
			$criteria->Parameters[':idtipoobra'] = $id;
		}

		$finder = TipoObraRecord::finder();
		$tipo = $finder->find($criteria);

		if (is_object($tipo)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.TipoObra.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = TipoObraRecord::finder();
				$tipo = $finder->findByPk($id);
			}
			else{
				$tipo = new TipoObraRecord();
			}

			$tipo->Descripcion = mb_strtoupper($this->txtDescripcion->Text, 'utf-8');

			try{
				$tipo->save();
				$this->Response->Redirect("?page=Admin.TipoObra.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>