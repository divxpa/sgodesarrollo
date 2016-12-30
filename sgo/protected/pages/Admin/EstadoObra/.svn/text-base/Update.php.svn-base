<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Estado de Obra";
				$this->Refresh($id);
			}
			
		}

	}

	public function Refresh($idEstado){
		$finder = EstadoObraRecord::finder();
		$estado = $finder->findByPk($idEstado);
		$this->txtDescripcion->Text = $estado->Descripcion;
	}

	public function cvEstadoObra_OnServerValidate($sender, $param)
	{
		$descripcion = strtoupper($this->txtDescripcion->Text);
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Descripcion like :descripcion ';
		$criteria->Parameters[':descripcion'] = $descripcion;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdEstadoObra <> :idestadoobra';
			$criteria->Parameters[':idestadoobra'] = $id;
		}

		$finder = EstadoObraRecord::finder();
		$estado = $finder->find($criteria);

		if (is_object($estado)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.EstadoObra.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = EstadoObraRecord::finder();
				$estado = $finder->findByPk($id);
			}
			else{
				$estado = new EstadoObraRecord();
			}

			$estado->Descripcion = mb_strtoupper($this->txtDescripcion->Text, 'utf-8');

			try{
				$estado->save();
				$this->Response->Redirect("?page=Admin.EstadoObra.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>