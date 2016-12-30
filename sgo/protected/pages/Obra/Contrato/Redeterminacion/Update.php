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
				$this->lblAccion->Text = "Modificar Redeterminación de Precios";
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

	public function Refresh($idRedeterminacion, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra, true)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = RedeterminacionRecord::finder();
		$redeterminacion = $finder->findByPk($idRedeterminacion);

		$fecha = explode("-", $redeterminacion->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtNLAprobacion->Text = $redeterminacion->NormaLegalAprobacion;
		$this->txtImporte->Text = $redeterminacion->Importe;
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
				$finder = RedeterminacionRecord::finder();
				$redeterminacion = $finder->findByPk($id);
			}
			else{
				$redeterminacion = new RedeterminacionRecord();
				$redeterminacion->IdContrato = $idContrato;
			}

			$fecha = explode("/", $this->dtpFecha->Text);
			$redeterminacion->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$redeterminacion->NormaLegalAprobacion = $this->txtNLAprobacion->Text;
			$redeterminacion->Importe = $this->txtImporte->Text;

			try{
				$redeterminacion->save();
				$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>