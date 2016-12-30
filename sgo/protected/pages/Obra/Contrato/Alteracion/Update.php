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
				$this->lblAccion->Text = "Modificar Alteración de Contrato";
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

	public function Refresh($idAlteracion, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra, true)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = AlteracionRecord::finder();
		$alteracion = $finder->findByPk($idAlteracion);

		$fecha = explode("-", $alteracion->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtNLAprobacion->Text = $alteracion->NormaLegalAprobacion;
		$this->ddlTipo->SelectedValue = $alteracion->AdicionalDeductivo;
		$this->txtImporte->Text = $alteracion->Importe;
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
			$recalcula = false;

			if(!is_null($id)){
				$finder = AlteracionRecord::finder();
				$alteracion = $finder->findByPk($id);

				if($alteracion->Importe!=$this->txtImporte->Text or $alteracion->AdicionalDeductivo!=$this->ddlTipo->SelectedValue){
					$recalcula = true;
				}

			}
			else{
				$alteracion = new AlteracionRecord();
				$alteracion->IdContrato = $idContrato;
				$recalcula = true;
			}

			$fecha = explode("/", $this->dtpFecha->Text);
			$alteracion->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$alteracion->NormaLegalAprobacion = $this->txtNLAprobacion->Text;
			$alteracion->AdicionalDeductivo = $this->ddlTipo->SelectedValue;
			$alteracion->Importe = $this->txtImporte->Text;

			try{
				$alteracion->save();

				/*if($recalcula){
					$this->ExecuteSql(ContratoPeer::RecalcularCertificacionesByContrato($alteracion->IdContrato));
				}*/

				$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>