<?php
class CertificacionPeriodo extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);
	}

	public function btnBuscar_OnClick($sender, $param)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$periodo = explode("/", $this->dtpPeriodo->Text);
		$periodo = $periodo[1].$periodo[0];
		$this->Refresh($idOrganismo, $periodo);
	}

	public function Refresh($idOrganismo, $periodo)
	{
		$data = $this->CreateDataSource("CertificacionPeer","CertificacionesPorPeriodo", $idOrganismo, $periodo);
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();

		if(count($data)){
			$this->pnlConfirmar->Display = 
			$this->pnlDatos->Display = "Dynamic";
		}
		else{
			$this->pnlConfirmar->Display = 
			$this->pnlDatos->Display = "None";
		}

		$this->CalcularTotales();
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Home");
	}

	/*DESACTIVADO*/
	public function cvNumero_OnServerValidate($sender, $param)
	{
		$idContrato = $sender->Parent->Parent->tcNumero->hdnIdContrato->Value;
		$idCertificacion = $sender->Parent->Parent->tcNumero->hdnIdCertificacion->Value;
		$numero = $param->Value;

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdContrato = :idcontrato AND NroCertificado = :numero ';
		$criteria->Parameters[':idcontrato'] = $idContrato;
		$criteria->Parameters[':numero'] = $numero;

		if($idCertificacion!=""){
			$criteria->Condition .=  ' AND IdCertificacion <> :idcertificacion';
			$criteria->Parameters[':idcertificacion'] = $idCertificacion;
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

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){

			try{
				$periodo = explode("/",$this->dtpPeriodo->Text);
				$periodo = $periodo[1].$periodo[0];

				foreach ($this->dgDatos->Items as $it) {
				
					if($it->tcMontoAvance->txtMontoAvance->Text!=""){
						$idCertificacion = $it->tcNumero->hdnIdCertificacion->Value;
						$idContrato = $it->tcNumero->hdnIdContrato->Value;

						if($idCertificacion!=""){
							$finder = CertificacionRecord::finder();
							$certificacion = $finder->findByPk($idCertificacion);
						}
						else{
							$certificacion = new CertificacionRecord();
							$certificacion->IdContrato = $idContrato;
						}

						if($it->tcNumero->txtNumero->Text!=""){
							$certificacion->NroCertificado = $it->tcNumero->txtNumero->Text;
						}
						else{
							$certificacion->NroCertificado = null;
						}

						$certificacion->Periodo = $periodo;
						$certificacion->PorcentajeAvance = $it->tcPorcentajeAvance->txtPorcentajeAvance->Text;
						$certificacion->MontoAvance = $it->tcMontoAvance->txtMontoAvance->Text;

						if($it->tcAnticipoFinanciero->txtAnticipoFinanciero->Text!=""){
							$certificacion->AnticipoFinanciero = $it->tcAnticipoFinanciero->txtAnticipoFinanciero->Text;
						}
						else{
							$certificacion->AnticipoFinanciero = null;
						}

						if($it->tcDescuentoAnticipo->txtDescuentoAnticipo->Text!=""){
							$certificacion->DescuentoAnticipo = $it->tcDescuentoAnticipo->txtDescuentoAnticipo->Text;
						}
						else{
							$certificacion->DescuentoAnticipo = null;
						}

						if($it->tcRetencionMulta->txtRetencionMulta->Text!=""){
							$certificacion->RetencionMulta = $it->tcRetencionMulta->txtRetencionMulta->Text;
						}
						else{
							$certificacion->RetencionMulta = null;
						}

						if($it->tcFondoReparo->txtFondoReparo->Text!=""){
							$certificacion->RetencionFondoReparo = $it->tcFondoReparo->txtFondoReparo->Text;
						}
						else{
							$certificacion->RetencionFondoReparo = null;
						}

						if($it->tcRedeterminacionPrecios->txtRedeterminacionPrecios->Text!=""){
							$certificacion->RedeterminacionPrecios = $it->tcRedeterminacionPrecios->txtRedeterminacionPrecios->Text;
						}
						else{
							$certificacion->RedeterminacionPrecios = null;
						}
						
						if($it->tcOtrosConceptos->txtOtrosConceptos->Text!=""){
							$certificacion->OtrosConceptos = $it->tcOtrosConceptos->txtOtrosConceptos->Text;
						}
						else{
							$certificacion->OtrosConceptos = null;
						}
					
						$certificacion->ImporteNeto = $it->tcImporteNeto->txtImporteNeto->Text;

						if($it->tcManoObra->txtManoObra->Text!=""){
							$certificacion->ManoObraOcupada = $it->tcManoObra->txtManoObra->Text;
						}
						else{
							$certificacion->ManoObraOcupada = null;
						}
						
						$fecha = explode("/",$it->tcFechaVto->dtpFechaVto->Text);
						$certificacion->FechaVtoPago = $fecha[2]."-".$fecha[1]."-".$fecha[0];
						$certificacion->TipoCertificado = $it->tcTipo->ddlTipoCertificado->SelectedValue;
						$certificacion->save();
					}

				}

				$this->Response->Redirect("?page=Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function ddlTipoCertificado_OnSelectedIndexChanged($sender, $param){
		$periodo = explode("/", $this->dtpPeriodo->Text);
		$periodo = $periodo[1].$periodo[0];
		$tipo = $sender->Parent->Parent->tcTipo->ddlTipoCertificado->SelectedValue;
		$idContrato = $sender->Parent->Parent->tcNumero->hdnIdContrato->Value;
		$numero = $this->CreateDataSource("CertificacionPeer", "SiguienteNumeroCertificacionPorPeriodo", $idContrato, $tipo, $periodo);
		$sender->Parent->Parent->tcNumero->txtNumero->Text = $numero[0]["Numero"];
	}

	public function txtImporte_OnTextChanged($sender, $param){
		$this->CalcularNeto($sender);
		$this->CalcularTotales();
	}

	public function CalcularNeto($sender){
		$certificacion = floatval($sender->Parent->Parent->tcMontoAvance->txtMontoAvance->Text);
		$anticipo = floatval($sender->Parent->Parent->tcAnticipoFinanciero->txtAnticipoFinanciero->Text);
		$descuento = floatval($sender->Parent->Parent->tcDescuentoAnticipo->txtDescuentoAnticipo->Text);
		$multa = floatval($sender->Parent->Parent->tcRetencionMulta->txtRetencionMulta->Text);
		$fondoReparo = floatval($sender->Parent->Parent->tcFondoReparo->txtFondoReparo->Text);
		$redeterminacionPrecios = floatval($sender->Parent->Parent->tcRedeterminacionPrecios->txtRedeterminacionPrecios->Text);
		$otros = floatval($sender->Parent->Parent->tcOtrosConceptos->txtOtrosConceptos->Text);
		$sender->Parent->Parent->tcImporteNeto->txtImporteNeto->Text = number_format($certificacion + $anticipo - $descuento - $multa - $fondoReparo + $redeterminacionPrecios + $otros, 2, ".", "");
	}

	public function txtMontoAvance_OnTextChanged($sender, $param){
		$idContrato = $sender->Parent->Parent->tcNumero->hdnIdContrato->Value;
		$monto = $sender->Text;

		if($monto!=""){
			$montoContratoArray = $this->CreateDataSource("ContratoPeer", "MontoContrato", $idContrato);
			$montoContrato = $montoContratoArray[0]["MontoTotal"];
			$sender->Parent->Parent->tcPorcentajeAvance->txtPorcentajeAvance->Text = number_format($monto/$montoContrato*100,5,".","");
		}

		$this->CalcularNeto($sender);
		$this->CalcularTotales();
	}

	public function CalcularTotales(){
		$montoAvance =
		$anticipoFinanciero =
		$descuentoAnticipo =
		$retencionMulta =
		$fondoReparo =
		$redeterminacionPrecios =
		$otrosConceptos =
		$importeNeto = 0;

		foreach($this->dgDatos->Items as $it){
			$montoAvance += floatval($it->tcMontoAvance->txtMontoAvance->Text);
			$anticipoFinanciero += floatval($it->tcAnticipoFinanciero->txtAnticipoFinanciero->Text);
			$descuentoAnticipo += floatval($it->tcDescuentoAnticipo->txtDescuentoAnticipo->Text);
			$retencionMulta += floatval($it->tcRetencionMulta->txtRetencionMulta->Text);
			$fondoReparo += floatval($it->tcFondoReparo->txtFondoReparo->Text);
			$redeterminacionPrecios += floatval($it->tcRedeterminacionPrecios->txtRedeterminacionPrecios->Text);
			$otrosConceptos += floatval($it->tcOtrosConceptos->txtOtrosConceptos->Text);
			$importeNeto += floatval($it->tcImporteNeto->txtImporteNeto->Text);
		}

		$this->lblMontoAvanceTotal->Text = number_format($montoAvance, 2, ",", ".");
		$this->lblAnticipoFinancieroTotal->Text = number_format($anticipoFinanciero, 2, ",", ".");
		$this->lblDescuentoAnticipoTotal->Text = number_format($descuentoAnticipo, 2, ",", ".");
		$this->lblRetencionMultaTotal->Text = number_format($retencionMulta, 2, ",", ".");
		$this->lblFondoReparoTotal->Text = number_format($fondoReparo, 2, ",", ".");
		$this->lblRedeterminacionPreciosTotal->Text = number_format($redeterminacionPrecios, 2, ",", ".");
		$this->lblOtrosConceptosTotal->Text = number_format($otrosConceptos, 2, ",", ".");
		$this->lblImporteNetoTotal->Text = number_format($importeNeto, 2, ",", ".");
	}

}

?>