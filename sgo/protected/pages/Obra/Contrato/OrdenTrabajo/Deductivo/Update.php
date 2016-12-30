<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$idOrdenTrabajo = $this->Request["idot"];
			$this->LoadDataRelated($idObra, $idContrato, $idOrdenTrabajo);
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Deductivo de Orden de Trabajo";
				$this->Refresh($id, $idObra, $idContrato, $idOrdenTrabajo);
			}

		}

	}

	public function LoadDataRelated($idObra, $idContrato, $idOrdenTrabajo){
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
		$finder = OrdenTrabajoRecord::finder();
		$ordenTrabajo = $finder->findByPk($idOrdenTrabajo);
		$this->lblOrdenTrabajo->Text = "Nro. ".$ordenTrabajo->Numero;
	}

	public function Refresh($idOrdenTrabajoDeductivo, $idObra, $idContrato, $idOrdenTrabajo){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if($obra->IdOrganismo!=$idOrganismo){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = OrdenTrabajoDeductivoRecord::finder();
		$deductivo = $finder->findByPk($idOrdenTrabajoDeductivo);

		$fecha = explode("-", $deductivo->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtNLAprobacion->Text = $deductivo->NormaLegalAprobacion;
		$this->txtImporte->Text = $deductivo->Importe;
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		$idot = $this->Request["ido"];
		$this->Response->Redirect("?page=Obra.Contrato.OrdenTrabajo.Update&id=$idot&idc=$idc&ido=$ido");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$idOrdenTrabajo = $this->Request["idot"];
			$recalcula = false;

			if(!is_null($id)){
				$finder = OrdenTrabajoDeductivoRecord::finder();
				$deductivo = $finder->findByPk($id);

				if($deductivo->Importe!=$this->txtImporte->Text){
					$recalcula = true;
				}

			}
			else{
				$deductivo = new OrdenTrabajoDeductivoRecord();
				$deductivo->IdOrdenTrabajo = $idOrdenTrabajo;
				$recalcula = true;
			}

			$fecha = explode("/", $this->dtpFecha->Text);
			$deductivo->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$deductivo->NormaLegalAprobacion = $this->txtNLAprobacion->Text;
			$deductivo->Importe = $this->txtImporte->Text;

			try{
				$deductivo->save();

				if($recalcula){
					$this->ExecuteSql(OrdenTrabajoPeer::RecalcularCertificacionesByOrdenTrabajo($idOrdenTrabajo));
				}

				$this->Response->Redirect("?page=Obra.Contrato.OrdenTrabajo.Update&id=$idOrdenTrabajo&idc=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>