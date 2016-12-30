<?php
class Aniversario extends PageBaseSP{

	public function onPreInit($param){
		parent::onPreInit($param);
		$this->MasterClass = "Application.layouts.DialogLayout";
	}

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];
            $this->LoadDataRelated();
			$this->ddlOrganismo->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlOrganismo->ID);
			$this->ddlEstado->SelectedValue = $this->GetSearchMemory($this->PagePath, $this->ddlEstado->ID);

			$this->Refresh($id);
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
                
        $criteriaE = new TActiveRecordCriteria;
		$criteriaE->OrdersBy['Descripcion'] = 'asc';
		$finderE = EstadoObraRecord::finder();
                $this->ddlEstado->PromptValue="0";
		$this->ddlEstado->PromptText="Seleccione";
		$estados = $finderE->findAll($criteriaE);
		$this->ddlEstado->DataSource = $estados;
		$this->ddlEstado->dataBind();
	}
	public function Refresh($id)
	{

		$finder = LocalidadRecord::finder();
		$localidad = $finder->findByPk($id);
		$idOrganismo = $this->ddlOrganismo->SelectedValue;
        $idEstadoObra=  $this->ddlEstado->SelectedValue;
        $fechaDesde = $this->ddlFechaDesde->SelectedValue;
        $fechaHasta = $this->ddlFechaHasta->SelectedValue;
		$this->lblLocalidad->Text = 
		$this->lblLocalidad2->Text = $localidad->Nombre;
		$fecha = explode("-", $localidad->Aniversario);
		
		if(count($fecha)==3){
			$this->lblAniversario->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}
		
		$cargoAutoridad = "";

		switch ($localidad->Categoria) {
			case 1:
				$cargoAutoridad = "Intendente";
				$this->lblCategoria->Text = "Municipalidad de 1° Categoría";
				break;
			case 2:
				$cargoAutoridad = "Intendente";
				$this->lblCategoria->Text = "Municipalidad de 2° Categoría";
				break;
			case 3:
				$cargoAutoridad = "Intendente";
				$this->lblCategoria->Text = "Municipalidad de 3° Categoría";
				break;
			case 4:
				$cargoAutoridad = "Presidente";
				$this->lblCategoria->Text = "Comisión de Fomento";
				break;
		}

		$this->lblAutoridades->Text = $cargoAutoridad . " - " . $localidad->Autoridad;
		$this->lblHabitantes->Text = number_format($localidad->Habitantes, 0, "", ".");

		$this->lblEscudo->Text = "Escudo de la localidad";

		if(!is_null($localidad->FotoEscudo) and $localidad->FotoEscudo!=""){
			$this->imgEscudo->ImageUrl = $localidad->FotoEscudo;
		}
		else{
			$this->imgEscudo->ImageUrl = "upload/localidad/no_image.jpg";
		}

		$this->lblAutoridades2->Text = $cargoAutoridad . " - " . $localidad->Autoridad;

		if(!is_null($localidad->FotoAutoridad) and $localidad->FotoAutoridad!=""){
			$this->imgAutoridad->ImageUrl = $localidad->FotoAutoridad;
		}
		else{
			$this->imgAutoridad->ImageUrl = "upload/localidad/no_image.jpg";
		}

		$this->lblLocalidad3->Text = "Fotografía de la localidad";

		if(!is_null($localidad->FotoLocalidad) and $localidad->FotoLocalidad!=""){
			$this->imgLocalidad->ImageUrl = $localidad->FotoLocalidad;
		}
		else{
			$this->imgLocalidad->ImageUrl = "upload/localidad/no_image.jpg";
		}
	if(!is_null($localidad->MarcaLocalidad) and $localidad->MarcaLocalidad!=""){
			$this->imgMarca->ImageUrl = $localidad->MarcaLocalidad;
		}
		else{
			$this->imgMarca->ImageUrl = "upload/localidad/marcaBlanca.png";
		}
		$selectedValues = $this->chkColumns->SelectedValues;
                $this->setViewState("Columns",$selectedValues);
		
		$data = $this->CreateDataSource("ObraPeer","ObrasByLocalidad", $id,$idOrganismo,$idEstadoObra,$fechaDesde, $fechaHasta);

//		echo"<pre>";print_r($data);echo"</pre>";exit;
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);
		$monto =
		$beneficiarios = 
		$manoobra = 0;

		foreach ($data as $d) {
			$monto += $d["Monto"];
			$beneficiarios += $d["CantidadBeneficiarios"];
			$manoobra += $d["CantidadManoObra"];
		}

		$this->lblTotalObras->Text = count($data);
		$this->lblTotalMonto->Text = "$ ".number_format($monto, 2, ",", ".");
		/*$this->lblTotalBeneficiarios->Text = number_format($beneficiarios, 0, "", ".");
		$this->lblTotalManoObra->Text = number_format($manoobra, 0, "", ".");*/
	}

	public function btnPdf_OnClick($sender, $param)
	{
		$obras = $this->getViewState("Data", array());
                $columns=  $this->getViewState("Columns",array());
                
		$data = array(
					"Localidad" => $this->lblLocalidad->Text,
					"Aniversario" => $this->lblAniversario->Text,
					"Categoria" => $this->lblCategoria->Text,
					"Autoridades" => $this->lblAutoridades->Text,
					"Habitantes" => $this->lblHabitantes->Text,
					"FotoEscudo" => $this->imgEscudo->ImageUrl,
					"FotoLocalidad" => $this->imgLocalidad->ImageUrl,
					"FotoAutoridad" => $this->imgAutoridad->ImageUrl,
					"CantidadObras" => $this->lblTotalObras->Text,
					"MontoTotal" => $this->lblTotalMonto->Text,
                                        "Marca"=>  $this->imgMarca->ImageUrl,
					/*"TotalBeneficiarios" => $this->lblTotalBeneficiarios->Text,
					"TotalManoObra" => $this->lblTotalManoObra->Text,*/
					"Obras" => $obras,
                                        "Columns"=>$columns
				);

		$file = $this->GenerarReportePrensa("AniversarioReport", "A4-L", array(), $data);
		$this->CallbackClient->callClientFunction("Imprimir",array($file));
	}

	public function btnBuscar_OnClick($sender, $param)
	{
		$this->SaveSearchMemory($this->PagePath, $this->ddlOrganismo->ID, $this->ddlOrganismo->SelectedValue);
        $this->SaveSearchMemory($this->PagePath, $this->ddlEstado->ID, $this->ddlEstado->SelectedValue);
		//$this->dgDatos->CurrentPageIndex = 0;
		$this->Refresh($this->Request["id"]);
	}
         public function toggleColumnVisibility($sender,$param)
        {
            foreach($this->dgDatos->Columns as $index=>$column)
                $column->Visible=$sender->Items[$index]->Selected;
            $this->Refresh($this->Request["id"]);
        }
}

?>
