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
				$this->lblAccion->Text = "Modificar Ampliación de Plazo de Contrato";
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

	public function Refresh($idContratoPlazo, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = ContratoPlazoRecord::finder();
		$contratoPlazo = $finder->findByPk($idContratoPlazo);
		$this->txtNLAprobacion->Text = $contratoPlazo->NormaLegalAprobacion;
		$this->txtCantidadDias->Text = $contratoPlazo->CantidadDias;
		$fecha = explode("-", $contratoPlazo->NuevaFechaFinalizacion);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
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
				$finder = ContratoPlazoRecord::finder();
				$contratoPlazo = $finder->findByPk($id);
			}
			else{
				$contratoPlazo = new ContratoPlazoRecord();
				$contratoPlazo->IdContrato = $idContrato;
			}

			$contratoPlazo->CantidadDias = $this->txtCantidadDias->Text;
			$fecha = explode("/", $this->dtpFecha->Text);
			$contratoPlazo->NuevaFechaFinalizacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$contratoPlazo->NormaLegalAprobacion = $this->txtNLAprobacion->Text;

			try{
				$contratoPlazo->save();
				$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function txtCantidadDias_OnTextChanged($sender, $param){
		$this->RecalcularFechaFinalizacion();
	}

	public function RecalcularFechaFinalizacion(){
		$id = $this->Request["id"];
		$idContrato = $this->Request["idc"]; 

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['NuevaFechaFinalizacion'] = 'desc';
		$criteria->Condition = 'IdContrato = :idcontrato ';
		$criteria->Parameters[':idcontrato'] = $idContrato;

		if(!is_null($id)){
			$criteria->Condition .= ' AND IdContratoPlazo <> :idcontratoplazo ';
			$criteria->Parameters[':idcontratoplazo'] = $id;
		}

		$finder = ContratoPlazoRecord::finder();
		$ultimoPlazo = $finder->find($criteria);

		if(is_object($ultimoPlazo)){
			$fecha = $ultimoPlazo->NuevaFechaFinalizacion;
		}
		else{
			$finder = ContratoRecord::finder();
			$contrato = $finder->findByPk($idContrato);
			$fecha = $contrato->FechaInicio;
		}

		$plazoEjecucion = $this->txtCantidadDias->Text;

		if($fecha!="" and $plazoEjecucion!=""){
			$plazoEjecucion+=1;
			$fecha = new DateTime($fecha);
			$fecha->add(new DateInterval('P'.$plazoEjecucion.'D'));
			$this->dtpFecha->Text = $fecha->format('d/m/Y');
		}

	}

}

?>