<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Obra";
				$this->Refresh($id);
			}
			else{
				$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
				$codigo = $this->CreateDataSource("ObraPeer", "SiguienteCodigoObra", $idOrganismo);
				$this->txtCodigo->Text = $codigo[0]["Codigo"];
			}

		}

	}

	public function LoadDataRelated(){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrganismo = :idorganismo OR Comitente=1 ';
		$criteria->Parameters[':idorganismo'] = $idOrganismo;
		$finder = OrganismoRecord::finder();
		$organismos = $finder->findAll($criteria);
		$this->ddlComitente->DataSource = $organismos;
		$this->ddlComitente->dataBind();
		$this->ddlComitente->SelectedValue = $idOrganismo;

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = LocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$this->ddlLocalidad1->DataSource = $localidades;
		$this->ddlLocalidad1->dataBind();
		$this->ddlLocalidad2->DataSource = $localidades;
		$this->ddlLocalidad2->dataBind();
		$this->ddlLocalidad3->DataSource = $localidades;
		$this->ddlLocalidad3->dataBind();
		$this->ddlLocalidad4->DataSource = $localidades;
		$this->ddlLocalidad4->dataBind();
		$this->ddlLocalidad5->DataSource = $localidades;
		$this->ddlLocalidad5->dataBind();
		$this->ddlLocalidad6->DataSource = $localidades;
		$this->ddlLocalidad6->dataBind();
		$this->ddlLocalidad7->DataSource = $localidades;
		$this->ddlLocalidad7->dataBind();
		$this->ddlLocalidad8->DataSource = $localidades;
		$this->ddlLocalidad8->dataBind();
		$this->ddlLocalidad9->DataSource = $localidades;
		$this->ddlLocalidad9->dataBind();
		$this->ddlLocalidad10->DataSource = $localidades;
		$this->ddlLocalidad10->dataBind();
		$this->ddlLocalidad11->DataSource = $localidades;
		$this->ddlLocalidad11->dataBind();
		$this->ddlLocalidad12->DataSource = $localidades;
		$this->ddlLocalidad12->dataBind();
		$this->ddlLocalidad13->DataSource = $localidades;
		$this->ddlLocalidad13->dataBind();
		$this->ddlLocalidad14->DataSource = $localidades;
		$this->ddlLocalidad14->dataBind();
		$this->ddlLocalidad15->DataSource = $localidades;
		$this->ddlLocalidad15->dataBind();
		$this->ddlLocalidad16->DataSource = $localidades;
		$this->ddlLocalidad16->dataBind();
		$this->ddlLocalidad17->DataSource = $localidades;
		$this->ddlLocalidad17->dataBind();
		$this->ddlLocalidad18->DataSource = $localidades;
		$this->ddlLocalidad18->dataBind();
		$this->ddlLocalidad19->DataSource = $localidades;
		$this->ddlLocalidad19->dataBind();
		$this->ddlLocalidad20->DataSource = $localidades;
		$this->ddlLocalidad20->dataBind();

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Descripcion'] = 'asc';
		$finder = EstadoObraRecord::finder();
		$estados = $finder->findAll($criteria);
		$this->ddlEstado->DataSource = $estados;
		$this->ddlEstado->dataBind();

		$fuentesfinanciamiento = $this->CreateDataSource("FuenteFinanciamientoPeer","FuentesFinanciamientoSelect");
		$this->ddlFufi1->DataSource = $fuentesfinanciamiento;
		$this->ddlFufi1->dataBind();
		$this->ddlFufi2->DataSource = $fuentesfinanciamiento;
		$this->ddlFufi2->dataBind();
		$this->ddlFufi3->DataSource = $fuentesfinanciamiento;
		$this->ddlFufi3->dataBind();

		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Descripcion'] = 'asc';
		$finder = TipoObraRecord::finder();
		$tipos = $finder->findAll($criteria);
		$this->ddlTipo->DataSource = $tipos;
		$this->ddlTipo->dataBind();
	}

	public function Refresh($idObra){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$this->txtCodigo->Text = $obra->Codigo;
		$this->txtDenominacion->Text = $obra->Denominacion;
		$this->txtExpediente->Text = $obra->Expediente;
		$this->ddlComitente->SelectedValue = $obra->IdComitente;
		$this->txtCreditoPresup->Text = $obra->CreditoPresupuestarioAprobado;

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdObra = :idobra ';
		$criteria->Parameters[':idobra'] = $idObra;
		$criteria->OrdersBy['IdFuenteFinanciamiento'] = 'asc';
		$finder = ObraFuenteFinanciamientoRecord::finder();
		$fufis = $finder->findAll($criteria);
		$i = 1;

		foreach($fufis as $f){
			$controlName = "ddlFufi$i";
			$this->$controlName->SelectedValue = $f->IdFuenteFinanciamiento;
			$i++;
		}

		$this->txtPresupuestoOficial->Text = $obra->PresupuestoOficial;

		if(!is_null($obra->FechaPresupuestoOficial)){
			$fecha = explode("-",$obra->FechaPresupuestoOficial);
			$this->dtpFechaPresupOficial->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdObra = :idobra ';
		$criteria->Parameters[':idobra'] = $idObra;
		$criteria->OrdersBy['IdLocalidad'] = 'asc';
		$finder = ObraLocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$i = 1;

		foreach($localidades as $l){
			$controlName = "ddlLocalidad$i";
			$this->$controlName->SelectedValue = $l->IdLocalidad;
			$i++;
		}

		if($i>4){
			$this->pnlMasLocalidades->Display = "Dynamic";
		}

		$this->txtLatitud->Text = $obra->Latitud;
		$this->txtLongitud->Text = $obra->Longitud;

		if(!is_null($obra->IdTipoObra)){
			$this->ddlTipo->SelectedValue = $obra->IdTipoObra;
		}

		$this->txtBeneficiarios->Text = $obra->CantidadBeneficiarios;

		if(!is_null($obra->FechaInauguracion)){
			$fecha = explode("-",$obra->FechaInauguracion);
			$this->dtpFechaInauguracion->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		}

		$this->ddlEstado->SelectedValue = $obra->IdEstadoObra;
		$this->txtDetalleEstado->Text = $obra->DetalleEstado;
		$this->txtMemoriaDescriptiva->Text = $obra->MemoriaDescriptiva;

		$this->pnlModificacionObra->Visible = true;

		$this->btnAgregarRefuerzo->NavigateUrl .= "&ido=".$idObra;
		$data = $this->CreateDataSource("ObraPeer","RefuerzosPartidaByObra", $idObra);
		$this->dgRefuerzosPartida->DataSource = $data;
		$this->dgRefuerzosPartida->dataBind();

		if(count($data)){
			$this->lblRefuerzosPartida->Visible = false;
		}

	}

	public function cvCodigo_OnServerValidate($sender, $param)
	{
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$codigo = $this->txtCodigo->Text;
		
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrganismo = :idorganismo AND Codigo like :codigo ';
		$criteria->Parameters[':idorganismo'] = $idOrganismo;
		$criteria->Parameters[':codigo'] = $codigo;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdObra <> :idobra';
			$criteria->Parameters[':idobra'] = $id;
		}

		$finder = ObraRecord::finder();
		$obra = $finder->find($criteria);

		if (is_object($obra)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Obra.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];
			$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");

			if(!is_null($id)){
				$finder = ObraRecord::finder();
				$obra = $finder->findByPk($id);

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdObra = :idobra ';
				$criteria->Parameters[':idobra'] = $id;
				$finder = ObraFuenteFinanciamientoRecord::finder();
				$fufis = $finder->findAll($criteria);

				foreach($fufis as $f){
					$f->delete();
				}

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdObra = :idobra ';
				$criteria->Parameters[':idobra'] = $id;
				$finder = ObraLocalidadRecord::finder();
				$localidades = $finder->findAll($criteria);

				foreach($localidades as $l){
					$l->delete();
				}

			}
			else{
				$obra = new ObraRecord();
				$obra->IdOrganismo = $idOrganismo;
			}

			$obra->Codigo = $this->txtCodigo->Text;
			$obra->Denominacion = mb_strtoupper($this->txtDenominacion->Text, 'utf-8');
			$obra->Expediente = $this->txtExpediente->Text;
			$obra->IdComitente = $this->ddlComitente->SelectedValue;

			if($this->txtCreditoPresup->Text!=""){
				$obra->CreditoPresupuestarioAprobado = $this->txtCreditoPresup->Text;
			}
			else{
				$obra->CreditoPresupuestarioAprobado = null;
			}

			if($this->txtPresupuestoOficial->Text!=""){
				$obra->PresupuestoOficial = $this->txtPresupuestoOficial->Text;
			}
			else{
				$obra->PresupuestoOficial = null;
			}

			if($this->dtpFechaPresupOficial->Text!=""){
				$fecha = explode("/", $this->dtpFechaPresupOficial->Text);
				$obra->FechaPresupuestoOficial = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$obra->FechaPresupuestoOficial = null;
			}

			$obra->Latitud = $this->txtLatitud->Text;
			$obra->Longitud = $this->txtLongitud->Text;

			if($this->ddlTipo->SelectedValue!="" and $this->ddlTipo->SelectedValue!="0"){
				$obra->IdTipoObra = $this->ddlTipo->SelectedValue;
			}
			else{
				$obra->IdTipoObra = null;
			}

			if($this->txtBeneficiarios->Text!=""){
				$obra->CantidadBeneficiarios = $this->txtBeneficiarios->Text;
			}
			else{
				$obra->CantidadBeneficiarios = null;
			}

			if($this->dtpFechaInauguracion->Text!=""){
				$fecha = explode("/", $this->dtpFechaInauguracion->Text);
				$obra->FechaInauguracion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$obra->FechaInauguracion = null;
			}

			$obra->IdEstadoObra = $this->ddlEstado->SelectedValue;
			$obra->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
			$obra->MemoriaDescriptiva = mb_strtoupper($this->txtMemoriaDescriptiva->Text, 'utf-8');

			try{
				$obra->save();

				$obraFufi = new ObraFuenteFinanciamientoRecord();
				$obraFufi->IdObra = $obra->IdObra;
				$obraFufi->IdFuenteFinanciamiento = $this->ddlFufi1->SelectedValue;
				$obraFufi->save();

				if($this->ddlFufi2->SelectedValue!="" and $this->ddlFufi2->SelectedValue!="0"){
					$obraFufi = new ObraFuenteFinanciamientoRecord();
					$obraFufi->IdObra = $obra->IdObra;
					$obraFufi->IdFuenteFinanciamiento = $this->ddlFufi2->SelectedValue;
					$obraFufi->save();
				}

				if($this->ddlFufi3->SelectedValue!="" and $this->ddlFufi3->SelectedValue!="0"){
					$obraFufi = new ObraFuenteFinanciamientoRecord();
					$obraFufi->IdObra = $obra->IdObra;
					$obraFufi->IdFuenteFinanciamiento = $this->ddlFufi3->SelectedValue;
					$obraFufi->save();
				}

				$obraLocalidad = new ObraLocalidadRecord();
				$obraLocalidad->IdObra = $obra->IdObra;
				$obraLocalidad->IdLocalidad = $this->ddlLocalidad1->SelectedValue;
				$obraLocalidad->save();

				if($this->ddlLocalidad2->SelectedValue!="" and $this->ddlLocalidad2->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad2->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad3->SelectedValue!="" and $this->ddlLocalidad3->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad3->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad4->SelectedValue!="" and $this->ddlLocalidad4->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad4->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad5->SelectedValue!="" and $this->ddlLocalidad5->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad5->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad6->SelectedValue!="" and $this->ddlLocalidad6->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad6->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad7->SelectedValue!="" and $this->ddlLocalidad7->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad7->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad8->SelectedValue!="" and $this->ddlLocalidad8->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad8->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad9->SelectedValue!="" and $this->ddlLocalidad9->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad9->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad10->SelectedValue!="" and $this->ddlLocalidad10->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad10->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad11->SelectedValue!="" and $this->ddlLocalidad11->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad11->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad12->SelectedValue!="" and $this->ddlLocalidad12->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad12->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad13->SelectedValue!="" and $this->ddlLocalidad13->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad13->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad14->SelectedValue!="" and $this->ddlLocalidad14->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad14->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad15->SelectedValue!="" and $this->ddlLocalidad15->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad15->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad16->SelectedValue!="" and $this->ddlLocalidad16->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad16->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad17->SelectedValue!="" and $this->ddlLocalidad17->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad17->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad18->SelectedValue!="" and $this->ddlLocalidad18->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad18->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad19->SelectedValue!="" and $this->ddlLocalidad19->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad19->SelectedValue;
					$obraLocalidad->save();
				}

				if($this->ddlLocalidad20->SelectedValue!="" and $this->ddlLocalidad20->SelectedValue!="0"){
					$obraLocalidad = new ObraLocalidadRecord();
					$obraLocalidad->IdObra = $obra->IdObra;
					$obraLocalidad->IdLocalidad = $this->ddlLocalidad20->SelectedValue;
					$obraLocalidad->save();
				}

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdObra = :idobra ';
				$criteria->Parameters[':idobra'] = $obra->IdObra;
				$criteria->OrdersBy['Fecha'] = 'desc';
				$finder = ObraEstadoRecord::finder();
				$estado = $finder->find($criteria);
				//si tiene algun cambio de estado registrado veo si hizo algun cambio
				if(is_object($estado)){
					//si el estado es distinto al estado actual, registro el nuevo estado
					if($estado->IdEstadoObra != $obra->IdEstadoObra){
						$nuevoEstado = new ObraEstadoRecord();
						$nuevoEstado->IdObra = $obra->IdObra;
						$nuevoEstado->IdEstadoObra = $obra->IdEstadoObra;
						$nuevoEstado->Fecha = date('Y-m-d');
						$nuevoEstado->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
						$nuevoEstado->save();
					}
					else{
						//si el estado actual es el mismo, pero cambio el detalle, le actualizo el detalle
						if($estado->DetalleEstado != mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8')){
							$estado->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
							$estado->save();
						}

					}

				}
				else{
					//registro el primer obraestado
					$nuevoEstado = new ObraEstadoRecord();
					$nuevoEstado->IdObra = $obra->IdObra;
					$nuevoEstado->IdEstadoObra = $obra->IdEstadoObra;
					$nuevoEstado->Fecha = date('Y-m-d');
					$nuevoEstado->DetalleEstado = mb_strtoupper($this->txtDetalleEstado->Text, 'utf-8');
					$nuevoEstado->save();
				}

				$this->Response->Redirect("?page=Obra.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

	public function btnMasLocalidades_OnClick($sender, $param)
	{
		
		if($this->pnlMasLocalidades->Display=="None"){
			$this->pnlMasLocalidades->Display = "Dynamic";
		}
		else{
			$this->pnlMasLocalidades->Display = "None";
		}

	}

	public function cvMemoriaDescriptiva_OnServerValidate($sender, $param)
	{
		$texto = $this->txtMemoriaDescriptiva->Text;

		if (strlen($texto)>516) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

}

?>