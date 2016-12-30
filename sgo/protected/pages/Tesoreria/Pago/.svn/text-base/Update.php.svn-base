<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Pago";
				$this->Refresh($id);
			}

		}

	}

	public function LoadDataRelated(){
		$idOrganismo = $this->Session["SPOrganismo"];
		$proveedores = $this->CreateDataSource("ProveedorPeer","ProveedoresConContratoSelect", $idOrganismo);
		$this->ddlProveedor->DataSource = $proveedores;
		$this->ddlProveedor->dataBind();
	}

	public function ddlProveedor_OnSelectedIndexChanged($sender, $param)
	{
		
		if($this->ddlProveedor->SelectedValue!="0" and $this->ddlProveedor->SelectedValue!=""){
			$idOrganismo = $this->Session["SPOrganismo"];
			$idProveedor = $this->ddlProveedor->SelectedValue;
			$certificaciones = $this->CreateDataSource("PagoPeer", "CertificacionesPendientesPago", $idOrganismo, $idProveedor);
			$this->dgDatos->DataSource = $certificaciones;
			$this->dgDatos->dataBind();
			
			if(count($certificaciones)){
				$this->pnlCertificaciones->Display = "Dynamic";
				$this->lblNoData->Text = "";
			}
			else{
				$this->pnlCertificaciones->Display = "None";
				$this->lblNoData->Text = "No existen certificaciones pendientes para el proveedor seleccionado";
			}
			
		}
		else{
			$this->dgDatos->DataSource = array();
			$this->dgDatos->dataBind();
			$this->pnlCertificaciones->Display = "None";
			$this->lblNoData->Text = "";
		}

	}

	public function rblTipoPago_OnSelectedIndexChanged($sender, $param){
		$saldo = str_replace(".", "", $sender->Parent->Parent->bcSaldo->Text);
		$saldo = str_replace(",", ".", $saldo);

		switch ($sender->SelectedValue) {
			case '0':
				$sender->Parent->txtImportePagar->Enabled = false;
				$sender->Parent->txtImportePagar->Text = $saldo;
				break;
			case '1':
				$sender->Parent->txtImportePagar->Enabled = true;
				$sender->Parent->txtImportePagar->Text = $saldo;
				break;
			default:
				$sender->Parent->txtImportePagar->Enabled = false;
				$sender->Parent->txtImportePagar->Text = "";
				break;
		}

		$this->CalcularTotales();
	}

	public function txtImportePagar_OnTextChanged($sender, $param){
		$this->CalcularTotales();
	}

	public function CalcularTotales(){
		$bruto = 0;

		foreach ($this->dgDatos->Items as $it) {
			
			if($it->ItemType==TListItemType::Item or $it->ItemType==TListItemType::AlternatingItem){

				if($it->tcImporte->txtImportePagar->Text!=""){
					$bruto += floatval($it->tcImporte->txtImportePagar->Text);
				}

			}

		}

		$this->txtBruto->Text = number_format($bruto, 2, ".", "");
		$this->txtNeto->Text = number_format($bruto - floatval($this->txtRetenciones->Text), 2, ".", "");
	}

	public function Refresh($idPago){
		$idOrganismo = $this->Session["SPOrganismo"];

		$finder = PagoRecord::finder();
		$pago = $finder->findByPk($idPago);

		if($pago->IdOrganismo!=$idOrganismo){
			$this->Response->Redirect("?page=Tesoreria.Pago.Home");
		}

		$idProveedor = $pago->IdProveedor;
		$this->ddlProveedor->SelectedValue = $pago->IdProveedor;
		$this->ddlProveedor->Enabled = false;
		$this->txtOrdenPago->Text = $pago->OrdenPago;
		$fecha = explode("-", $pago->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->pnlCertificaciones->Display = "Dynamic";
		$certificaciones = $this->CreateDataSource("PagoPeer", "CertificacionesPendientesPagoUpdate", $idOrganismo, $idProveedor, $idPago);
		$this->dgDatos->DataSource = $certificaciones;
		$this->dgDatos->dataBind();
		$this->txtBruto->Text = $pago->ImporteBruto;
		$this->txtRetenciones->Text = $pago->Retenciones;
		$this->txtNeto->Text = $pago->ImporteNeto;
	}

	public function cvOrdenPago_OnServerValidate($sender, $param)
	{
		$idOrganismo = $this->Session["SPOrganismo"];
		$ordenPago = $this->txtOrdenPago->Text;
		
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrganismo = :idorganismo AND OrdenPago like :ordenpago ';
		$criteria->Parameters[':idorganismo'] = $idOrganismo;
		$criteria->Parameters[':ordenpago'] = $ordenPago;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdPago <> :idpago';
			$criteria->Parameters[':idpago'] = $id;
		}

		$finder = PagoRecord::finder();
		$pago = $finder->find($criteria);

		if (is_object($pago)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Tesoreria.Pago.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idOrganismo = $this->Session["SPOrganismo"];

			if(!is_null($id)){
				$finder = PagoRecord::finder();
				$pago = $finder->findByPk($id);
			}
			else{
				$pago = new PagoRecord();
				$pago->IdOrganismo = $idOrganismo;
				$pago->IdProveedor = $this->ddlProveedor->SelectedValue;
			}

			$pago->OrdenPago = $this->txtOrdenPago->Text;
			$fecha = explode("/", $this->dtpFecha->Text);
			$pago->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$pago->ImporteBruto = $this->txtBruto->Text;
			$pago->Retenciones = $this->txtRetenciones->Text;
			$pago->ImporteNeto = $this->txtNeto->Text;

			try{
				$pago->save();

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdPago = :idpago ';
				$criteria->Parameters[':idpago'] = $pago->IdPago;
				$finder = PagoCertificacionRecord::finder();
				$pagosCertificacion = $finder->findAll($criteria);

				foreach($pagosCertificacion as $pc){
					$pc->delete();
				}

				foreach ($this->dgDatos->Items as $it) {
			
					if($it->ItemType==TListItemType::Item or $it->ItemType==TListItemType::AlternatingItem){

						if($it->tcImporte->txtImportePagar->Text!=""){
							$idCertificacion = $it->bcIdCertificacion->Text;
							$importe = $it->tcImporte->txtImportePagar->Text;
							$pagoCertificacion = new PagoCertificacionRecord();
							$pagoCertificacion->IdPago = $pago->IdPago;
							$pagoCertificacion->IdCertificacion = $idCertificacion;
							$pagoCertificacion->Importe = $importe;
							$pagoCertificacion->save();
						}

					}

				}

				$this->Response->Redirect("?page=Tesoreria.Pago.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>