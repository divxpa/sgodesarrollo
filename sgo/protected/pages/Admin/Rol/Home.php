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
		$busqueda = $this->txtBusqueda->Text;
		$data = $this->CreateDataSource("RolPeer","RolesHome", $busqueda);
		$this->dgDatos->DataSource = $data;
		$this->lblTitulo->Text = "Roles (". count($data) . ")";
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);
	}

	public function dgDatos_OnPageIndexChanged($sender, $param){
		$data = $this->getViewState("Data", array());
		//array_slice($data, $param->NewPageIndex * $this->dgDatos->PageSize, $this->dgDatos->PageSize); // esto no hace nada
		$this->dgDatos->CurrentPageIndex = $param->NewPageIndex;
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();
	}

	public function dgDatos_OnPagerCreated($sender, $param){
		$style = $param->Pager->DataGrid->getPagerStyle();
		$pageCount = $param->Pager->DataGrid->getPageCount();
		$pageIndex = $param->Pager->DataGrid->getCurrentPageIndex() + 1;
		$maxButtonCount = $style->getPageButtonCount(); 
		$buttonCount = $maxButtonCount > $pageCount?$pageCount:$maxButtonCount;
		$startPageIndex = 1;
		$endPageIndex = $buttonCount;

		if($pageIndex > $endPageIndex)
		{
			$startPageIndex = ((int)(($pageIndex - 1) / $maxButtonCount)) * $maxButtonCount + 1;
			if (($endPageIndex = $startPageIndex + $maxButtonCount - 1) > $pageCount)
				$endPageIndex = $pageCount;
			if ($endPageIndex - $startPageIndex + 1 < $maxButtonCount)
			{
				if(($startPageIndex=$endPageIndex-$maxButtonCount+1)<1)
					$startPageIndex=1;
			}
		}

		$param->Pager->Controls->insertAt(0, "Páginas " . $startPageIndex . " a " . $endPageIndex . " de " . $pageCount . ": ");
	}

	public function btnBuscar_OnClick($sender, $param)
	{
		$this->buscar();
	}

	public function btnVerTodos_OnClick($sender, $param)
	{
		$this->txtBusqueda->Text = "";
		$this->buscar();
	}

	protected function buscar()
	{
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

}

?>