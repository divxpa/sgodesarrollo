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
				$this->lblAccion->Text = "Modificar Certificación";
				$this->Refresh($id, $idObra, $idContrato);
			}
			else{
				$this->SugerirNumeroCertificado($idContrato, $this->ddlTipoCertificado->SelectedValue);
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
		$this->lblExpediente->Text = $obra->Expediente  . "-";

		$this->hlkVolver->NavigateUrl .= "&idc=$idContrato&ido=$idObra";

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Descripcion'] = 'asc';
		$finder = EstadoObraRecord::finder();
		$estados = $finder->findAll($criteria);
		$this->ddlEstado->DataSource = $estados;
		$this->ddlEstado->dataBind();
		$this->ddlEstado->SelectedValue = $obra->IdEstadoObra;
		$this->txtDetalleEstado->Text = $obra->DetalleEstado;

		//Veo si el contrato tiene ordenes de trabajo para mostrar el desplegable
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdContrato = :idcontrato';
		$criteria->Parameters[':idcontrato'] = $idContrato;
		$criteria->OrdersBy['Numero'] = 'asc';
		$finder = OrdenTrabajoRecord::finder();
		$ordenesTrabajo = $finder->findAll($criteria);
		$this->ddlOrdenTrabajo->DataSource = $ordenesTrabajo;
		$this->ddlOrdenTrabajo->dataBind();

		if(count($ordenesTrabajo)){
			$this->pnlOrdenTrabajo->Display = "Dynamic";
		}

	}

	public function SugerirNumeroCertificado($idContrato, $tipo, $idOrdenTrabajo=""){
		$numero = $this->CreateDataSource("CertificacionPeer", "SiguienteNumeroCertificado", $idContrato, $tipo, $idOrdenTrabajo);
		$this->txtNumero->Text = $numero[0]["Numero"];
		$this->dtpPeriodo->Text = $numero[0]["Periodo"];
	}

	public function ddlTipoCertificado_OnSelectedIndexChanged($sender, $param){
		$idContrato = $this->Request["idc"];
		$tipo = $this->ddlTipoCertificado->SelectedValue;
		$idOrdenTrabajo = $this->ddlOrdenTrabajo->SelectedValue;
		$this->SugerirNumeroCertificado($idContrato, $tipo, $idOrdenTrabajo);
	}

	/*DESACTIVADO*/
	public function cvNumero_OnServerValidate($sender, $param)
	{
		$idContrato = $this->Request["idc"];
		$numero = $this->txtNumero->Text;
		
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdContrato = :idcontrato AND NroCertificado = :numero ';
		$criteria->Parameters[':idcontrato'] = $idContrato;
		$criteria->Parameters[':numero'] = $numero;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdCertificacion <> :idcertificacion';
			$criteria->Parameters[':idcertificacion'] = $id;
		}

		$finder = CertificacionRecord::finder();
		$certificacion = $finder->find($criteria);

		if (is_object($certificacion)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function Refresh($idCertificacion, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$certificacion = $this->CreateDataSource("CertificacionPeer","getCertificacion", $idCertificacion);
		$certificacion = $certificacion[0];
		/*$finder = CertificacionRecord::finder();
		$certificacion = $finder->findByPk($idCertificacion);*/
		$this->txtAlcance->Text = $certificacion["Alcance"];

		if(!is_null($certificacion["IdOrdenTrabajo"])){
			$this->ddlOrdenTrabajo->SelectedValue = $certificacion["IdOrdenTrabajo"];
		}

		$this->ddlTipoCertificado->SelectedValue = $certificacion["TipoCertificado"];
		$this->txtNumero->Text = $certificacion["NroCertificado"];
		$periodo = substr($certificacion["Periodo"], 4, 2) . "/" . substr($certificacion["Periodo"], 0, 4);
		$this->dtpPeriodo->Text = $periodo;
		$this->txtPorcentajeAvance->Text = $certificacion["PorcentajeAvanceCalc"];
		$this->txtMontoAvance->Text = $certificacion["MontoAvance"];
		$this->txtAnticipoFinanciero->Text = $certificacion["AnticipoFinanciero"];
		$this->txtDescuentoAnticipo->Text = $certificacion["DescuentoAnticipo"];
		$this->txtRetencionMulta->Text = $certificacion["RetencionMulta"];
		$this->txtFondoReparo->Text = $certificacion["RetencionFondoReparo"];
		$this->txtRedeterminacionPrecios->Text = $certificacion["RedeterminacionPrecios"];
		$this->txtOtrosConceptos->Text = $certificacion["OtrosConceptos"];
		$this->txtImporteNeto->Text = $certificacion["ImporteNeto"];
		
		if(!is_null($certificacion["FechaVtoPago"])){
			$fecha = explode("-",$certificacion["FechaVtoPago"]);
			$this->dtpFechaVto->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}
		else{
			$this->dtpFechaVto->Text = "";
		}

		$this->txtManoObra->Text = $certificacion["ManoObraOcupada"];
		$this->txtObservaciones->Text = $certificacion["Observaciones"];
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		$this->Response->Redirect("?page=Obra.Contrato.Certificacion.Home&idc=$idc&ido=$ido");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];

			if(!is_null($id)){
				$finder = CertificacionRecord::finder();
				$certificacion = $finder->findByPk($id);
			}
			else{
				$certificacion = new CertificacionRecord();
				$certificacion->IdContrato = $idContrato;
			}

			if($this->txtNumero->Text!=""){
				$certificacion->NroCertificado = $this->txtNumero->Text;
			}
			else{
				$certificacion->NroCertificado = null;
			}

			$periodo = explode("/",$this->dtpPeriodo->Text);
			$certificacion->Periodo = $periodo[1].$periodo[0];
			$certificacion->PorcentajeAvance = $this->txtPorcentajeAvance->Text;
			$certificacion->MontoAvance = $this->txtMontoAvance->Text;

			if($this->txtAnticipoFinanciero->Text!=""){
				$certificacion->AnticipoFinanciero = $this->txtAnticipoFinanciero->Text;
			}
			else{
				$certificacion->AnticipoFinanciero = null;
			}

			if($this->txtDescuentoAnticipo->Text!=""){
				$certificacion->DescuentoAnticipo = $this->txtDescuentoAnticipo->Text;
			}
			else{
				$certificacion->DescuentoAnticipo = null;
			}

			if($this->txtRetencionMulta->Text!=""){
				$certificacion->RetencionMulta = $this->txtRetencionMulta->Text;
			}
			else{
				$certificacion->RetencionMulta = null;
			}

			if($this->txtFondoReparo->Text!=""){
				$certificacion->RetencionFondoReparo = $this->txtFondoReparo->Text;
			}
			else{
				$certificacion->RetencionFondoReparo = null;
			}

			if($this->txtRedeterminacionPrecios->Text!=""){
				$certificacion->RedeterminacionPrecios = $this->txtRedeterminacionPrecios->Text;
			}
			else{
				$certificacion->RedeterminacionPrecios = null;
			}
			
			if($this->txtOtrosConceptos->Text!=""){
				$certificacion->OtrosConceptos = $this->txtOtrosConceptos->Text;
			}
			else{
				$certificacion->OtrosConceptos = null;
			}
		
			$certificacion->ImporteNeto = $this->txtImporteNeto->Text;

			if($this->dtpFechaVto->Text!=""){
				$fecha = explode("/",$this->dtpFechaVto->Text);
				$certificacion->FechaVtoPago = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$certificacion->FechaVtoPago = null;
			}

			if($this->txtManoObra->Text!=""){
				$certificacion->ManoObraOcupada = $this->txtManoObra->Text;
			}
			else{
				$certificacion->ManoObraOcupada = null;
			}

			$certificacion->Observaciones = $this->txtObservaciones->Text;
			$certificacion->TipoCertificado = $this->ddlTipoCertificado->SelectedValue;

			if($this->ddlOrdenTrabajo->SelectedValue!="" and $this->ddlOrdenTrabajo->SelectedValue!="0"){
				$certificacion->IdOrdenTrabajo = $this->ddlOrdenTrabajo->SelectedValue;
			}
			else{
				$certificacion->IdOrdenTrabajo = null;
			}

			if($this->txtAlcance->Text!=""){
				$certificacion->Alcance = $this->txtAlcance->Text;
			}
			else{
				$certificacion->Alcance = null;
			}

			try{
				$certificacion->save();
				$finder = ObraRecord::finder();
				$obra = $finder->findByPk($idObra);

				if($this->ddlEstado->SelectedValue != $obra->IdEstadoObra){
					$obra->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
					$obra->IdEstadoObra = $this->ddlEstado->SelectedValue;
					$obra->save();
					$nuevoEstado = new ObraEstadoRecord();
					$nuevoEstado->IdObra = $obra->IdObra;
					$nuevoEstado->IdEstadoObra = $obra->IdEstadoObra;
					$nuevoEstado->Fecha = date('Y-m-d');
					$nuevoEstado->DetalleEstado = $obra->DetalleEstado;
					$nuevoEstado->save();
				}
				else{
					
					if($obra->DetalleEstado != mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8')){
						$obra->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
						$obra->save();
						$criteria = new TActiveRecordCriteria;
						$criteria->Condition = 'IdObra = :idobra ';
						$criteria->Parameters[':idobra'] = $obra->IdObra;
						$criteria->OrdersBy['Fecha'] = 'desc';
						$finder = ObraEstadoRecord::finder();
						$estado = $finder->find($criteria);	
						$estado->DetalleEstado = $obra->DetalleEstado;
						$estado->save();
					}

				}

				$porcentajeAvance = $this->CreateDataSource("ObraPeer", "PorcentajeAvance", $idObra, null);
				
				if(floatval($porcentajeAvance[0]['PorcentajeAvance'])>99.99){

					if($obra->IdEstadoObra!=8){
						$obra->IdEstadoObra = 8;
						$obra->DetalleEstado = 'FINALIZADA';
						$obra->save();

						$nuevoEstado = new ObraEstadoRecord();
						$nuevoEstado->IdObra = $obra->IdObra;
						$nuevoEstado->IdEstadoObra = $obra->IdEstadoObra;
						$nuevoEstado->Fecha = date('Y-m-d');
						$nuevoEstado->DetalleEstado = $obra->DetalleEstado;
						$nuevoEstado->save();						
					}

				}

				$this->Response->Redirect("?page=Obra.Contrato.Certificacion.Home&idc=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function txtImporte_OnTextChanged($sender, $param){
		$this->CalcularNeto();
	}

	public function CalcularNeto(){
		$certificacion = floatval($this->txtMontoAvance->Text);
		$anticipo = floatval($this->txtAnticipoFinanciero->Text);
		$descuento = floatval($this->txtDescuentoAnticipo->Text);
		$multa = floatval($this->txtRetencionMulta->Text);
		$fondoReparo = floatval($this->txtFondoReparo->Text);
		$redeterminacionPrecios = floatval($this->txtRedeterminacionPrecios->Text);
		$otros = floatval($this->txtOtrosConceptos->Text);
		$this->txtImporteNeto->Text = number_format($certificacion + $anticipo - $descuento - $multa - $fondoReparo + $redeterminacionPrecios + $otros, 2, ".", "");
	}

	public function txtMontoAvance_OnTextChanged($sender, $param){
		$idContrato = $this->Request["idc"];
		$idOrdenTrabajo = $this->ddlOrdenTrabajo->SelectedValue;
		$monto = $this->txtMontoAvance->Text;
		
		if($monto!=""){
			//veo si certifica orden de trabajo o contrato
			if($idOrdenTrabajo!="" and $idOrdenTrabajo!="0"){
				$montoContratoArray = $this->CreateDataSource("OrdenTrabajoPeer", "MontoOrdenTrabajo", $idOrdenTrabajo);
			}
			else{
				$montoContratoArray = $this->CreateDataSource("ContratoPeer", "MontoContrato", $idContrato);
			}

			$montoContrato = $montoContratoArray[0]["MontoTotal"];
			$this->txtPorcentajeAvance->Text = number_format($monto/$montoContrato*100,5,".","");
		}

		$this->CalcularNeto();
	}

	public function cvMontoAvance_OnServerValidate($sender, $param){
		$idObra = $this->Request["ido"];
		$idContrato = $this->Request["idc"];
		$id = $this->Request["id"];
		$idOrdenTrabajo = $this->ddlOrdenTrabajo->SelectedValue;

		if($idOrdenTrabajo!="" and $idOrdenTrabajo!="0"){
			$data = $this->CreateDataSource("OrdenTrabajoPeer","PorcentajeAvance", $idOrdenTrabajo, $id);
		}
		else{
			$data = $this->CreateDataSource("ContratoPeer","PorcentajeAvance", $idContrato, $id);
		}

		$porcentaje = $data[0]['PorcentajeAvance'] + $this->txtPorcentajeAvance->Text;
		$porcentaje = number_format($porcentaje, 2);
		
		if(number_format($porcentaje, 2) > '100.00'){
			$param->IsValid = false;
			$this->cvMontoAvance->ErrorMessage = "No puede certificar más del 100% de la obra ($porcentaje %)";
		}
		else{
			$param->IsValid = true;
		}

	}

	public function cvEstado_OnServerValidate($sender, $param){
		/*$idObra = $this->Request["ido"];
		$idContrato = $this->Request["idc"];
		$id = $this->Request["id"];
		$data = $this->CreateDataSource("ContratoPeer","PorcentajeAvance", $idContrato, $id);
		$porcentaje = $data[0]['PorcentajeAvance'] + $this->txtPorcentajeAvance->Text;
		
		if($porcentaje >= 100){
			
			if($this->ddlEstado->SelectedValue!=8){
				$param->IsValid = false;
				$this->cvEstado->ErrorMessage = "Debe cambiar el estado de obra a FINALIZADA por que registra el 100% de certificación";
			}
			else{
				$param->IsValid = true;
			}
			
		}
		else{
			
			if($this->ddlEstado->SelectedValue!=6){
				$param->IsValid = false;
				$this->cvEstado->ErrorMessage = "Debe cambiar el estado de obra a EN EJECUCIÓN por que no registra el 100% de certificación";
			}
			else{
				$param->IsValid = true;
			}
			
		}*/
		$param->IsValid = true;
	}

}

?>