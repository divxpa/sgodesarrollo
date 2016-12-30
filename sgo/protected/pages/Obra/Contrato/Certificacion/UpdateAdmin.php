<?php

class UpdateAdmin extends PageBaseSP {

    public function onLoad($param) {
        parent::onLoad($param);

        if (!$this->IsPostBack) {
            $idObra = $this->Request["ido"];
            $idContrato = $this->Request["idc"];
            $id = $this->Request["id"];
            if (!is_null($id)) {
                $this->lblAccion->Text = "Modificar Certificación";
                $this->Refresh($id, $idObra, $idContrato);
            } else {
                $this->SugerirNumeroCertificado($idContrato, 0);
            }
            $this->LoadDataRelated($idObra, $idContrato, $id);

            $this->cambios->Value = "0";
        }
    }

    public function LoadDataRelated($idObra, $idContrato, $id) {
        $finder = ObraRecord::finder();
        $obra = $finder->findByPk($idObra);
        $finder = OrganismoRecord::finder();

        $organismo = $finder->findByPk($obra->IdOrganismo);
        $localidades = $this->CreateDataSource("ObraPeer", "LocalidadesPorObra", $idObra);
        $this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion;
        $this->lblLocalidades->Text = $localidades[0]["Localidades"];
        $finder = ContratoRecord::finder();
        $contrato = $finder->findByPk($idContrato);
        $finder = ProveedorRecord::finder();
        $proveedor = $finder->findByPk($contrato->IdProveedor);
        $periodo = explode("/", $this->dtpPeriodo->Text);
		$periodo = preg_replace('/(\d+)\/(\d+)/','$2$1', $this->dtpPeriodo->Text);
        $certificacionAnterior = $this->CreateDataSource("CertificacionPeer", "getCertificacionAnterior", $idContrato, $periodo);
        $contratoItems = $this->CreateDataSource("ContratoPeer", "ItemsByContratoCertificacion", $idContrato, $periodo, $id);
        $this->dgDatos->DataSource = $contratoItems;
		//echo"<pre>";print_r($contratoItems);die();
        $this->dgDatos->dataBind();
        $this->lblDecreto->Text = $contrato->Decreto;
        $this->lblContratista->Text = $proveedor->RazonSocial;
        $this->lblMontoProvincia->Text = number_format($contrato->MontoProvincia, 2, ",", ".");
        $this->hdnMontoContrato->Value = $contrato->Monto;
        $this->lblMontoContrato->Text = number_format($contrato->Monto, 2, ",", ".");
        if ($contrato->FechaInicio != null) {
            $fechaInicio = explode("-", $contrato->FechaInicio);
            $fechaInicio = $fechaInicio[2] . "/" . $fechaInicio[1] . "/" . $fechaInicio[0];
        } else {
            $fechaInicio = "-";
        }
        $this->lblFechaInicio->Text = $fechaInicio;
        $this->lblPlazoEjecucion->Text = $contrato->PlazoEjecucion;
        $this->txtImporteCertifAnterior->Text = number_format($certificacionAnterior[0]["ImporteCertifAnterior"], 2, ",", ".");
        $this->txtPorcentajeCertifAnterior->Text = number_format($certificacionAnterior[0]["ImporteCertifAnterior"] / $contrato->Monto * 100, 4, ".", "");
        if ($certificacionAnterior[0]["FechaUltimoPago"] != null) {
            $fechaUltimoPago = explode("-", $certificacionAnterior[0]["FechaUltimoPago"]);
            $fechaUltimoPago = $fechaUltimoPago[2] . "/" . $fechaUltimoPago[1] . "/" . $fechaUltimoPago[0];
        } else {
            $fechaUltimoPago = "-";
        }
        $this->lblFechaUltimoPago->Text = $fechaUltimoPago;

        $this->hlkVolver->NavigateUrl .= "&idc=$idContrato&ido=$idObra";

        $this->CalcularTotales();

//            $this->txtAnticipoOtorgadoProv->Text=number_format(0,2,",",".");

        $this->txtAnticipoAcumulado->Text = number_format($certificacionAnterior[0]["AnticipoFinancieroAnterior"], 2, ",", ".");

        $this->txtDescuentoAnticipoActual->Text = $this->lblSumaPorcentajeActual->Text * ($this->txtAnticipoAcumulado->Text + $this->txtAnticipoFinanciero->Text);
        $this->txtDescuentoAnticipoAcumulado->Text = number_format($certificacionAnterior[0]["DescuentoAnticipoAnterior"] + $this->txtDescuentoAnticipoActual->Text, 2, ",", ".");
//            $this->txtFondoReparo->Text=number_format(0,2,",",".");
        $this->txtTotalPagoMunicipio->Text = number_format($this->hdnSumaImporteActual->Value - ($this->lblSumaPorcentajeActual->Text * ($this->txtAnticipoAcumulado->Text + $this->txtAnticipoFinanciero->Text)) - $this->txtFondoReparo->Text, 2, ",", ".");
        $this->txtTotalPagoProvincia->Text = number_format($this->hdnSumaImporteActual->Value - ($this->lblSumaPorcentajeActual->Text * ($this->txtAnticipoAcumulado->Text + $this->txtAnticipoFinanciero->Text)), 2, ",", ".");
        $criteria = new TActiveRecordCriteria;
        $criteria->OrdersBy['Descripcion'] = 'asc';
    }

