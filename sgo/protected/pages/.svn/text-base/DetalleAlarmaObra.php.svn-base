<?php
class DetalleAlarmaObra extends PageBaseSP{

	public function onPreInit($param){
		parent::onPreInit($param);
		$this->MasterClass = "Application.layouts.DialogLayout";
	}

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];
			$this->Refresh($id);
		}

	}

	public function Refresh($id)
	{
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($id);
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $id);
		$this->lblObra->Text = $obra->Denominacion . " - " .$localidades[0]["Localidades"];

		$idRol = $this->Session["SPIdRol"];
		$alarmas = $this->CreateDataSource("RolPeer","Alarmas", $idRol);
		$data = array();

		for($i=0; $i<count($alarmas); $i++){
			$query = $alarmas[$i]["Query"];
			$query = str_replace("order by", "and o.IdObra=$id order by", $query);
			$result = $this->ExecuteQuery($query);

			if(count($result)){
				$img = "<img src='themes/serviciospublicos/images/".$result[0]["Icono"].".png' width='24px' />";
				$data[] = array(
							"Icono" => $img,
							"Alarma" => $alarmas[$i]["Nombre"]
						);
			}

		}

		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);
	}

	public function btnPdf_OnClick($sender, $param)
	{
		$alarmas = $this->getViewState("Data", array());
		$data = array(
					"Alarma" => $this->lblAlarma->Text,
					"Alarmas" => $alarmas
				);
		$file = $this->GenerarReporte("AlarmasReport", "A4", array(), $data);
		$this->CallbackClient->callClientFunction("Imprimir",array($file));
	}

}

?>