<?php
class PagosByCertificado extends PageBaseSP{

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
		$idOrganismo = $this->Session->get("usr_sgo_idOrganismo");
		$finder = CertificacionRecord::finder();
		$certificacion = $finder->findByPk($id);
		$finder = ContratoRecord::finder();
		$contrato = $finder->findByPk($certificacion->IdContrato);
		$finder = ProveedorRecord::finder();
		$proveedor = $finder->findByPk($contrato->IdProveedor);
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($contrato->IdObra);
		$finder = OrganismoRecord::finder();
		$organismo = $finder->findByPk($obra->IdOrganismo);

		if($obra->IdOrganismo!=$idOrganismo){
			$this->Response->Redirect("?page=Home");
		}

		$periodo = substr($certificacion->Periodo, 4, 2)."/".substr($certificacion->Periodo, 0, 4);
		$this->lblObra->Text = $organismo->PrefijoCodigo."-".$obra->Codigo." ".$obra->Denominacion;
		$this->lblContrato->Text = $contrato->Numero." - ".$proveedor->Cuit." ".$proveedor->RazonSocial;
		$this->lblCertificacion->Text = $certificacion->NroCertificado." - ".$periodo;

		$data = $this->CreateDataSource("PagoPeer","PagosByCertificado", $id);
		$this->dgDatos->DataSource = $data;
		$this->dgDatos->dataBind();
		$this->setViewState("Data",$data);
	}

}

?>