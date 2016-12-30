<?php
class UpdateProyecto extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Proyecto de inversión";
				$this->Refresh($id);
			}

		}

	}

	public function Refresh($id){
		$finder = SolicitudProyectoDetalleRecord::finder();
		$solicitudProyectoDetalle = $finder->findByPk($id);
		$this->txtLocalizacion->Text = $solicitudProyectoDetalle->Localizacion;
		$this->txtProyecto->Text = $solicitudProyectoDetalle->Proyecto;
		$this->txtDescripcion->Text = $solicitudProyectoDetalle->Descripcion;
		$this->txtMontoEstimado->Text = $solicitudProyectoDetalle->MontoEstimado;
		$this->ddlMoneda->SelectedValue = $solicitudProyectoDetalle->Moneda;

		if(!is_null($solicitudProyectoDetalle->FechaEstimacionCosto)){
			$fecha = explode("-", $solicitudProyectoDetalle->FechaEstimacionCosto);
			$this->dtpFechaEstimacionCosto->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}

		$this->ddlEstado->SelectedValue = $solicitudProyectoDetalle->Estado;
		$this->ddlPrioridad->SelectedValue = $solicitudProyectoDetalle->Prioridad;
		$this->txtObservaciones->Text = $solicitudProyectoDetalle->Observaciones;
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$ids = $this->Request["ids"];			

			if(!is_null($id)){
				$finder = SolicitudProyectoDetalleRecord::finder();
				$solicitudProyectoDetalle = $finder->findByPk($id);
			}
			else{
				$solicitudProyectoDetalle = new SolicitudProyectoDetalleRecord();
				$solicitudProyectoDetalle->IdSolicitudProyecto = $ids;
			}

			$solicitudProyectoDetalle->Localizacion = mb_strtoupper($this->txtLocalizacion->Text, 'utf-8');
			$solicitudProyectoDetalle->Proyecto = mb_strtoupper($this->txtProyecto->Text, 'utf-8');
			$solicitudProyectoDetalle->Descripcion = $this->txtDescripcion->Text;
			$solicitudProyectoDetalle->MontoEstimado = $this->txtMontoEstimado->Text;
			$solicitudProyectoDetalle->Moneda = $this->ddlMoneda->SelectedValue;

			if($this->dtpFechaEstimacionCosto->Text!=""){
				$fecha = explode("/", $this->dtpFechaEstimacionCosto->Text);
				$solicitudProyectoDetalle->FechaEstimacionCosto = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$solicitudProyectoDetalle->FechaEstimacionCosto = null;
			}

			$solicitudProyectoDetalle->Estado = $this->ddlEstado->SelectedValue;
			$solicitudProyectoDetalle->Prioridad = $this->ddlPrioridad->SelectedValue;
			$solicitudProyectoDetalle->Observaciones = $this->txtObservaciones->Text;

			try{
				$solicitudProyectoDetalle->save();
				$this->Response->Redirect("?page=Obra.SolicitudProyecto.Update&id=$ids");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ids = $this->Request["ids"];
		$this->Response->Redirect("?page=Obra.SolicitudProyecto.Update&id=$ids");
	}

}
?>	