<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$this->Refresh($idObra, $idContrato);
		}

	}

	public function Refresh($idObra, $idContrato)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$this->btnAgregar->NavigateUrl.="&ido=$idObra&idc=$idContrato";
		$this->btnAgregar->Visible = $this->ValidarComitente($idOrganismo, $idObra);
		$this->hlkVolver->NavigateUrl.="&id=$idObra";

		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra, true)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = ContratoRecord::finder();
		$contrato = $finder->findByPk($idContrato);

		$finder = ProveedorRecord::finder();
		$proveedor = $finder->findByPk($contrato->IdProveedor);

		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $idObra);
		$this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion . " - " .$localidades[0]["Localidades"];
		$this->lblContrato->Text = $contrato->Numero . " - " . $proveedor->Cuit . " " . $proveedor->RazonSocial;
		$data = $this->CreateDataSource("CertificacionPeer","CertificacionesHome", $idContrato, $idOrganismo);
		$this->dgDatos->DataSource = $data;
		$this->lblTitulo->Text = "Certificaciones de Contrato (". count($data) . ")";
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);
	}

	public function dgDatos_OnPageIndexChanged($sender,$param){
		$data = $this->getViewState("Data",array());
		array_slice($data, $param->NewPageIndex*$this->dgDatos->PageSize,$this->dgDatos->PageSize);
		$this->dgDatos->CurrentPageIndex=$param->NewPageIndex;
		$this->dgDatos->DataSource=$data;
		$this->dgDatos->dataBind();
	}

	public function dgDatos_OnPagerCreated($sender,$param){
		$style=$param->Pager->DataGrid->getPagerStyle();
		$pageCount=$param->Pager->DataGrid->getPageCount();
		$pageIndex=$param->Pager->DataGrid->getCurrentPageIndex()+1;
		$maxButtonCount=$style->getPageButtonCount();
		$buttonCount=$maxButtonCount>$pageCount?$pageCount:$maxButtonCount;
		$startPageIndex=1;
		$endPageIndex=$buttonCount;

		if($pageIndex>$endPageIndex)
		{
			$startPageIndex=((int)(($pageIndex-1)/$maxButtonCount))*$maxButtonCount+1;
			if(($endPageIndex=$startPageIndex+$maxButtonCount-1)>$pageCount)
				$endPageIndex=$pageCount;
			if($endPageIndex-$startPageIndex+1<$maxButtonCount)
			{
				if(($startPageIndex=$endPageIndex-$maxButtonCount+1)<1)
					$startPageIndex=1;
			}
		}

		$param->Pager->Controls->insertAt(0,"Páginas " . $startPageIndex . " a " . $endPageIndex . " de " . $pageCount . ": ");
	}

	public function btnBuscar_OnClick($sender, $param)
	{
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

	public function btnVerTodos_OnClick($sender, $param)
	{
		$this->ddlLocalidad->SelectedValue = 
		$this->ddlFufi->SelectedValue = 
		$this->ddlEstado->SelectedValue = 0;
		$this->txtBusqueda->Text = "";
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

}

?>