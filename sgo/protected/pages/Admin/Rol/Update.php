<?php
class Update extends PageBaseSP{

	public $botones;
	public $paginas;

	public function onLoad($param)
	{
		parent::onLoad($param);

		if(!$this->IsPostBack)
		{
			$id = $this->Request["id"];

			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Rol";
				$this->Refresh($id);
			}
			

			// BOTONES
			$rolesBoton =  $this->CreateDataSource('BotonPeer', 'Botones');
            $this->CheckBoxListBotones->DataSource = $rolesBoton;
            $this->CheckBoxListBotones->dataBind();

			$this->botones = array_map(function($elem){
				return $elem['IdBoton'];
			}, $this->CreateDataSource('BotonPeer','BotonesRol',$id));
			$this->hdnBotonesResult->setValue(implode(',', $this->botones));


			// PAGINAS
			$rolesPagina =  $this->CreateDataSource('PaginaPeer', 'Paginas');
            $this->CheckBoxListPaginas->DataSource = $rolesPagina;
            $this->CheckBoxListPaginas->dataBind();

			$this->paginas = array_map(function($elem){
				return $elem['IdPagina'];
			}, $this->CreateDataSource('PaginaPeer','PaginasRol',$id));
			$this->hdnPaginasResult->setValue(implode(',', $this->paginas));

		}

	}

	public function Refresh($idRol)
	{
		$rol = RolRecord::finder()->findByPk($idRol);
		$this->txtNombre->Text = $rol->Nombre;
		//$this->chkActivo->Checked = $rol->Activo;
	}

	public function cvRol_OnServerValidate($sender, $param)
	{
		$nombre = strtoupper($this->txtNombre->Text);
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'Nombre like :nombre ';
		$criteria->Parameters[':nombre'] = $nombre;

		$id = $this->Request["id"];

		if(!is_null($id)){
			$criteria->Condition .=  ' AND IdRol <> :idrol';
			$criteria->Parameters[':idrol'] = $id;
		}

		$finder = RolRecord::finder();
		$rol = $finder->find($criteria);

		if (is_object($rol)) {
			$param->IsValid = false;
		}
		else {
			$param->IsValid = true;
		}

	}

	public function btnCancelar_OnClick($sender, $param)
	{
		$this->Response->Redirect("?page=Admin.Rol.Home");
	}

	public function btnAceptar_OnClick($sender, $param)
	{

		if($this->IsValid){
			$id = $this->Request["id"];

			if(!is_null($id)){
				$finder = RolRecord::finder();
				$rol = $finder->findByPk($id);
			}
			else{
				$rol = new RolRecord();
			}

			$rol->Nombre = mb_strtoupper($this->txtNombre->Text, 'utf-8');
			//$rol->Activo = $this->chkActivo->Checked ? 1 : 0;
			
			try{
				$rol->save();

				if(is_null($id)) 
					$id = $this->CreateDataSource('RolPeer','LastId')[0]['IdRol'];

				$modificaPaginas = false;

				// Roles múltiples:
				$botones = explode(',', $this->hdnBotonesResult->getValue());
				// Borra los existentes y los crea nuevamente
				RolBotonRecord::finder()->deleteAll('IdRol = ?', $id);
				foreach ($botones as $boton) {
					if ($boton) {
						$rolboton = new RolBotonRecord(); // Lo vuelve a crear por cada ciclo para que no intente un Update
						$rolboton->IdRol = $id;
						$rolboton->IdBoton = $boton;
						$rolboton->save();
					}

					if (in_array($boton,[1, 3])) $modificaPaginas = true;
				}

				// Páginas:
				if ($modificaPaginas) 
				{
					$paginas = explode(',', $this->hdnPaginasResult->getValue());
					// Borra los existentes y los crea nuevamente
					RolPaginaRecord::finder()->deleteAll('IdRol = ?', $id);
					foreach ($paginas as $pagina) {
						if ($pagina) {
							$rolpagina = new RolPaginaRecord(); // Lo vuelve a crear por cada ciclo para que no intente un Update
							$rolpagina->IdRol = $id;
							$rolpagina->IdPagina = $pagina;
							$rolpagina->save();
						}
					}
				}

				$this->Response->Redirect("?page=Admin.Rol.Home");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}

		}

	}

}

?>