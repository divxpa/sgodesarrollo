<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$this->LoadDataRelated($idObra);
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Refuerzo de Partida Presupuestaria";
				$this->Refresh($id, $idObra);
			}

		}

	}

	public function LoadDataRelated($idObra){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $idObra);
		$this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion . " - " .$localidades[0]["Localidades"];
	}

	public function Refresh($idRefuerzo, $idObra){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra, true)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = RefuerzoPartidaRecord::finder();
		$refuerzoPartida = $finder->findByPk($idRefuerzo);

		$fecha = explode("-", $refuerzoPartida->Fecha);
		$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$this->txtNLAprobacion->Text = $refuerzoPartida->NormaLegal;
		$this->txtImporte->Text = $refuerzoPartida->Importe;
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$this->Response->Redirect("?page=Obra.Update&id=$ido");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];

			if(!is_null($id)){
				$finder = RefuerzoPartidaRecord::finder();
				$refuerzoPartida = $finder->findByPk($id);
			}
			else{
				$refuerzoPartida = new RefuerzoPartidaRecord();
				$refuerzoPartida->IdObra = $idObra;
			}

			$fecha = explode("/", $this->dtpFecha->Text);
			$refuerzoPartida->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			$refuerzoPartida->NormaLegal = $this->txtNLAprobacion->Text;
			$refuerzoPartida->Importe = $this->txtImporte->Text;

			try{
				$refuerzoPartida->save();
				$this->Response->Redirect("?page=Obra.Update&id=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>