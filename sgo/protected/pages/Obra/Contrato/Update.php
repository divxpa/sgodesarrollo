<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$this->LoadDataRelated($idObra);
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Contrato";
				$this->Refresh($id, $idObra);
			}

		}

	}

	public function LoadDataRelated($idObra){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $idObra);
		$this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion . " - " .$localidades[0]["Localidades"];

		/*$proveedores = $this->CreateDataSource("ProveedorPeer","ProveedoresSelect");
		$this->ddlProveedor->DataSource = $proveedores;
		$this->ddlProveedor->dataBind();*/
		
		//si el estado de obra es a iniciar, no es obligatorio fecha de inicio/ fin y plazo
		if($obra->IdEstadoObra==5){
			$this->rfvFechaInicio->Enabled = false;
			$this->rfvFechaFinalizacion->Enabled = false;
			$this->rfvPlazoEjecucion->Enabled = false;
			$this->lblFechaInicio->CssClass = 
			$this->lblPlazoEjecucion->CssClass = 
			$this->lblFechaFinalizacion->CssClass = "label";
		}

		$this->hlkVolver->NavigateUrl .= "&id=$idObra";
	}

	public function Refresh($idContrato, $idObra){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra, true)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = ContratoRecord::finder();
		$contrato = $finder->findByPk($idContrato);

		$this->txtNumero->Text = $contrato->Numero;
		/*$this->ddlProveedor->SelectedValue = $contrato->IdProveedor;
		$this->ddlProveedor->Enabled = false;*/
		$this->txtIdProveedor->Text = $contrato->IdProveedor;
		$finder2 = ProveedorRecord::finder();
		$proveedor = $finder2->findByPk($contrato->IdProveedor);
		$this->acpProveedor->Text = $proveedor->RazonSocial." (".$proveedor->Cuit.")";
		$this->acpProveedor->Enabled = false;

		$fecha = explode("-",$contrato->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtMonto->Text = $contrato->Monto;
		$fecha = explode("-",$contrato->FechaBaseMonto);
		$this->dtpFechaBaseMonto->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtNLAutorizacion->Text = $contrato->NormaLegalAutorizacion;
		$this->txtNLAdjudicacion->Text = $contrato->NormaLegalAdjudicacion;
		
		if(!is_null($contrato->FechaInicio)){
			$fecha = explode("-",$contrato->FechaInicio);
			$this->dtpFechaInicio->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}

		if(!is_null($contrato->FechaFinalizacion)){
			$fecha = explode("-",$contrato->FechaFinalizacion);
			$this->dtpFechaFinalizacion->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}
		
		$this->txtPlazoEjecucion->Text = $contrato->PlazoEjecucion;
		$this->pnlModificacionContrato->Visible = true;

		$this->btnAgregarAlteracion->NavigateUrl .= "&idc=".$idContrato."&ido=".$idObra;
		$data = $this->CreateDataSource("ContratoPeer","AlteracionesByContrato", $idContrato);
		$this->dgAlteraciones->DataSource = $data;
		$this->dgAlteraciones->dataBind();

		if(count($data)){
			$this->lblAlteraciones->Visible = false;
		}

		$this->btnAgregarRedeterminacion->NavigateUrl .= "&idc=".$idContrato."&ido=".$idObra;
		$data = $this->CreateDataSource("ContratoPeer","RedeterminacionesByContrato", $idContrato);
		$this->dgRedeterminaciones->DataSource = $data;
		$this->dgRedeterminaciones->dataBind();

		if(count($data)){
			$this->lblRedeterminaciones->Visible = false;
		}

		$this->btnAgregarAmpliacion->NavigateUrl .= "&idc=".$idContrato."&ido=".$idObra;
		$this->btnAgregarAmpliacion->Visible = $this->ValidarComitente($idOrganismo, $idObra);
		$data = $this->CreateDataSource("ContratoPeer","AmpliacionesByContrato", $idContrato, $idOrganismo);
		$this->dgAmpliacionesPlazo->DataSource = $data;
		$this->dgAmpliacionesPlazo->dataBind();

		if(count($data)){
			$this->lblAmpliacionesPlazo->Visible = false;
		}

		$this->btnAgregarRecepcion->NavigateUrl .= "&idc=".$idContrato."&ido=".$idObra;
		$this->btnAgregarRecepcion->Visible = $this->ValidarComitente($idOrganismo, $idObra);
		$data = $this->CreateDataSource("ContratoPeer","RecepcionesByContrato", $idContrato, $idOrganismo);
		$this->dgRecepcionesContrato->DataSource = $data;
		$this->dgRecepcionesContrato->dataBind();

		if(count($data)){
			$this->lblRecepcionesContrato->Visible = false;
		}
		
		$this->btnAgregarOrdenTrabajo->NavigateUrl .= "&idc=".$idContrato."&ido=".$idObra;
		$this->btnAgregarOrdenTrabajo->Visible = $this->ValidarComitente($idOrganismo, $idObra);
		$data = $this->CreateDataSource("ContratoPeer","OrdenesTrabajoByContrato", $idContrato, $idOrganismo);
		$this->dgOrdenesTrabajo->DataSource = $data;
		$this->dgOrdenesTrabajo->dataBind();

		if(count($data)){
			$this->lblOrdenesTrabajo->Visible = false;
		}
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$id = $this->Request["ido"];
		$this->Response->Redirect("?page=Obra.Contrato.Home&id=$id");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$recalcula = false;
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];

			if(!is_null($id)){
				$finder = ContratoRecord::finder();
				$contrato = $finder->findByPk($id);

				if($contrato->Monto!=$this->txtMonto->Text){
					$recalcula = true;
				}

			}
			else{
				$contrato = new ContratoRecord();
				$contrato->IdObra = $idObra;
			}

			//$contrato->IdProveedor = $this->ddlProveedor->SelectedValue;
			$contrato->IdProveedor = $this->txtIdProveedor->Text;
			$contrato->Numero = $this->txtNumero->Text;
			$fecha = explode("/", $this->dtpFecha->Text);
			$contrato->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$contrato->Monto = $this->txtMonto->Text;
			$fecha = explode("/", $this->dtpFechaBaseMonto->Text);
			$contrato->FechaBaseMonto = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$contrato->NormaLegalAutorizacion = $this->txtNLAutorizacion->Text;
			$contrato->NormaLegalAdjudicacion = $this->txtNLAdjudicacion->Text;
			
			if($this->dtpFechaInicio->Text!=""){
				$fecha = explode("/", $this->dtpFechaInicio->Text);
				$contrato->FechaInicio = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$contrato->FechaInicio = null;
			}

			if($this->dtpFechaFinalizacion->Text!=""){
				$fecha = explode("/", $this->dtpFechaFinalizacion->Text);
				$contrato->FechaFinalizacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$contrato->FechaFinalizacion = null;
			}

			if($this->txtPlazoEjecucion->Text!=""){
				$contrato->PlazoEjecucion = $this->txtPlazoEjecucion->Text;
			}
			else{
				$contrato->PlazoEjecucion = null;
			}

			try{
				$contrato->save();

				/*if($recalcula){
					$this->ExecuteSql(ContratoPeer::RecalcularCertificacionesByContrato($contrato->IdContrato));
				}*/

				$this->Response->Redirect("?page=Obra.Contrato.Home&id=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function dtpFechaInicio_OnTextChanged($sender, $param){
		$this->RecalcularFechaFinalizacion();
	}

	public function RecalcularFechaFinalizacion(){
		$fechaInicio = $this->dtpFechaInicio->Text;
		$plazoEjecucion = $this->txtPlazoEjecucion->Text;

		if($fechaInicio!="" and $plazoEjecucion!=""){
			$plazoEjecucion+=1;
			$fecha = explode("/", $fechaInicio);
			$fecha = new DateTime($fecha[2].'-'.$fecha[1].'-'.$fecha[0]);
			$fecha->add(new DateInterval('P'.$plazoEjecucion.'D'));
			$this->dtpFechaFinalizacion->Text = $fecha->format('d/m/Y');
		}

	}

	public function acpProveedor_OnSuggest($sender, $param)
	{
		$this->txtIdProveedor->Text = "";
		$token = $param->Token;
		$data = $this->CreateDataSource("ProveedorPeer","ProveedoresAutocomplete", $token);

		if (count($data) == 0) {

			if(strlen($token)!=13){
				$data = array(
					array(
						'IdProveedor' => '',
						'Descripcion' => 'No se encontraron coincidencias.<br /><br />Ingrese CUIT completo para consultar el padrón de la Provincia.'
					)
				);
			}
			else{
				$data = $this->GetProveedorWS($token);

				if(count($data) == 0){
					$data = array(
						array(
							'IdProveedor' => '',
							'Descripcion' => 'No se encontraron coincidencias.<br /><br />El CUIT es incorrecto o no está registrado como proveedor en el padrón de la Provincia.'
						)
					);
				}

			}

		}

		$sender->DataSource = $data;
		$sender->dataBind();
	}

	public function acpProveedor_OnSuggestionSelected($sender, $param)
	{
		$idProveedor = $sender->Suggestions->DataKeys[$param->SelectedIndex];

		if($idProveedor!="-1"){
			$this->txtIdProveedor->Text = $sender->Suggestions->DataKeys[$param->SelectedIndex];
		}
		else{
			$this->txtIdProveedor->Text = $this->SaveProveedorWS();
		}

	}

}

?>