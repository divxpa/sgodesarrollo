<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->txtBusqueda->Text = $this->GetSearchMemory($this->PagePath, $this->txtBusqueda->ID);
			$this->Refresh();
		}

	}

	public function Refresh()
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$busqueda = $this->txtBusqueda->Text;
		$data = $this->CreateDataSource("ObraPeer","ProyectosInversionHome", $idOrganismo, $busqueda);
		$this->lblTitulo->Text = "Proyectos de inversión (". count($data) . ")";
		$this->setViewState("Data",$data);

		if(!$this->Session->exists("ProyectoCurrentPage")){
			$this->LoadDataPage($data, 0);
		}
		else{
			$this->LoadDataPage($data, $this->Session->get("ProyectoCurrentPage"));
		}

		
	}

	public function LoadDataPage($data, $page){
		$this->Session->set("ProyectoCurrentPage", $page);
		$this->dgDatos->CurrentPageIndex= $page;
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();
	}

	public function dgDatos_OnPageIndexChanged($sender,$param){
		$currentPage = $param->NewPageIndex;
		$data = $this->getViewState("Data",array());
		array_slice($data, $currentPage*$this->dgDatos->PageSize,$this->dgDatos->PageSize);
		$this->LoadDataPage($data, $currentPage);
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
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		$this->Session->set("ProyectoCurrentPage", 0);
		$this->Refresh();
	}

	public function btnVerTodos_OnClick($sender, $param)
	{
		$this->txtBusqueda->Text = "";
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		$this->Session->set("ProyectoCurrentPage", 0);
		$this->Refresh();
	}

}

?>