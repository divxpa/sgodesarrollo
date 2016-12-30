<?php
class ObrasReport extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
		}

	}

	public function LoadDataRelated(){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesConObraSelect", $idOrganismo);
		$this->ddlLocalidad->DataSource = $localidades;
		$this->ddlLocalidad->dataBind();

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Descripcion'] = 'asc';
		$finder = EstadoObraRecord::finder();
		$estados = $finder->findAll($criteria);

		$this->chkEstado->DataSource = $estados;
		$this->chkEstado->dataBind();
		$this->setViewState("Estados", $estados);
		$this->CheckEstados(true);
	}

	public function CheckEstados($check){
		$selected = array();

		if($check){
			$estados = $this->getViewState("Estados", array());

			foreach($estados as $e){
				$selected[] = $e->IdEstadoObra;
			}

		}

		$this->chkEstado->SelectedValues = $selected;
	}

	public function btnCheckEstados_OnClick($sender, $param){

		if($sender->Text=="Desmarcar Todos"){
			$this->btnCheckEstados->Text = "Marcar Todos";
			$this->CheckEstados(false);
		}
		else{
			$this->btnCheckEstados->Text = "Desmarcar Todos";
			$this->CheckEstados(true);
		}

	}

	public function btnConsultar_OnClick($sender, $param)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$idLocalidad = $this->ddlLocalidad->SelectedValue;
		$codigo = $this->txtCodigo->Text;
		$denominacion = $this->txtDenominacion->Text;
		$expediente = $this->txtExpediente->Text;

		if(is_object($this->ddlLocalidad->SelectedItem)){
			$localidad = $this->ddlLocalidad->SelectedItem->Text;
		}
		else{
			$localidad = "TODAS";
		}

		$estadosCount = count($this->getViewState("Estados", array()));
		$selectedValues = $this->chkEstado->SelectedValues;

		if($estadosCount==count($selectedValues)){
			$estado = "TODOS";
		}
		else{
			$estado = "";
			$finder = EstadoObraRecord::finder();
			
			for($i=0; $i<count($selectedValues); $i++){
				$estadoRecord = $finder->findByPk($selectedValues[$i]);
				$estado .= $estadoRecord->Descripcion . " - ";
			}

		}

		$data = $this->getData($idOrganismo, $codigo, $denominacion, $expediente, $idLocalidad, $selectedValues);

		$filters = array($codigo, $denominacion, $expediente, $localidad, $estado);
		$file = $this->GenerarReporte("ObrasReport", "A4-L", $filters, $data);
		$this->CallbackClient->callClientFunction("Imprimir",array($file));
	}

	public function getData($idOrganismo, $codigo, $denominacion, $expediente, $idLocalidad, $idEstados)
	{
		$data = $this->CreateDataSource("ObraPeer","ObrasReport", $idOrganismo, $codigo, $denominacion, $expediente, $idLocalidad, $idEstados);

		if($this->chkCertificaciones->Checked){

			for($i=0; $i<count($data); $i++){

				if(!is_null($data[$i]["IdContrato"])){
					$data[$i]["Certificaciones"] = $this->CreateDataSource("CertificacionPeer","ObrasReportDetalle", $data[$i]["IdContrato"]);
				}
				else{
					$data[$i]["Certificaciones"] = null;
				}

			}

		}

		return $data;
	}

	public function btnExportar_OnClick($sender, $param)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$idLocalidad = $this->ddlLocalidad->SelectedValue;
		$codigo = $this->txtCodigo->Text;
		$denominacion = $this->txtDenominacion->Text;
		$expediente = $this->txtExpediente->Text;

		if(is_object($this->ddlLocalidad->SelectedItem)){
			$localidad = $this->ddlLocalidad->SelectedItem->Text;
		}
		else{
			$localidad = "TODAS";
		}

		$estadosCount = count($this->getViewState("Estados", array()));
		$selectedValues = $this->chkEstado->SelectedValues;

		if($estadosCount==count($selectedValues)){
			$estado = "TODOS";
		}
		else{
			$estado = "";
			$finder = EstadoObraRecord::finder();
			
			for($i=0; $i<count($selectedValues); $i++){
				$estadoRecord = $finder->findByPk($selectedValues[$i]);
				$estado .= $estadoRecord->Descripcion . " - ";
			}

		}

		$data = $this->getData($idOrganismo, $codigo, $denominacion, $expediente, $idLocalidad, $selectedValues);

		$excelxml = new ExcelXml();
		$hoja1 = array();
		$hoja1[] = array("S.G.O. - Sistema de Gestión de Obras");
		$hoja1[] = array("Usuario", $this->Session->get("usr_apellidoNombre"));
		$hoja1[] = array("Fecha/Hora", date('d/m/Y H:i:s'));
		$hoja1[] = array("Reporte de Obras");
		$hoja1[] = array("Filtros");
		$hoja1[] = array("Código", $codigo, "Denominación", $denominacion, "Expediente", $expediente, "Localidad", $localidad, "Estado", $estado);
		$hoja1[] = array("Cód.", "Denominación", "Localidad", "Expediente", "Proveedor", "Nro. Cont.", "Monto Cont.", "Alter.", "Red. Prec.", "% Certif.", "$ Certif.", "Estado", "Detalle");

		foreach($data as $d){
			$hoja1[] = array($d["Codigo"], $d["Denominacion"], $d["Localidad"], $d["Expediente"], $d["Proveedor"], $d["NroContrato"], array("Number", $d["Monto"]), array("Number", $d["Alteracion"]), array("Number", $d["RedeterminacionPrecios"]), array("Number", $d["PorcentajeAvance"]), array("Number", $d["MontoAvance"]), $d["Estado"], $d["DetalleEstado"]);

			$certificaciones = isset($d["Certificaciones"]) ? $d["Certificaciones"] : null;

			if(is_array($certificaciones) and count($certificaciones)){
				$hoja1[] = array("", "Tipo", "Nro.", "Periodo", "% Cert.", "$ Cert.", "Ant. Fin.", "Dto. Ant.", "Ret. Multa", "Fdo. Rep.", "Red. Prec.", "Otros", "Imp. Neto", "Mano Obra", "F. Vto.", "Imp. Pag.", "F. Pag.", "Saldo");

				$porcentajeAvance =
				$montoAvance =
				$anticipoFinanciero =
				$descuentoAnticipo =
				$retencionMulta =
				$retencionFondoReparo =
				$redeterminacionPrecios =
				$otrosConceptos =
				$importeNeto =
				$manoObraOcupada =
				$importePagado =
				$saldo = 0;

				foreach($certificaciones as $d1){
					$porcentajeAvance += $d1["PorcentajeAvance"];
					$montoAvance += $d1["MontoAvance"];
					$anticipoFinanciero += $d1["AnticipoFinanciero"];
					$descuentoAnticipo += $d1["DescuentoAnticipo"];
					$retencionMulta += $d1["RetencionMulta"];
					$retencionFondoReparo += $d1["RetencionFondoReparo"];
					$redeterminacionPrecios += $d1["RedeterminacionPrecios"];
					$otrosConceptos += $d1["OtrosConceptos"];
					$importeNeto += $d1["ImporteNeto"];
					$manoObraOcupada += $d1["ManoObraOcupada"];
					$importePagado += $d1["ImportePagado"];
					$saldo += $d1["Saldo"];

					$hoja1[] = array("", $d1["TipoCertificado"], $d1["NroCertificado"], $d1["Periodo"], array("Number", $d1["PorcentajeAvance"]), array("Number", $d1["MontoAvance"]), array("Number", $d1["AnticipoFinanciero"]), array("Number", $d1["DescuentoAnticipo"]), array("Number", $d1["RetencionMulta"]), array("Number", $d1["RetencionFondoReparo"]), array("Number", $d1["RedeterminacionPrecios"]), array("Number", $d1["OtrosConceptos"]), array("Number", $d1["ImporteNeto"]), array("Number", $d1["ManoObraOcupada"]), $d1["FechaVtoPago"], array("Number", $d1["ImportePagado"]), $d1["FechaPago"], array("Number", $d1["Saldo"]));
				}

				$hoja1[] = array("", "", "", "TOTALES", array("Number", $porcentajeAvance), array("Number", $montoAvance), array("Number", $anticipoFinanciero), array("Number", $descuentoAnticipo), array("Number", $retencionMulta), array("Number", $retencionFondoReparo), array("Number", $redeterminacionPrecios), array("Number", $otrosConceptos), array("Number", $importeNeto), array("Number", $manoObraOcupada), "", array("Number", $importePagado), "", array("Number", $saldo));
			}

		}
		
		$excelxml->AgregarHoja("Reporte de Obras", $hoja1);
		$excelxml->GenerarXml();
		$this->CallbackClient->callClientFunction("OpenWindow",array("download.php/",1, 1));
	}

}

?>