    public function SugerirNumeroCertificado($idContrato, $tipo, $idOrdenTrabajo = "") {
        $numero = $this->CreateDataSource("CertificacionPeer", "SiguienteNumeroCertificado", $idContrato, $tipo, $idOrdenTrabajo);
        $this->txtNumero->Text = $numero[0]["Numero"];
        $this->dtpPeriodo->Text = $numero[0]["Periodo"];
        $this->txtPorcentajeAvanceReal->Text = '0';
    }

    /* DESACTIVADO */

    public function cvNumero_OnServerValidate($sender, $param) {
        $idContrato = $this->Request["idc"];
        $numero = $this->txtNumero->Text;

        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = 'IdContrato = :idcontrato AND NroCertificado = :numero ';
        $criteria->Parameters[':idcontrato'] = $idContrato;
        $criteria->Parameters[':numero'] = $numero;

        $id = $this->Request["id"];

        if (!is_null($id)) {
            $criteria->Condition .= ' AND IdCertificacion <> :idcertificacion';
            $criteria->Parameters[':idcertificacion'] = $id;
        }

        $finder = CertificacionRecord::finder();
        $certificacion = $finder->find($criteria);

        if (is_object($certificacion)) {
            $param->IsValid = false;
        } else {
            $param->IsValid = true;
        }
    }

    public function dtpPeriodo_OnServerValidate($sender, $param) {
        $idContrato = $this->Request["idc"];
        $periodo = explode("/", $this->dtpPeriodo->Text);
        $periodo = $periodo[1] . $periodo[0];

        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = 'IdContrato = :idcontrato AND Periodo = :periodo ';
        $criteria->Parameters[':idcontrato'] = $idContrato;
        $criteria->Parameters[':periodo'] = $periodo;

        $id = $this->Request["id"];

        if (!is_null($id)) {
            $criteria->Condition .= ' AND IdCertificacion <> :idcertificacion';
            $criteria->Parameters[':idcertificacion'] = $id;
        }

        $finder = CertificacionRecord::finder();
        $certificacion = $finder->find($criteria);

        if (is_object($certificacion)) {
            $param->IsValid = false;
        } else {
            $param->IsValid = true;
        }
    }

