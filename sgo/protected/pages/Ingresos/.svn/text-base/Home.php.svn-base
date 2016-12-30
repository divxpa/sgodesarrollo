<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$this->ddlOrganismo->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlOrganismo->ID);
			$this->ddlOrden->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlOrden->ID);
			$this->ddlTipoOrden->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlTipoOrden->ID);
			$this->Refresh();
		}

	}

	public function LoadDataRelated(){
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = OrganismoRecord::finder();
		$this->ddlOrganismo->PromptValue="0";
		$this->ddlOrganismo->PromptText="Seleccione";
		$organismos = $finder->findAll($criteria);
		$this->ddlOrganismo->DataSource = $organismos;
		$this->ddlOrganismo->dataBind();
	}

	public function Refresh()
	{
		$idOrganismo = $this->ddlOrganismo->SelectedValue;
		$orden = $this->ddlOrden->SelectedValue;
		$tipo = $this->ddlTipoOrden->Text;
		$data = $this->CreateDataSource("UsuarioPeer","IngresosHome", $idOrganismo, $orden, $tipo);
		$this->dgDatos->DataSource = $data;
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
		$this->SaveSearchMemory($this->PagePath, $this->ddlOrganismo->ID, $this->ddlOrganismo->SelectedValue);
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

}

?>