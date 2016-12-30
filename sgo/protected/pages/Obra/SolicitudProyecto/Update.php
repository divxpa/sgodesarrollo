<?php
class Update extends PageBaseSP{

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
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = SolicitudProyectoRecord::finder();
		$solicitudProyecto = $finder->findByPk($id);

		if(!$this->ValidarProyectoOrganismo($idOrganismo, $id)){
			$this->Response->Redirect("?page=Obra.SolicitudProyecto.Home");
		}

		$fecha = explode("-",$solicitudProyecto->FechaSolicitud);
		$this->dtpFechaSolicitud->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtSolicitante->Text = $solicitudProyecto->Solicitante;
		$this->txtDepartamento->Text = $solicitudProyecto->DepartamentoSolicitante;
		$this->txtAutoridadSolicitante->Text = $solicitudProyecto->AutoridadSolicitante;
		$this->txtDomicilioSolicitante->Text = $solicitudProyecto->DomicilioSolicitante;
		$this->txtTelefonoSolicitante->Text = $solicitudProyecto->TelefonoSolicitante;
		$this->txtEmailSolicitante->Text = $solicitudProyecto->EmailSolicitante;
		$this->txtReferente->Text = $solicitudProyecto->Referente;
		$this->txtDniReferente->Text = $solicitudProyecto->DniReferente;
		$this->txtCargoReferente->Text = $solicitudProyecto->CargoReferente;
		$this->txtDomicilioReferente->Text = $solicitudProyecto->DomicilioReferente;
		$this->txtTelefonoReferente->Text = $solicitudProyecto->TelefonoReferente;
		$this->txtEmailReferente->Text = $solicitudProyecto->EmailReferente;

		$data = $this->CreateDataSource("ObraPeer","DetalleSolicitud", $id);
		$this->dgProyectos->DataSource = $data;
		$this->dgProyectos->dataBind();
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Obra.SolicitudProyecto.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$this->saveSolicitud();
			$this->Response->Redirect("?page=Obra.SolicitudProyecto.Home");
		}

	}

	public function saveSolicitud(){
		$id = $this->Request["id"];
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");

		if(!is_null($id)){
			$finder = SolicitudProyectoRecord::finder();
			$solicitudProyecto = $finder->findByPk($id);
		}
		else{
			$solicitudProyecto = new SolicitudProyectoRecord();
			$solicitudProyecto->IdOrganismo = $idOrganismo;
		}

		$fecha = explode("/", $this->dtpFechaSolicitud->Text);
		$solicitudProyecto->FechaSolicitud = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		$solicitudProyecto->Solicitante = mb_strtoupper($this->txtSolicitante->Text, 'utf-8');
		$solicitudProyecto->DepartamentoSolicitante = $this->txtDepartamento->Text;
		$solicitudProyecto->AutoridadSolicitante = $this->txtAutoridadSolicitante->Text;
		$solicitudProyecto->DomicilioSolicitante = $this->txtDomicilioSolicitante->Text;
		$solicitudProyecto->TelefonoSolicitante = $this->txtTelefonoSolicitante->Text;
		$solicitudProyecto->EmailSolicitante = $this->txtEmailSolicitante->Text;
		$solicitudProyecto->Referente = mb_strtoupper($this->txtReferente->Text, 'utf-8');
		$solicitudProyecto->DniReferente = $this->txtDniReferente->Text;
		$solicitudProyecto->CargoReferente = $this->txtCargoReferente->Text;
		$solicitudProyecto->DomicilioReferente = $this->txtDomicilioReferente->Text;
		$solicitudProyecto->TelefonoReferente = $this->txtTelefonoReferente->Text;
		$solicitudProyecto->EmailReferente = $this->txtEmailReferente->Text;

		try{
			$solicitudProyecto->save();
			return $solicitudProyecto->IdSolicitudProyecto;	
		}
		catch(exception $e){
			$this->Log($e->getMessage(),true);
			return null;
		}

	}

	public function btnAgregarProyecto_OnClick($sender, $param){

		if($this->IsValid){
			$id = $this->saveSolicitud();
			$this->Response->Redirect("?page=Obra.SolicitudProyecto.UpdateProyecto&ids=".$id);
		}

	}

}

?>