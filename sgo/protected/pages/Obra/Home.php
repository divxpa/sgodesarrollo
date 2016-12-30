<?php
class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = '';

			if(isset($this->Request['id'])){
				$idObra = $this->Request['id'];
			}

			$this->LoadDataRelated();
			$this->ddlLocalidad->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlLocalidad->ID);
			$this->ddlFufi->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlFufi->ID);
			$this->ddlEstado->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlEstado->ID);
			$this->txtBusqueda->Text = $this->GetSearchMemory($this->PagePath, $this->txtBusqueda->ID);
			$this->Refresh($idObra);
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
		$this->ddlEstado->DataSource = $estados;
		$this->ddlEstado->dataBind();

		$fuentesfinanciamiento = $this->CreateDataSource("ObraPeer","FufisConObraSelect", $idOrganismo);
		$this->ddlFufi->DataSource = $fuentesfinanciamiento;
		$this->ddlFufi->dataBind();

		$idRol = $this->Session->get("usr_sgo_idRol");
		$alarmas = $this->CreateDataSource("RolPeer","Alarmas", $idRol);
		$this->setViewState("Alarmas", $alarmas);
	}

	public function Refresh($idObra='')
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$idLocalidad = $this->ddlLocalidad->SelectedValue;
		$idFufi = $this->ddlFufi->SelectedValue;
		$idEstado = $this->ddlEstado->SelectedValue;
		$busqueda = $this->txtBusqueda->Text;
		$codigoOrganismo = $this->txtcodigoOrganismo->Text;
		$codigoObra = $this->txtcodigoObra->Text;

		// $data = $this->CreateDataSource("ObraPeer","ObrasHome", $idOrganismo, $idLocalidad, $idFufi, $idEstado, $busqueda, $idObra);
		//Agregado busqueda por codigo
		$data = $this->CreateDataSource("ObraPeer","ObrasHome", $idOrganismo, $idLocalidad, $idFufi, $idEstado, $busqueda, $idObra, $codigoOrganismo, $codigoObra);


		$this->lblTitulo->Text = "Obras (". count($data) . ")";
		$this->setViewState("Data",$data);

		if(!$this->Session->exists("ObraCurrentPage")){
			$this->LoadDataPage($data, 0);
		}
		else{
			$this->LoadDataPage($data, $this->Session->get("ObraCurrentPage"));
		}

		
	}

	public function LoadDataPage($data, $page){
		$this->Session->set("ObraCurrentPage", $page);
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
		$this->SaveSearchMemory($this->PagePath, $this->ddlLocalidad->ID, $this->ddlLocalidad->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlFufi->ID, $this->ddlFufi->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlEstado->ID, $this->ddlEstado->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->txtcodigoOrganismo->ID, $this->txtcodigoOrganismo->Text);
		$this->SaveSearchMemory($this->PagePath, $this->txtcodigoObra->ID, $this->txtcodigoObra->Text);		
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		//$this->dgDatos->CurrentPageIndex = 0;
		$this->Session->set("ObraCurrentPage", 0);
		$this->Refresh();
	}

	public function btnVerTodos_OnClick($sender, $param)
	{
		$this->ddlLocalidad->SelectedValue = 
		$this->ddlFufi->SelectedValue = 
		$this->ddlEstado->SelectedValue = 0;
		$this->txtcodigoOrganismo->Text = "";
		$this->txtcodigoObra->Text = "";
		$this->txtBusqueda->Text = "";


		$this->SaveSearchMemory($this->PagePath, $this->ddlLocalidad->ID, $this->ddlLocalidad->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlFufi->ID, $this->ddlFufi->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->ddlEstado->ID, $this->ddlEstado->SelectedValue);
		$this->SaveSearchMemory($this->PagePath, $this->txtcodigoOrganismo->ID, $this->txtcodigoOrganismo->Text);
		$this->SaveSearchMemory($this->PagePath, $this->txtcodigoObra->ID, $this->txtcodigoObra->Text);
		$this->SaveSearchMemory($this->PagePath, $this->txtBusqueda->ID, $this->txtBusqueda->Text);
		
		//$this->dgDatos->CurrentPageIndex = 0;
		$this->Session->set("ObraCurrentPage", 0);
		$this->Refresh();
	}

	public function dgDatos_OnItemDataBound($sender,$param){
		
		if($param->Item->ItemType==TListItemType::Item || $param->Item->ItemType==TListItemType::AlternatingItem){
			
			if(isset($this->Application->Parameters["alarmaEnabled"])){
				$enabled = $this->Application->Parameters["alarmaEnabled"];

				if($enabled){
					$flag = false;
					$alarmas = $this->getViewState("Alarmas", array());
					$idObra = $param->Item->bcAlarma->Text;

					for($i=0; $i<count($alarmas) && !$flag; $i++){
						$query = $alarmas[$i]["Query"];
						$query = str_replace("order by", "and o.IdObra=$idObra order by", $query);
						$result = $this->ExecuteQuery($query);

						if(count($result)){
							$flag = true;
						}

					}

					if($flag){
						$param->Item->bcAlarma->Text = "<a title='El sistema ha detectado que existen alarmas pendientes para la obra' href=\"javascript:OpenWindow('?page=DetalleAlarmaObra&id=$idObra',600,400);\"><img src='themes/serviciospublicos/images/alarma-3.png' width='24px' /></a>";
					}
					else{
						$param->Item->bcAlarma->Text = "";
					}

				}
				else{
					$param->Item->bcAlarma->Text = "";
				}

			}
			else{
				$param->Item->bcAlarma->Text = "";
			}

			$saldo = str_replace('.', '', $param->Item->bcSaldoPresup->Text);
			$saldo = floatval(str_replace(',', '.', $saldo));

			if($saldo<0){
				$param->Item->bcSaldoPresup->Style = 'color: red';
			}

		}
		else{
			$param->Item->bcAlarma->Text = "";
		}

	}

}

?>