    public function Refresh($idCertificacion, $idObra, $idContrato) {
        $idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
        $finder = ObraRecord::finder();
        $obra = $finder->findByPk($idObra);
        $contrato = ContratoRecord::finder()->findByPk($idContrato);
        if (!$this->ValidarObraOrganismo($idOrganismo, $idObra)) {
            $this->Response->Redirect("?page=Obra.Home");
        }

        $certificacion = $this->CreateDataSource("CertificacionPeer", "getCertificacion", $idCertificacion);
		//echo"<pre>";print_r($certificacion);die();
        $certificacion = $certificacion[0];
        $this->dtpPeriodo->Enabled = false;
        $this->txtNumero->Text = $certificacion["NroCertificado"];
        $periodo = substr($certificacion["Periodo"], 4, 2) . "/" . substr($certificacion["Periodo"], 0, 4);
        $this->dtpPeriodo->Text = $periodo;
        $this->txtAnticipoFinanciero->Text = number_format($certificacion["AnticipoFinanciero"], 2, ",", ".");
        $this->txtAnticipoOtorgadoProv->Text = number_format($certificacion["AnticipoFinanciero"], 2, ",", ".");
        $this->txtFondoReparo->Text = $certificacion["RetencionFondoReparo"];

        $this->txtAnticipoFinancieroPorcentaje->Text = number_format($certificacion["AnticipoFinanciero"] / $contrato->Monto * 100, 4, ".", "");
        if ($certificacion["FechaPagoAnticipo"] != 0) {
            $fechaAnticipo = explode("-", $certificacion["FechaPagoAnticipo"]);
            $this->lblFechaPagoAnticipoFinanciero->Text = $fechaAnticipo[2] . "/" . $fechaAnticipo[1] . "/" . $fechaAnticipo[0];
        } else {
            $this->lblFechaPagoAnticipoFinanciero->Text = null;
        }

        $this->txtPorcentajeAvanceReal->Text = $certificacion["PorcentajeAvanceReal"];
        if ($certificacion["FechaMedicion"] != null) {
            $fechaMedicion = explode("-", $certificacion["FechaMedicion"]);
            $this->dtpFechaMedicion->Text = $fechaMedicion[2] . "/" . $fechaMedicion[1] . "/" . $fechaMedicion[0];
        } else {
            $this->dtpFechaMedicion->Text = null;
        }
    }

    public function btnCancelar_OnClick($sender, $param) {
        $ido = $this->Request["ido"];
        $idc = $this->Request["idc"];
        $this->Response->Redirect("?page=Obra.Contrato.Certificacion.HomeAdmin&idc=$idc&ido=$ido");
    }

    public function btnAceptar_OnClick($sender, $param) {
        $this->CalcularTotales();
        if ($this->IsValid) {

            $id = $this->Request["id"];
            $idObra = $this->Request["ido"];
            $idContrato = $this->Request["idc"];

            if (!is_null($id)) {
                $finder = CertificacionRecord::finder();
                $certificacion = $finder->findByPk($id);
            } else {
                $certificacion = new CertificacionRecord();
                $certificacion->IdContrato = $idContrato;
            }

            if ($this->txtNumero->Text != "") {
                $certificacion->NroCertificado = $this->txtNumero->Text;
            } else {
                $certificacion->NroCertificado = null;
            }

            $periodo = explode("/", $this->dtpPeriodo->Text);
            $certificacion->Periodo = $periodo[1] . $periodo[0];
            $certificacion->PorcentajeAvance = floatval($this->lblSumaPorcentajeActual->Text);
            $certificacion->MontoAvance = $this->hdnSumaImporteActual->Value;
            $certificacion->DescuentoAnticipo = floatval($this->txtDescuentoAnticipoActual->Text);
            $certificacion->RetencionFondoReparo = floatval($this->txtFondoReparo->Text);
            if ($this->txtAnticipoFinanciero->Text != "0,00") {
                $certificacion->AnticipoFinanciero = $this->txtAnticipoFinanciero->Text;
            } else {
                $certificacion->AnticipoFinanciero = null;
            }
            if ($this->txtPorcentajeAvanceReal->Text != "") {
                $certificacion->PorcentajeAvanceReal = $this->txtPorcentajeAvanceReal->Text;
            } else {
                $certificacion->PorcentajeAvanceReal = null;
            }
            if ($this->dtpFechaMedicion->Text != "") {
                $fecha = explode("/", $this->dtpFechaMedicion->Text);
                $certificacion->FechaMedicion = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
            } else {
                $certificacion->FechaMedicion = null;
            }
            $certificacion->TipoCertificado = 0;
            $certificacion->ImporteNeto = $this->hdnSumaImporteActual->Value + floatval($this->txtAnticipoFinanciero->Text) - floatval($this->txtDescuentoAnticipoActual->Text) - floatval($this->txtFondoReparo->Text);

            try {
                $certificacion->save();
                foreach ($this->dgDatos->Items as $it) {
                    if ($it->tcPrecioTotal->hdnIdCertificacionItem->Value != 0) {
                        $finder = CertificacionItemRecord::finder();
                        $certificacionItem = $finder->findByPk($it->tcPrecioTotal->hdnIdCertificacionItem->Value);
                    } else {
                        $certificacionItem = new CertificacionItemRecord();
                        $certificacionItem->IdContratoItem = $it->tcPrecioTotal->hdnIdContratoItem->value;
                        $certificacionItem->IdCertificacion = $certificacion->IdCertificacion;
                    }
                    $certificacionItem->PorcentajeActual = floatval($it->tcPorcentajeActual->txtPorcentajeActual->Text);
                    $certificacionItem->ImporteActual = floatval($it->tcImporteActual->txtImporteActual->Text);
                    $certificacionItem->save();
                }
                $this->Response->Redirect("?page=Obra.Contrato.Certificacion.HomeAdmin&idc=$idContrato&ido=$idObra");
            } catch (exception $e) {
                $this->Log($e->getMessage(), true);
            }
        }
    }

