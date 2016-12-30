<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$this->LoadDataRelated($idObra, $idContrato);
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Recepción de Contrato";
				$this->Refresh($id, $idObra, $idContrato);
			}

		}

	}

	public function LoadDataRelated($idObra, $idContrato){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $idObra);
		$this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion . " - " .$localidades[0]["Localidades"];
		$finder = ContratoRecord::finder();
		$contrato = $finder->findByPk($idContrato);
		$finder = ProveedorRecord::finder();
		$proveedor = $finder->findByPk($contrato->IdProveedor);
		$this->lblContrato->Text = $contrato->Numero . " - " . $proveedor->Cuit . " " . $proveedor->RazonSocial;
	}

	public function Refresh($idRecepcionContrato, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = RecepcionContratoRecord::finder();
		$recepcionContrato = $finder->findByPk($idRecepcionContrato);
		$this->txtNLAprobacion->Text = $recepcionContrato->NormaLegalAprobacion;
		$fecha = explode("-", $recepcionContrato->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->ddlTipo->SelectedValue = $recepcionContrato->ProvisoriaDefinitiva;
		$this->ddlParcialTotal->SelectedValue = $recepcionContrato->ParcialTotal;
		$this->txtPorcentajeRecepcion->Text = $recepcionContrato->PorcentajeRecepcion;		
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idc&ido=$ido");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];

			if(!is_null($id)){
				$finder = RecepcionContratoRecord::finder();
				$recepcionContrato = $finder->findByPk($id);
			}
			else{
				$recepcionContrato = new RecepcionContratoRecord();
				$recepcionContrato->IdContrato = $idContrato;
			}

			$fecha = explode("/", $this->dtpFecha->Text);
			$recepcionContrato->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$recepcionContrato->NormaLegalAprobacion = $this->txtNLAprobacion->Text;
			$recepcionContrato->ProvisoriaDefinitiva = $this->ddlTipo->SelectedValue;
			$recepcionContrato->ParcialTotal = $this->ddlParcialTotal->SelectedValue;
			$recepcionContrato->PorcentajeRecepcion = $this->txtPorcentajeRecepcion->Text;	

			try{
				$recepcionContrato->save();
				$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function ddlParcialTotal_OnSelectedIndexChanged($sender, $param){

		if($this->ddlParcialTotal->SelectedValue==1){
			$this->txtPorcentajeRecepcion->Text = "100.00";
			$this->txtPorcentajeRecepcion->Enabled = false;
		}
		else{
			$this->txtPorcentajeRecepcion->Enabled = true;
		}

	}

}

?>