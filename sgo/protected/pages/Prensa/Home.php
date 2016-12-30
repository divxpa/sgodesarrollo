<?php

class Home extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['Nombre'] = 'asc';
			$criteria = new TActiveRecordCriteria;
			$criteria->Condition = ' Aniversario is not null ';

			$finder = LocalidadRecord::finder();
			$localidades = $finder->findAll($criteria);
			$this->ddlLocalidad->DataSource = $localidades;
			$this->ddlLocalidad->dataBind();

			$aniversarios = $this->CreateDataSource("LocalidadPeer","Aniversarios");

			foreach ($aniversarios as $a) {
				$fecha = explode("-", $a["Aniversario"]);
				$titulo = $a["Nombre"];
				$nombre = "<a href=\"javascript:OpenWindow('?page=Prensa.Aniversario&id=".$a["IdLocalidad"]."', 1100, 600);\">".$a["Nombre"]."</a>";
				$this->Calendario->addEvent($titulo, $nombre, intval($fecha[1]), intval($fecha[2]));
			}

			$this->Calendario->RefreshCalendar();
		}

	}

	public function btnBuscar_OnClick($sender, $param)
	{
		$idLocalidad = $this->ddlLocalidad->SelectedValue;
		//echo "<script>alert('hola');</script>";
		$this->Page->ClientScript->registerEndScript('openLocalidad', "OpenWindow('?page=Prensa.Aniversario&id=$idLocalidad', 1100, 600)");
	}

}

?>