    public function txtImporteActual_OnTextChanged($sender, $param) {
        $monto = $sender->Text;
        if ($monto != "") {
            $sender->Parent->Parent->tcPorcentajeActual->txtPorcentajeActual->Text = number_format($monto / $sender->Parent->Parent->tcPrecioTotal->txtPrecioTotal->Text * 100, 4, ".", "");
            $sender->Parent->Parent->tcPorcentajeAcum->txtPorcentajeAcum->Text = number_format($sender->Parent->Parent->tcPorcentajeAnterior->txtPorcentajeAnterior->Text + $sender->Parent->Parent->tcPorcentajeActual->txtPorcentajeActual->Text, 4, ".", "");
            $sender->Parent->Parent->tcImporteAcum->txtImporteAcum->Text = number_format($sender->Parent->Parent->tcImporteAnterior->txtImporteAnterior->Text + $monto, 2, ",", ".");
        }
        $this->CalcularTotales();
    }

    public function txtPorcentajeActual_OnTextChanged($sender, $param) {
        $porcentaje = $sender->Text;
        if ($porcentaje != "") {
            $sender->Parent->Parent->tcImporteActual->txtImporteActual->Text = $sender->Parent->Parent->tcPrecioTotal->txtPrecioTotal->Text * $porcentaje / 100;
            $sender->Parent->Parent->tcPorcentajeAcum->txtPorcentajeAcum->Text = number_format($sender->Parent->Parent->tcPorcentajeAnterior->Text + $porcentaje, 4, ".", "");
            $sender->Parent->Parent->tcImporteAcum->txtImporteAcum->Text = number_format($sender->Parent->Parent->tcImporteAnterior->txtImporteAnterior->Text + $sender->Parent->Parent->tcImporteActual->txtImporteActual->Text, 2, ",", ",");
        }
        $this->CalcularTotales();
    }

    public function txtAnticipoFinancieroPorcentaje_OnTextChanged($sender, $param) {
        $porcentaje = $sender->Text;
        if ($porcentaje != "") {
            $this->txtAnticipoFinanciero->Text = $this->txtAnticipoOtorgadoProv->Text = number_format($porcentaje * $this->hdnMontoContrato->Value / 100, 2, ",", ".");
            $this->txtDescuentoAnticipoActual->Text = number_format($this->lblSumaPorcentajeActual->Text * ($this->txtAnticipoAcumulado->Text + $porcentaje * $this->hdnMontoContrato->Value / 100), 2, ",", ".");
        }
    }

