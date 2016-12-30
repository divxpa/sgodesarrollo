<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$this->LoadDataRelated($idObra, $idContrato);
			//$id = $this->Request["id"];

			if (!is_null($idContrato)) {
				$this->lblAccion->Text = "Modificar item de Contrato";
				$this->Refresh($idContrato);
			}
		}
	}

	public function LoadDataRelated($idObra, $idContrato){
		//Lista de Items padre
		//Proximo nro de orden
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

	public function Refresh($idContrato){

		$this->lblAccion->Text = "Nuevo item de Contrato";
		$orden = $this->CreateDataSource("ContratoPeer", "SiguienteNumeroOrden", $idContrato);
		$this->txtOrden->Text = $orden[0]["Orden"];
		if ($orden>1){					
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['Orden'] = 'asc';
			$criteria = new TActiveRecordCriteria;
			$criteria->Condition = 'IdContrato = :idcontrato ';
			$criteria->Parameters[':idcontrato'] = $idContrato;
			$finder = ContratoItemPadreRecord::finder();
			$items = $finder->findAll($criteria);
			$this->ddlItemPadre->DataSource = $items;
			$this->ddlItemPadre->dataBind();	
			//if (count($items) != 0) {
			//	$this->pnlItemPadre->Visible = "true";	
			//}					
			}

		$data = $this->CreateDataSource("ContratoPeer","ItemsByContratoConUnidadMedida", $idContrato);
		 		$this->dgItems->DataSource = $data;
		 		$this->dgItems->dataBind();		 
		 		if(count($data)){
		 		$this->lblItems->Visible = false;
		}
	}

	public function LimpiarCampos(){
		$this->txtItem->Text = "";
		$this->txtCantidad->Text = "";
		$this->ddlUnidadDeMedida->SelectedValue = 0;
		$this->txtPrecioUnitario->Text = "";
		$this->txtPrecioTotal->Text = "";
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		//$this->Response->Redirect("?page=Obra.UpdateAdmin&id=$ido&idc=$idc");
		$this->Response->Redirect("?page=Obra.HomeAdmin");
	}

	public function btnAceptar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];

			if($this->chkEsPadre->Checked){
				if(!is_null($id)){
					$finder = ContratoItemPadreRecord::finder();
					$contratoitempadre = $finder->findByPk($id);
				}
				else{
					$contratoitempadre = new ContratoItemPadreRecord();
				}
				$contratoitempadre->IdContrato = $idContrato;
				$contratoitempadre->Item = $this->txtItem->Text;
				$contratoitempadre->Orden = $this->txtOrden->Text;
			}
			else
			{
				if(!is_null($id)){
					$finder = ContratoItemRecord::finder();
					$contratoitem = $finder->findByPk($id);
				}
				else{
					$contratoitem = new ContratoItemRecord();
				}
				$contratoitem->IdContrato = $idContrato;
				$contratoitem->Orden = $this->txtOrden->Text;
				$contratoitem->Item = $this->txtItem->Text;
				$contratoitem->Cantidad = $this->txtCantidad->Text;
				$contratoitem->UnidadMedida = $this->ddlUnidadDeMedida->SelectedValue;
				$contratoitem->PrecioUnitario = $this->txtPrecioUnitario->Text;
				$contratoitem->PrecioTotal = $this->txtPrecioTotal->Text;
			}

			try{
				if($this->chkEsPadre->Checked){
					$contratoitempadre->save();
				}
				else
				{
					$contratoitem->save();
				}


				$contratoitem->save();
				//$this->Response->Redirect("?page=Obra.UpdateAdmin&id=$ido&idc=$idc");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}
		}
	}

	public function btnAgregarItem_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];

			if($this->chkEsPadre->Checked){
				if(!is_null($id)){
					$finder = ContratoItemPadreRecord::finder();
					$contratoitempadre = $finder->findByPk($id);
				}
				else{
					$contratoitempadre = new ContratoItemPadreRecord();
				}
				$contratoitempadre->IdContrato = $idContrato;
				$contratoitempadre->Item = $this->txtItem->Text;
				$contratoitempadre->Orden = $this->txtOrden->Text;
			}
			else
			{
				if(!is_null($id)){
					$finder = ContratoItemRecord::finder();
					$contratoitem = $finder->findByPk($id);
				}
				else{
					$contratoitem = new ContratoItemRecord();
				}
				$contratoitem->IdContrato = $idContrato;
				$contratoitem->Orden = $this->txtOrden->Text;
				$contratoitem->Item = $this->txtItem->Text;
				$contratoitem->Cantidad = $this->txtCantidad->Text;
				$contratoitem->UnidadMedida = $this->ddlUnidadDeMedida->SelectedValue;
				$contratoitem->PrecioUnitario = $this->txtPrecioUnitario->Text;
				$contratoitem->PrecioTotal = $this->txtPrecioTotal->Text;
			}

			try{
				$contratoitem->save();
				$this->Refresh($idContrato);
				$this->LimpiarCampos();
				//if($this->chkEsPadre->Checked){
					//$contratoitempadre->save();
				}
				//else
				//{
				//	$contratoitem->save();
				//}
				
				//$this->Response->Redirect("?page=Obra.UpdateAdmin&id=$ido&idc=$idc");
			//}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}
		}
	}

	public function chkEsPadre_OnCheckedChanged($sender, $param)
	{		
		if($this->chkEsPadre->Checked){
			$this->pnlItem->Display = "None";
			$this->pnlItemPadre->Display = "None";
			//$this->ddlItemPadre->SelectedValue = 0;
			$this->ddlItemPadre->dataBind();
			//Controlar el ID de ItemPadre
		}
		else{
			$this->pnlItem->Display = "Dynamic";
			$this->pnlItemPadre->Display = "Dynamic";
		}
	}

	public function selectionChanged($sender,$param)
    {
       if ($this->ddlItemPadre->SelectedValue!="0"){
       		$this->pnlItem->Display = "Dynamic";
       }
       else{
       		$this->pnlItem->Display = "None";
       }

    }

}

?>
