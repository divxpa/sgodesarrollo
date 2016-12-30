<?php
class Update extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$this->LoadDataRelated();
			$id = $this->Request["id"];
			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Compromiso";
				$this->Refresh($id);
			}
			
		}		
	}
	
	public function LoadDataRelated(){		
		
		$criteria = new TActiveRecordCriteria;
		$criteria->OrdersBy['Nombre'] = 'asc';
		$finder = LocalidadRecord::finder();
		$localidades = $finder->findAll($criteria);
		$this->ddlLocalidad->DataSource = $localidades;
		$this->ddlLocalidad->dataBind();
		
		$responsables = $this->CreateDataSource("CompromisoResponsablePeer","CompromisoResponsableSelect");		
		$this->ddlResponsable->DataSource = $responsables;
		$this->ddlResponsable->dataBind();

		
	}
		
	public function Refresh($idCompromiso){
		
		
		$finder = CompromisoRecord::finder();
		$compromiso = $finder->findByPk($idCompromiso);
		
		if(!is_null($compromiso->Fecha)){
			$fecha = explode("-",$compromiso->Fecha);
			$this->dtpFecha->Text = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		} 
		
		$this->txtDenominacion->Text = $compromiso->Compromiso;
		$this->ddlResponsable->Text = $compromiso->IdResponsable;
		$this->ddlLocalidad->Text = $compromiso->IdLocalidad;
		$this->txtPlazo->Text = $compromiso->Plazo;
		$this->txtLatitud->Text = $compromiso->Latitud;
		$this->txtLongitud->Text = $compromiso->Longitud;

	}

	/*
	public function cvEstadoObra_OnServerValidate($sender, $param){
		$compromiso = strtoupper($this->txtDenominacion->Text);
		$criteria = new TActiveRecordCriteria;
		//$criteria->Condition = 'Descripcion like :descripcion ';
		$criteria->Condition = ' AND 1 = 1';
		$criteria->Parameters[':descripcion'] = $descripcion;

		$id = $this->Request["id"];

		if(!is_null($id)){
			//$criteria->Condition .=  ' AND IdEstadoObra <> :idestadoobra';
			$criteria->Condition .=  ' AND 1 = 1';
			$criteria->Parameters[':idestadoobra'] = $id;
		}

		$finder = CompromisoRecord::finder();
		$estado = $finder->find($criteria);

		if (is_object($estado)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}*/
	
	public function btnCancelar_OnClick($sender, $param){
		$this->Response->Redirect("?page=Compromiso.Home");
	}

	public function btnAceptar_OnClick($sender, $param)	{ 
		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = CompromisoRecord::finder();
				$compromiso = $finder->findByPk($id);
			}
			else{
				$compromiso = new CompromisoRecord();
			}
			
			// Compromiso.Fecha
			if($this->dtpFecha->Text!=""){
				$fecha = explode("/", $this->dtpFecha->Text);
				$compromiso->Fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			else{
				$compromiso->Fecha = null;
			}

			// Compromiso.IdLocalidad
			if($this->ddlLocalidad->SelectedValue!="" and $this->ddlLocalidad->SelectedValue!="0"){
				$compromiso->IdLocalidad = $this->ddlLocalidad->SelectedValue;
			}
			else{
				$compromiso->IdLocalidad = null;
			}
			// Compromiso.Compromiso
			$compromiso->Compromiso = $this->txtDenominacion->Text;
			
			// Compromiso.IdResponsable			
			if($this->ddlResponsable->SelectedValue!="" and $this->ddlResponsable->SelectedValue!="0"){
				$compromiso->IdResponsable = $this->ddlResponsable->SelectedValue;
			}

			// Compromiso.Plazo
			$compromiso->Plazo = $this->txtPlazo->Text;
			// Compromiso.Latitud
			$compromiso->Latitud = $this->txtLatitud->Text;
			// Compromiso.Longitud
			$compromiso->Longitud = $this->txtLongitud->Text;
			// Compromiso.Fecha Registro
			//$compromiso->FechaRegistro = date('Y-m-d h:i:s a');
			//$compromiso->FechaRegistro = date('Y-m-d H:i:s');

			//Compromiso.IdUsuario
			$idUsuario = $this->Session["SPIdUsuario"];
			$finder = UsuarioRecord::finder();
			$usuario = $finder->findByPk($idUsuario);
			$compromiso->IdUsuario = $usuario->IdUsuario;						
			
			// Compromiso.IdObra
			//
					
			// Compromiso.Activo
			$compromiso->Activo = True;
			
			try{
				$compromiso->FechaRegistro = date('Y-m-d H:i:s');
				$compromiso->save();
				$this->Response->Redirect("?page=Compromiso.Home");
				//$this->Response->Redirect("?page=Home");
				//?page=Home
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}
		}
	 }
}
?>
