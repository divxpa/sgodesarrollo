<?php
class DetalleAlarma extends PageBaseSP{

	public function onPreInit($param){
		parent::onPreInit($param);
		$this->MasterClass = "Application.layouts.DialogLayout";
	}

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["id"];
			$idOrganismo = $this->Session["SPOrganismo"];
			$this->Refresh($id, $idOrganismo);
		}

	}

	public function Refresh($id, $idOrganismo)
	{
		$finder = AlarmaRecord::finder();
		$alarma = $finder->findByPk($id);
		$this->lblAlarma->Text = $alarma->Nombre;
		$idRol = $this->Session["SPIdRol"];

		$where = (!is_null($idOrganismo) ? "where v.IdOrganismo=".$idOrganismo : "");
		$join = (!is_null($idOrganismo) ? "": " inner join organismo og on v.IdOrganismo=og.IdOrganismo");
		$field = (!is_null($idOrganismo) ? "": ", og.Nombre as Organismo");

		$sql =  "select v.* $field from (" . $alarma->Query . ") v $join $where order by 2";
		$data = $this->ExecuteQuery($sql);

		for($i=0;$i<count($data);$i++){
			$data[$i]["Icono"] = '<img width="24px" src="themes/serviciospublicos/images/' . $data[$i]["Icono"] . '.png" />';
			
			switch ($idRol) {
				case 2:
				case 3:
					$data[$i]["Cód."] = sprintf("<a href='javascript:OpenParent(\"?page=Obra.Home&id=%s\");'>%s</a>", $data[$i]["IdObra"], $data[$i]["Cód."]);
					break;
				default:
					break;
			}

			$idObra = $data[$i]["IdObra"];
			$idCertificacion = $data[$i]["IdCertificacion"];

			$comentarios = $this->CreateDataSource("AlarmaUsuarioPeer","ComentarioByAlarma", $idOrganismo, $id, $idObra, $idCertificacion);
			$dataComentarios = "";
			/*
			foreach($comentarios as $c){
				$dataComentarios .= $c["FechaHora"] . " " . $c["ApellidoNombre"] . " - " . $c["Comentario"] . "<br>";
			}*/
			$btnRefresh = $this->btnRefresh->ClientID;

			if(count($comentarios)){
				$dataComentarios = "<a href=\"javascript:OpenWindow('?page=ComentariosAlarma&idA=$id&idO=$idObra&idC=$idCertificacion&btn=$btnRefresh',600,400, 'ComentarioAlarma');\" title='La alarma tiene comentarios'><img width='16px' src='themes/serviciospublicos/images/comments.png' /></a>";
			}
			else{
				$dataComentarios = "<a href=\"javascript:OpenWindow('?page=ComentariosAlarma&idA=$id&idO=$idObra&idC=$idCertificacion&btn=$btnRefresh',600,400, 'ComentarioAlarma');\" title='Agregar comentario'><img width='12px' src='themes/serviciospublicos/images/btnAgregar2.png' /></a>";
			}

			$data[$i]["Comentarios"] = $dataComentarios;
			unset($data[$i]["IdOrganismo"]);
			unset($data[$i]["IdObra"]);
			unset($data[$i]["IdCertificacion"]);
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

	public function btnRefresh_OnClick($sender, $param){
		$id = $this->Request["id"];
		$idOrganismo = $this->Session["SPOrganismo"];
		$this->Refresh($id, $idOrganismo);
	}

}

?>