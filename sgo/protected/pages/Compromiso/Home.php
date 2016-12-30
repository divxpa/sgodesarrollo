<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->txtBusqueda->Text = $this->GetSearchMemory($this->PagePath, $this->txtBusqueda->ID);
			$this->LoadDataRelated();
			$this->ddlLocalidad->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlLocalidad->ID);
			$this->ddlOrganismo->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlOrganismo->ID);
			$this->ddlResponsable->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlResponsable->ID);
			$this->txtBusqueda->Text = $this->GetSearchMemory($this->PagePath, $this->txtBusqueda->ID);

			$this->Refresh();
		}


	}

	public function Refresh()
	{
		$idLocalidad = $this->ddlLocalidad->SelectedValue;
		$idOrganismo = $this->ddlOrganismo->SelectedValue;
		$idResponsable = $this->ddlResponsable->SelectedValue;
		$busqueda = $this->txtBusqueda->Text;

		$data = $this->CreateDataSource("CompromisoPeer","CompromisoHome", $idLocalidad, $idOrganismo,
			$idResponsable, $busqueda);

		$this->dgDatos->DataSource = $data;
		$this->lblTitulo->Text = "Compromisos (". count($data) . ")";
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);


	}

	public function LoadDataRelated(){
		$actualizacion = $this->CreateDataSource("CompromisoPeer","UltimaCreacion");
		$this->dgDatos->DataSource = $actualizacion;
		$this->lblActualizacion->Text = 'Última Actualización: ' . $actualizacion[0]["ultimo"];
		$actualizacion = $this->CreateDataSource("CompromisoPeer","UltimaRevision");
		$this->lblUltimaRevision->Text = 'Última Revisión: ' . $actualizacion[0]["ultimo"];
	
		$localidades = $this->CreateDataSource("CompromisoPeer","LocalidadesConCompromisoSelect");
		$this->ddlLocalidad->DataSource = $localidades;
		$this->ddlLocalidad->dataBind();

		$organismo = $this->CreateDataSource("CompromisoPeer","OrganismosConCompromisoSelect");
		$this->ddlOrganismo->DataSource = $organismo;
		$this->ddlOrganismo->dataBind();
		
		$responsables = $this->CreateDataSource("CompromisoResponsablePeer","CompromisoResponsableSelect");
		$this->ddlResponsable->DataSource = $responsables;
		$this->ddlResponsable->dataBind();
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
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh();
	}

public function btnVerTodos_OnClick($sender, $param)
	{
		$this->ddlLocalidad->SelectedValue = 
		$this->ddlOrganismo->SelectedValue = 
		$this->ddlResponsable->SelectedValue = 0;
		$this->txtBusqueda->Text = "";

		$this->SaveSearchMemory($this->PagePath, $this->ddlLocalidad->ID, $this->ddlLocalidad->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlOrganismo->ID, $this->ddlOrganismo->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlResponsable->ID, $this->ddlResponsable->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		
		$this->Refresh();
	}
}

?>