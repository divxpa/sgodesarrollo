<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$this->ddlProveedor->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlProveedor->ID);
			$this->dtpFechaDesde->Text = $this->GetSearchMemory($this->PagePath, $this->dtpFechaDesde->ID);
			$this->dtpFechaHasta->Text = $this->GetSearchMemory($this->PagePath, $this->dtpFechaHasta->ID);
			$this->txtBusqueda->Text = $this->GetSearchMemory($this->PagePath, $this->txtBusqueda->ID);
			$this->Refresh();
		}

	}

	public function LoadDataRelated(){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$proveedores = $this->CreateDataSource("ProveedorPeer","ProveedoresConPagoSelect", $idOrganismo);
		$this->ddlProveedor->DataSource = $proveedores;
		$this->ddlProveedor->dataBind();
	}

	public function Refresh()
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$idProveedor = $this->ddlProveedor->SelectedValue;
		$fechaDesde = $this->dtpFechaDesde->Text;
		$fechaHasta = $this->dtpFechaHasta->Text;
		$busqueda = $this->txtBusqueda->Text;

		$data = $this->CreateDataSource("PagoPeer","PagosHome", $idOrganismo, $idProveedor, $fechaDesde, $fechaHasta, $busqueda);
		$this->dgDatos->DataSource = $data;
		$this->lblTitulo->Text = "Pagos (". count($data) . ")";
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
		$this->SaveSearchMemory($this->PagePath, $this->ddlProveedor->ID, $this->ddlProveedor->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->dtpFechaDesde->ID, $this->dtpFechaDesde->Text);
		$this->SaveSearchMemory($this->PagePath, $this->dtpFechaHasta->ID, $this->dtpFechaHasta->Text);
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

	public function btnVerTodos_OnClick($sender, $param)
	{
		$this->ddlProveedor->SelectedValue = 0;
		$this->dtpFechaDesde->Text = 
		$this->dtpFechaHasta->Text = 
		$this->txtBusqueda->Text = "";

		$this->SaveSearchMemory($this->PagePath, $this->ddlProveedor->ID, $this->ddlProveedor->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->dtpFechaDesde->ID, $this->dtpFechaDesde->Text);
		$this->SaveSearchMemory($this->PagePath, $this->dtpFechaHasta->ID, $this->dtpFechaHasta->Text);
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

}

?>