<?php
class ComentariosAlarma extends PageBaseSP{

	public function onPreInit($param){
		parent::onPreInit($param);
		$this->MasterClass = "Application.layouts.DialogLayout";
	}

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idAlarma = $this->Request["idA"];
			$idObra = $this->Request["idO"];
			$idCertificacion = $this->Request["idC"];
			$idOrganismo = $this->Session["SPOrganismo"];
			$this->Refresh($idOrganismo, $idAlarma, $idObra, $idCertificacion);
		}

	}

	public function Refresh($idOrganismo, $idAlarma, $idObra, $idCertificacion)
	{
		$idRol = $this->Session["SPIdRol"];

		if($idRol==1){
			$this->pnlAgregar->Visible = false;
		}

		$data = $this->CreateDataSource("AlarmaUsuarioPeer","ComentarioByAlarma", $idOrganismo, $idAlarma, $idObra, $idCertificacion);
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();

		if(!count($data)){
			$this->lblResult->Text = "No existen comentarios para la alarma seleccionada";
		}

		$this->setViewState("Data",$data);
	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->CallbackClient->callClientFunction("CloseWindow",array());
	}

	public function btnAceptar_OnClick($sender, $param)
	{
		$idUsuario = $this->Session["SPIdUsuario"];
		$idAlarma = $this->Request["idA"];
		$idObra = $this->Request["idO"];
		$idCertificacion = $this->Request["idC"];

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdAlarma = :idalarma ';
		$criteria->Parameters[':idalarma'] = $idAlarma;
		$criteria->Condition .= ' AND IdUsuario = :idusuario ';
		$criteria->Parameters[':idusuario'] = $idUsuario;
		$criteria->OrdersBy['FechaHora'] = 'desc';

		$finder = AlarmaUsuarioRecord::finder();
		$alarmaUsuario = $finder->find($criteria);

		if(is_object($alarmaUsuario)){
			$criteria = new TActiveRecordCriteria;
			$criteria->Condition = 'IdAlarmaUsuario = :idalarmausuario ';
			$criteria->Parameters[':idalarmausuario'] = $alarmaUsuario->IdAlarmaUsuario;

			$criteria->Condition .= ' AND IdObra = :idobra ';
			$criteria->Parameters[':idobra'] = $idObra;

			if($idCertificacion!=""){
				$criteria->Condition .= ' AND IdCertificacion = :idcertificacion ';
				$criteria->Parameters[':idcertificacion'] = $idCertificacion;
			}
			else{
				$criteria->Condition .= ' AND IdCertificacion is not null ';
			}

			$finder = AlarmaUsuarioDetalleRecord::finder();
			$alarmaUsuarioDetalle = $finder->find($criteria);

			if(is_object($alarmaUsuarioDetalle)){

				if(!is_null($alarmaUsuarioDetalle->Comentario)){
					$alarmaUsuarioDetalle = new AlarmaUsuarioDetalleRecord();
					$alarmaUsuarioDetalle->IdAlarmaUsuario = $alarmaUsuario->IdAlarmaUsuario;
					$alarmaUsuarioDetalle->IdObra = $idObra;

					if($idCertificacion!=""){
						$alarmaUsuarioDetalle->IdCertificacion = $idCertificacion;
					}

				}

			}
			else{
				$alarmaUsuarioDetalle = new AlarmaUsuarioDetalleRecord();
				$alarmaUsuarioDetalle->IdAlarmaUsuario = $alarmaUsuario->IdAlarmaUsuario;
				$alarmaUsuarioDetalle->IdObra = $idObra;

				if($idCertificacion!=""){
					$alarmaUsuarioDetalle->IdCertificacion = $idCertificacion;
				}

			}

			$alarmaUsuarioDetalle->Comentario = $this->txtComentario->Text;
			$alarmaUsuarioDetalle->save();
		}

		$btn = $this->Request["btn"];
		$this->CallbackClient->callClientFunction("RefreshParent",array($btn));
	}

}

?>