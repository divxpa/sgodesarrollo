<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];
			$this->LoadDataRelated($idObra, $idContrato);
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Orden de Trabajo";
				$this->Refresh($id, $idObra, $idContrato);
			}
			else{
				$numero = $this->CreateDataSource("OrdenTrabajoPeer", "SiguienteNumero", $idContrato);
				$this->txtNumero->Text = $numero[0]["Numero"];
			}

		}

	}

	public function LoadDataRelated($idObra, $idContrato){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);
		$localidades = $this->CreateDataSource("ObraPeer","LocalidadesPorObra", $idObra);
		$this->lblObra->Text = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion . " - " .$localidades[0]["Localidades"];
		$finder = ContratoRecord::finder();
		$contrato = $finder->findByPk($idContrato);
		$finder = ProveedorRecord::finder();
		$proveedor = $finder->findByPk($contrato->IdProveedor);
		$this->lblContrato->Text = $contrato->Numero . " - " . $proveedor->Cuit . " " . $proveedor->RazonSocial;
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
	}

	public function Refresh($idOrdenTrabajo, $idObra, $idContrato){
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if(!$this->ValidarObraOrganismo($idOrganismo, $idObra)){
			$this->Response->Redirect("?page=Obra.Home");
		}

		$finder = OrdenTrabajoRecord::finder();
		$ordenTrabajo = $finder->findByPk($idOrdenTrabajo);

		$this->txtNumero->Text = $ordenTrabajo->Numero;
		$this->txtNLAutorizacion->Text = $ordenTrabajo->NormaLegalAutorizacion;
		$this->txtImporte->Text = $ordenTrabajo->Monto;

		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdOrdenTrabajo = :idordentrabajo ';
		$criteria->Parameters[':idordentrabajo'] = $idOrdenTrabajo;
		$criteria->OrdersBy['IdLocalidad'] = 'asc';
		$finder = OrdenTrabajoLocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$i = 1;

		foreach($localidades as $l){
			$controlName = "ddlLocalidad$i";
			$this->$controlName->SelectedValue = $l->IdLocalidad;
			$i++;
		}

		$this->pnlModificacionOrdenTrabajo->Visible = true;

		$this->btnAgregarDeductivo->NavigateUrl .= "&idot=".$idOrdenTrabajo."&idc=".$idContrato."&ido=".$idObra;
		$data = $this->CreateDataSource("OrdenTrabajoPeer","DeductivosByOrdenTrabajo", $idOrdenTrabajo);
		$this->dgDeductivos->DataSource = $data;
		$this->dgDeductivos->dataBind();

		if(count($data)){
			$this->lblDeductivos->Visible = false;
		}
	}

	public function cvNumero_OnServerValidate($sender, $param)
	{
		$idContrato = $this->Request["idc"];
		$numero = $this->txtNumero->Text;
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Numero = :numero ';
		$criteria->Parameters[':numero'] = $numero;
		$criteria->Condition .= ' AND IdContrato = :idcontrato ';
		$criteria->Parameters[':idcontrato'] = $idContrato;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdOrdenTrabajo <> :idordentrabajo';
			$criteria->Parameters[':idordentrabajo'] = $id;
		}

		$finder = OrdenTrabajoRecord::finder();
		$ordenTrabajo = $finder->find($criteria);

		if (is_object($ordenTrabajo)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$ido = $this->Request["ido"];
		$idc = $this->Request["idc"];
		$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idc&ido=$ido");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$recalcula = false;
			$id = $this->Request["id"];
			$idObra = $this->Request["ido"];
			$idContrato = $this->Request["idc"];

			if(!is_null($id)){
				$finder = OrdenTrabajoRecord::finder();
				$ordenTrabajo = $finder->findByPk($id);

				$criteria = new TActiveRecordCriteria;
				$criteria->Condition = 'IdOrdenTrabajo = :idordentrabajo ';
				$criteria->Parameters[':idordentrabajo'] = $id;
				$finder = OrdenTrabajoLocalidadRecord::finder();
				$localidades = $finder->findAll($criteria);

				foreach($localidades as $l){
					$l->delete();
				}

				if($ordenTrabajo->Monto!=$this->txtImporte->Text){
					$recalcula = true;
				}

			}
			else{
				$ordenTrabajo = new OrdenTrabajoRecord();
				$ordenTrabajo->IdContrato = $idContrato;
			}

			$ordenTrabajo->Numero = $this->txtNumero->Text;
			$ordenTrabajo->NormaLegalAutorizacion = $this->txtNLAutorizacion->Text;
			$ordenTrabajo->Monto = $this->txtImporte->Text;

			try{
				$ordenTrabajo->save();

				$ordenTrabajoLocalidad = new OrdenTrabajoLocalidadRecord();
				$ordenTrabajoLocalidad->IdOrdenTrabajo = $ordenTrabajo->IdOrdenTrabajo;
				$ordenTrabajoLocalidad->IdLocalidad = $this->ddlLocalidad1->SelectedValue;
				$ordenTrabajoLocalidad->save();

				if($this->ddlLocalidad2->SelectedValue!="" and $this->ddlLocalidad2->SelectedValue!="0"){
					$ordenTrabajoLocalidad = new OrdenTrabajoLocalidadRecord();
					$ordenTrabajoLocalidad->IdOrdenTrabajo = $ordenTrabajo->IdOrdenTrabajo;
					$ordenTrabajoLocalidad->IdLocalidad = $this->ddlLocalidad2->SelectedValue;
					$ordenTrabajoLocalidad->save();
				}

				if($this->ddlLocalidad3->SelectedValue!="" and $this->ddlLocalidad3->SelectedValue!="0"){
					$ordenTrabajoLocalidad = new OrdenTrabajoLocalidadRecord();
					$ordenTrabajoLocalidad->IdOrdenTrabajo = $ordenTrabajo->IdOrdenTrabajo;
					$ordenTrabajoLocalidad->IdLocalidad = $this->ddlLocalidad3->SelectedValue;
					$ordenTrabajoLocalidad->save();
				}

				if($this->ddlLocalidad4->SelectedValue!="" and $this->ddlLocalidad4->SelectedValue!="0"){
					$ordenTrabajoLocalidad = new OrdenTrabajoLocalidadRecord();
					$ordenTrabajoLocalidad->IdOrdenTrabajo = $ordenTrabajo->IdOrdenTrabajo;
					$ordenTrabajoLocalidad->IdLocalidad = $this->ddlLocalidad4->SelectedValue;
					$ordenTrabajoLocalidad->save();
				}

				if($recalcula){
					$this->ExecuteSql(OrdenTrabajoPeer::RecalcularCertificacionesByOrdenTrabajo($ordenTrabajo->IdOrdenTrabajo));
				}

				$this->Response->Redirect("?page=Obra.Contrato.Update&id=$idContrato&ido=$idObra");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>