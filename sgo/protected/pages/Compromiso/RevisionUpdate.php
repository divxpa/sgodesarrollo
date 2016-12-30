<?php
class RevisionUpdate extends PageBaseSP{

	public function onLoad($param){
		parent::onLoad($param);

		if(!$this->IsPostBack){
			$id = $this->Request["idRevision"];
			if (!is_null($id)) {
				$this->lblAccion->Text = "Modificar Revision Compromiso";
				$this->Refresh($id);
			}			
		}		
	}


	public function Refresh($IdCompromisoRevision){
		
		$finder = CompromisoRevisionRecord::finder();
		$compromisoRevision = $finder->findByPk($IdCompromisoRevision);
		
		$this->txtDenominacion->Text = $compromisoRevision->Revision;
	}
	
	public function btnCancelar_OnClick($sender, $param){
		$idCompromiso = $this->Request["idCompromiso"];
		$this->Response->Redirect("?page=Compromiso.Update&id=$idCompromiso");
	}

	public function btnAceptar_OnClick($sender, $param)	{ 
		if($this->IsValid){
			$idCompromiso = $this->Request["idCompromiso"];
			$idRevision = $this->Request["idRevision"];

			if(!is_null($idRevision)){
				$finder = CompromisoRevisionRecord::finder();
				$compromisoRevision = $finder->findByPk($idRevision);
			}
			else{
				$compromisoRevision = new CompromisoRevisionRecord();
			}
			
			$compromisoRevision->IdCompromiso = $idCompromiso;
			$compromisoRevision->Revision = $this->txtDenominacion->Text;
			$idUsuario = $this->Session->get("usr_id");
			$finder = UsuarioRecord::finder();
			$usuario = $finder->findByPk($idUsuario);
			$compromisoRevision->IdUsuario = $usuario->IdUsuario;						
			$compromisoRevision->Activo = True;
			
			try{
				$compromisoRevision->Fecha = date('Y-m-d h:i:s');
				$compromisoRevision->save();
				$this->Response->Redirect("?page=Compromiso.Update&id=$idCompromiso");
			}
			catch(exception $e){
				$this->Log($e->getMessage(),true);
			}
		}
	 }
}
?>
