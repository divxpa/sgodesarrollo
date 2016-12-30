<?php
class UpdateAgil extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
			$codigo = $this->CreateDataSource("ObraPeer", "SiguienteCodigoObra", $idOrganismo);
			$this->txtCodigo->Text = $codigo[0]["Codigo"];
		}

	}

	public function LoadDataRelated(){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrganismo = :idorganismo OR Comitente=1 ';
		$criteria->Parameters[':idorganismo'] = $idOrganismo;
		$finder = OrganismoRecord::finder();
		$organismos = $finder->findAll($criteria);
		$this->ddlComitente->DataSource = $organismos;
		$this->ddlComitente->dataBind();
		$this->ddlComitente->SelectedValue = $idOrganismo;

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = LocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$this->ddlLocalidad1->DataSource = $localidades;
		$this->ddlLocalidad1->dataBind();
		$this->ddlLocalidad2->DataSource = $localidades;
		$this->ddlLocalidad2->dataBind();
		$this->ddlLocalidad3->DataSource = $localidades;
		$this->ddlLocalidad3->dataBind();
		$this->ddlLocalidad4->DataSource = $localidades;
		$this->ddlLocalidad4->dataBind();
		$this->ddlLocalidad5->DataSource = $localidades;
		$this->ddlLocalidad5->dataBind();
		$this->ddlLocalidad6->DataSource = $localidades;
		$this->ddlLocalidad6->dataBind();
		$this->ddlLocalidad7->DataSource = $localidades;
		$this->ddlLocalidad7->dataBind();
		$this->ddlLocalidad8->DataSource = $localidades;
		$this->ddlLocalidad8->dataBind();
		$this->ddlLocalidad9->DataSource = $localidades;
		$this->ddlLocalidad9->dataBind();
		$this->ddlLocalidad10->DataSource = $localidades;
		$this->ddlLocalidad10->dataBind();
		$this->ddlLocalidad11->DataSource = $localidades;
		$this->ddlLocalidad11->dataBind();
		$this->ddlLocalidad12->DataSource = $localidades;
		$this->ddlLocalidad12->dataBind();
		$this->ddlLocalidad13->DataSource = $localidades;
		$this->ddlLocalidad13->dataBind();
		$this->ddlLocalidad14->DataSource = $localidades;
		$this->ddlLocalidad14->dataBind();
		$this->ddlLocalidad15->DataSource = $localidades;
		$this->ddlLocalidad15->dataBind();
		$this->ddlLocalidad16->DataSource = $localidades;
		$this->ddlLocalidad16->dataBind();
		$this->ddlLocalidad17->DataSource = $localidades;
		$this->ddlLocalidad17->dataBind();
		$this->ddlLocalidad18->DataSource = $localidades;
		$this->ddlLocalidad18->dataBind();
		$this->ddlLocalidad19->DataSource = $localidades;
		$this->ddlLocalidad19->dataBind();
		$this->ddlLocalidad20->DataSource = $localidades;
		$this->ddlLocalidad20->dataBind();

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Descripcion'] = 'asc';
		$finder = EstadoObraRecord::finder();
		$estados = $finder->findAll($criteria);
		$this->ddlEstado->DataSource = $estados;
		$this->ddlEstado->dataBind();
	}

	public function cvCodigo_OnServerValidate($sender, $param)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$codigo = $this->txtCodigo->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrganismo = :idorganismo AND Codigo like :codigo ';
		$criteria->Parameters[':idorganismo'] = $idOrganismo;
		$criteria->Parameters[':codigo'] = $codigo;
		$finder = ObraRecord::finder();
		$obra = $finder->find($criteria);

		if (is_object($obra)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Obra.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
			$obra = new ObraRecord();
			$obra->IdOrganismo = $idOrganismo;
			$obra->Codigo = $this->txtCodigo->Text;
			$obra->Denominacion = mb_strtoupper($this->txtDenominacion->Text, 'utf-8');
			$obra->Expediente = null;
			$obra->IdComitente = $this->ddlComitente->SelectedValue;
			$obra->CreditoPresupuestarioAprobado = null;
			$obra->PresupuestoOficial = null;
			$obra->FechaPresupuestoOficial = null;
			$obra->Latitud = '';
			$obra->Longitud = '';
			$obra->IdTipoObra = null;
			$obra->CantidadBeneficiarios = null;

			if($this->dtpFechaInauguracion->Text!=""){
				$fecha = explode("/", $this->dtpFechaInauguracion->Text);
				$obra->FechaInauguracion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$obra->FechaInauguracion = null;
			}
			
			$obra->IdEstadoObra = $this->ddlEstado->SelectedValue;
			$obra->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
			$obra->MemoriaDescriptiva = mb_strtoupper($this->txtMemoriaDescriptiva->Text, 'utf-8');
			$obra->Agil = 1;

			try{
				$obra->save();

				$obraLocalidad = new ObraLocalidadRecord();
				$obraLocalidad->IdObra = $obra->IdObra;
				$obraLocalidad->IdLocalidad = $this->ddlLocalidad1->SelectedValue;
				$obraLocalidad->save();

				if($this->ddlLocalidad2->SelectedValue!="" and $this->ddlLocalidad2->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad2->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad3->SelectedValue!="" and $this->ddlLocalidad3->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad3->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad4->SelectedValue!="" and $this->ddlLocalidad4->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad4->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad5->SelectedValue!="" and $this->ddlLocalidad5->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad5->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad6->SelectedValue!="" and $this->ddlLocalidad6->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad6->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad7->SelectedValue!="" and $this->ddlLocalidad7->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad7->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad8->SelectedValue!="" and $this->ddlLocalidad8->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad8->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad9->SelectedValue!="" and $this->ddlLocalidad9->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad9->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad10->SelectedValue!="" and $this->ddlLocalidad10->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad10->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad11->SelectedValue!="" and $this->ddlLocalidad11->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad11->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad12->SelectedValue!="" and $this->ddlLocalidad12->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad12->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad13->SelectedValue!="" and $this->ddlLocalidad13->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad13->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad14->SelectedValue!="" and $this->ddlLocalidad14->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad14->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad15->SelectedValue!="" and $this->ddlLocalidad15->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad15->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad16->SelectedValue!="" and $this->ddlLocalidad16->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad16->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad17->SelectedValue!="" and $this->ddlLocalidad17->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad17->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad18->SelectedValue!="" and $this->ddlLocalidad18->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad18->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad19->SelectedValue!="" and $this->ddlLocalidad19->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad19->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad20->SelectedValue!="" and $this->ddlLocalidad20->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad20->SelectedValue;
					$obraLocalidad->save();
				}

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdObra = :idobra ';
				$criteria->Parameters[':idobra'] = $obra->IdObra;
				$criteria->OrdersBy['Fecha'] = 'desc';

				$nuevoEstado = new ObraEstadoRecord();
				$nuevoEstado->IdObra = $obra->IdObra;
				$nuevoEstado->IdEstadoObra = $obra->IdEstadoObra;
				$nuevoEstado->Fecha = date('Y-m-d');
				$nuevoEstado->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
				$nuevoEstado->save();

				$contrato = new ContratoRecord();
				$contrato->IdObra = $obra->IdObra;
				$contrato->IdProveedor = $this->txtIdProveedor->Text;
				$contrato->Numero = '*';
				$fecha = explode("/", $this->dtpFechaInicio->Text);
				$contrato->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
				$contrato->Monto = $this->txtMonto->Text;
				$contrato->FechaBaseMonto = $fecha[2]."-".$fecha[1]."-".$fecha[0];
				$contrato->NormaLegalAutorizacion = '*';
				$contrato->NormaLegalAdjudicacion = '*';
				$contrato->PlazoEjecucion = $this->txtPlazoEjecucion->Text;
				$contrato->FechaInicio = $fecha[2]."-".$fecha[1]."-".$fecha[0];

				if($this->txtAmpliacionPlazo->Text!='0'){
					$plazoEjecucion = (int) $this->txtPlazoEjecucion->Text + 1;
					$fechaDateTime = new DateTime($fecha[2].'-'.$fecha[1].'-'.$fecha[0]);
					$fechaDateTime->add(new DateInterval('P'.$plazoEjecucion.'D'));
					$contrato->FechaFinalizacion = $fechaDateTime->format('Y-m-d');
					$contrato->save();

					$contratoPlazo = new ContratoPlazoRecord();
					$contratoPlazo->IdContrato = $contrato->IdContrato;
					$contratoPlazo->CantidadDias = $this->txtAmpliacionPlazo->Text;
					$fecha = explode("/", $this->dtpFechaFinalizacion->Text);
					$contratoPlazo->NuevaFechaFinalizacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
					$contratoPlazo->NormaLegalAprobacion = '*';
					$contratoPlazo->save();
				}
				else{
					$fecha = explode("/", $this->dtpFechaFinalizacion->Text);
					$contrato->FechaFinalizacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
					$contrato->save();
				}

				if(floatval($this->txtMontoAdicional->Text)>0){
					$alteracion = new AlteracionRecord();
					$alteracion->IdContrato = $contrato->IdContrato;
					$fecha = explode("/", $this->dtpFechaInicio->Text);
					$alteracion->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
					$alteracion->NormaLegalAprobacion = '*';
					$alteracion->AdicionalDeductivo = 0;
					$alteracion->Importe = $this->txtMontoAdicional->Text;
					$alteracion->save();
				}

				$certificacion = new CertificacionRecord();
				$certificacion->IdContrato = $contrato->IdContrato;
				$certificacion->NroCertificado = 1;
				$periodo = explode("/",$this->dtpPeriodo->Text);
				$certificacion->Periodo = $periodo[1].$periodo[0];
				$certificacion->PorcentajeAvance = $this->txtPorcentajeAvance->Text;
				$certificacion->MontoAvance = $this->txtMontoAvance->Text;
				$certificacion->ImporteNeto = $this->txtMontoAvance->Text;
				$certificacion->AnticipoFinanciero = null;
				$certificacion->DescuentoAnticipo = null;
				$certificacion->RetencionMulta = null;
				$certificacion->RetencionFondoReparo = null;
				$certificacion->RedeterminacionPrecios = null;
				$certificacion->OtrosConceptos = null;
				$certificacion->FechaVtoPago = null;
				$certificacion->ManoObraOcupada = null;
				$certificacion->Observaciones = null;
				$certificacion->TipoCertificado = 0;
				$certificacion->IdOrdenTrabajo = null;
				$certificacion->Alcance = null;
				$certificacion->save();
				$this->Response->Redirect("?page=Obra.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function btnMasLocalidades_OnClick($sender, $param)
	{
		
		if($this->pnlMasLocalidades->Display=="None"){
			$this->pnlMasLocalidades->Display = "Dynamic";
		}
		else{
			$this->pnlMasLocalidades->Display = "None";
		}

	}

	public function cvMemoriaDescriptiva_OnServerValidate($sender, $param)
	{
		$texto = $this->txtMemoriaDescriptiva->Text;

		if (strlen($texto)>516) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
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

	public function dtpFechaInicio_OnTextChanged($sender, $param){
		$this->RecalcularFechaFinalizacion();
	}

	public function RecalcularFechaFinalizacion(){
		$fechaInicio = $this->dtpFechaInicio->Text;
		$plazoEjecucion = $this->txtPlazoEjecucion->Text;
		$ampliacionPlazo = $this->txtAmpliacionPlazo->Text;

		if($fechaInicio!="" and $plazoEjecucion!="" and $ampliacionPlazo!=""){
			$plazoEjecucion += $ampliacionPlazo;
			$plazoEjecucion+=1;
			$fecha = explode("/", $fechaInicio);
			$fecha = new DateTime($fecha[2].'-'.$fecha[1].'-'.$fecha[0]);
			$fecha->add(new DateInterval('P'.$plazoEjecucion.'D'));
			$this->dtpFechaFinalizacion->Text = $fecha->format('d/m/Y');
		}

	}

	public function txtMontoAvance_OnTextChanged($sender, $param){
		$monto = $this->txtMontoAvance->Text;
		$montoContrato = $this->txtMonto->Text + $this->txtMontoAdicional->Text;
		$this->txtPorcentajeAvance->Text = number_format($monto/$montoContrato*100,5,".","");
	}

	public function cvMontoAvance_OnServerValidate($sender, $param){
		$porcentaje = $this->txtPorcentajeAvance->Text;
		$porcentaje = number_format($porcentaje, 2);
		
		if(number_format($porcentaje, 2) > '100.00'){
			$param->IsValid = false;
			$this->cvMontoAvance->ErrorMessage = "No puede certificar más del 100% de la obra ($porcentaje %)";
		}
		else{
			$param->IsValid = true;
		}

	}

}

?>