    public function txtDescuentoAnticipoActual_OnTextChanged($sender, $param) {
        $monto = $sender->Text;
        if ($monto != "") {
            $this->txtTotalPagoMunicipio->Text = number_format($this->hdnSumaImporteActual->Value - $monto - $this->txtFondoReparo->Text, 2, ",", ".");
            $this->txtTotalPagoProvincia->Text = number_format($this->hdnSumaImporteActual->Value - $monto, 2, ",", ".");
        }
    }

    public function txtFondoReparo_OnTextChanged($sender, $param) {
        $monto = $sender->Text;
        $this->txtTotalPagoMunicipio->Text = number_format($this->hdnSumaImporteActual->Value - $this->txtDescuentoAnticipoActual->Text - $monto, 2, ",", ".");
    }

    public function cvMontoAvance_OnServerValidate($sender, $param) {
        $idObra = $this->Request["ido"];
        $idContrato = $this->Request["idc"];
        $id = $this->Request["id"];
        $idOrdenTrabajo = $this->ddlOrdenTrabajo->SelectedValue;

        if ($idOrdenTrabajo != "" and $idOrdenTrabajo != "0") {
            $data = $this->CreateDataSource("OrdenTrabajoPeer", "PorcentajeAvance", $idOrdenTrabajo, $id);
        } else {
            $data = $this->CreateDataSource("ContratoPeer", "PorcentajeAvance", $idContrato, $id);
        }

        $porcentaje = $data[0]['PorcentajeAvance'] + $this->txtPorcentajeAvance->Text;
        $porcentaje = number_format($porcentaje, 2);

        if (number_format($porcentaje, 2) > '100.00') {
            $param->IsValid = false;
            $this->cvMontoAvance->ErrorMessage = "No puede certificar más del 100% de la obra ($porcentaje %)";
        } else {
            $param->IsValid = true;
        }
    }

    public function CalcularTotales() {
        $sumaPrecioTotal = $incidenciaTotal = $sumaImporteAnterior = $sumaImporteActual = $sumaImporteAcum = 0;
        $items = $this->dgDatos->Items;
        foreach ($items as $i => $item) {
            if (strpos($item->tcPrecioTotal->txtPrecioTotal->CssClass,'padre') > 0) {
                $padre = $i;
                $items[$padre]->tcPrecioTotal->txtPrecioTotal->Text = 0;  
                $items[$padre]->tcIncidencia->txtIncidencia->Text = 0;
                $items[$padre]->tcIncidenciaPorcentaje->txtIncidenciaPorcentaje->Text = 0;
                $items[$padre]->tcImporteAnterior->txtImporteAnterior->Text = 0;
                $items[$padre]->tcPorcentajeAnterior->txtPorcentajeAnterior->Text = 0;
                $items[$padre]->tcImporteActual->txtImporteActual->Text = 0;
                $items[$padre]->tcPorcentajeActual->txtPorcentajeActual->Text = 0;
                $items[$padre]->tcImporteAcum->txtImporteAcum->Text = 0;
                $items[$padre]->tcPorcentajeAcum->txtPorcentajeAcum->Text = 0;
            } else {
                if (strpos($item->tcPrecioTotal->txtPrecioTotal->CssClass,'hijo') > 0) {
                    $items[$padre]->tcPrecioTotal->txtPrecioTotal->Text =
                        floatval($items[$padre]->tcPrecioTotal->txtPrecioTotal->Text) + floatval($item->tcPrecioTotal->txtPrecioTotal->Text);
                    $items[$padre]->tcIncidencia->txtIncidencia->Text =
                        floatval($items[$padre]->tcIncidencia->txtIncidencia->Text) + floatval($item->tcIncidencia->txtIncidencia->Text);
                    $items[$padre]->tcIncidenciaPorcentaje->txtIncidenciaPorcentaje->Text =
                        floatval($items[$padre]->tcIncidenciaPorcentaje->txtIncidenciaPorcentaje->Text) + floatval($item->tcIncidenciaPorcentaje->txtIncidenciaPorcentaje->Text);
                    $items[$padre]->tcImporteAnterior->txtImporteAnterior->Text =
                        floatval($items[$padre]->tcImporteAnterior->txtImporteAnterior->Text) + floatval($item->tcImporteAnterior->txtImporteAnterior->Text);
                    $items[$padre]->tcImporteActual->txtImporteActual->Text =
                        floatval($items[$padre]->tcImporteActual->txtImporteActual->Text) + floatval($item->tcImporteActual->txtImporteActual->Text);
                    $items[$padre]->tcImporteAcum->txtImporteAcum->Text =
                        floatval($items[$padre]->tcImporteAcum->txtImporteAcum->Text) + floatval($item->tcImporteAcum->txtImporteAcum->Text);
                    $items[$padre]->tcPorcentajeAnterior->txtPorcentajeAnterior->Text =
                        floatval($items[$padre]->tcPorcentajeAnterior->txtPorcentajeAnterior->Text) + floatval($item->tcPorcentajeAnterior->txtPorcentajeAnterior->Text);
                    $items[$padre]->tcPorcentajeActual->txtPorcentajeActual->Text =
                        floatval($items[$padre]->tcPorcentajeActual->txtPorcentajeActual->Text) + floatval($item->tcPorcentajeActual->txtPorcentajeActual->Text);
                    $items[$padre]->tcPorcentajeAcum->txtPorcentajeAcum->Text =
                        floatval($items[$padre]->tcPorcentajeAcum->txtPorcentajeAcum->Text) + floatval($item->tcPorcentajeAcum->txtPorcentajeAcum->Text);
                }     
                $sumaPrecioTotal+=floatval($item->tcPrecioTotal->txtPrecioTotal->Text);
                $incidenciaTotal+=$item->tcIncidencia->txtIncidencia->Text;
                $sumaImporteAnterior+=floatval($item->tcImporteAnterior->txtImporteAnterior->Text);
                $sumaImporteActual+=floatval($item->tcImporteActual->txtImporteActual->Text);
                $sumaImporteAcum+=floatval($item->tcImporteAcum->txtImporteAcum->Text);
            }
        }
        $this->lblPrecioTotal->Text = number_format($sumaPrecioTotal, 2, ",", ".");
        $this->lblIncidenciaTotal->Text = number_format($incidenciaTotal, 4, ".", "");
        $this->lblIncidenciaPorcentajeTotal->Text = number_format($incidenciaTotal * 100, 4, ".", "");
        $this->lblSumaImporteAnterior->Text = number_format($sumaImporteAnterior, 2, ",", ".");
        $this->lblSumaPorcentajeAnterior->Text = number_format($sumaImporteAnterior / $this->hdnMontoContrato->Value * 100, 4, ".", "");
        $this->lblSumaImporteActual->Text = number_format($sumaImporteActual, 2, ",", ".");
        $this->hdnSumaImporteActual->Value = $sumaImporteActual;
        $this->lblSumaPorcentajeActual->Text = number_format($sumaImporteActual / $this->hdnMontoContrato->Value * 100, 4, ".", "");
        $this->hdnSumaPorcentajeActual->Value = $sumaImporteActual / $this->hdnMontoContrato->Value * 100;
        $this->lblSumaImporteAcum->Text = number_format($sumaImporteAcum, 2, ",", ".");
        $this->lblSumaPorcentajeAcum->Text = number_format($sumaImporteAcum / $this->hdnMontoContrato->Value * 100, 4, ".", "");

        // Activa los cambios realizados del lado del cliente
        $this->cambios->Value++;
    }

    public function dtpPeriodo_OnTextChanged($sender, $param) {

        $idObra = $this->Request["ido"];
        $idContrato = $this->Request["idc"];
        $id = $this->Request["id"];
        $this->LoadDataRelated($idObra, $idContrato, $id);
    }

}

?>