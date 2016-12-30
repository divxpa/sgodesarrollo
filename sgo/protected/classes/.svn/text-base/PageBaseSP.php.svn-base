<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2012
 */
class PageBaseSP extends TPage{

	public function onLoad($param){
		parent::onLoad($param);

		if (!$this->IsPostBack) {

			if($this->PagePath!="Login"){

				if(!isset($this->Session["SPIdUsuario"])) {
					$this->Response->Redirect("?page=Login");
				}

				$this->Authorization();
			}

		}

		$this->LogAuditoria();
	}

	public function CreateDataSource($className,$methodName){
		$args = func_get_args();
		$argsc = func_num_args();
		$argsToInvoke = array();

		if($argsc > 2){

			for($i=2;$i<$argsc;$i++){
				$argsToInvoke[] = $args[$i];
			}

		}

		$method = new ReflectionMethod($className."::".$methodName);
		$sql = $method->invokeArgs(null,$argsToInvoke);
		$connection = $this->Application->Modules['database']->DbConnection;
		$connection->Active=true;
		$command = $connection->createCommand($sql);
		$dataReader = $command->query();
		$connection->Active=false;

		return $dataReader->readAll();
	}

	public function ExecuteSql($sql){
		$connection = $this->Application->Modules['database']->DbConnection;
		$connection->Active=true;
		$command = $connection->createCommand($sql);
		$command->execute();
	}

	public function ExecuteQuery($sql){
		$connection = $this->Application->Modules['database']->DbConnection;
		$connection->Active=true;
		$command = $connection->createCommand($sql);
		$dataReader = $command->query();

		return $dataReader->readAll();
	}

	public function LoadData(){
		$this->LoadMenu();
		$this->LoadUser();
	}

	public function LoadMenu(){

		if($this->PagePath!="Login"){
			$this->Master->menu->Visible = true;
			$data = $this->CreateDataSource("MenuPeer","MenuByUsuario",$this->Session["SPUsername"]);
			$this->Master->menu->DataSource = $data;
			$this->Master->menu->dataBind();
		}
		else{
			$this->Master->menu->Visible = false;
		}

	}


	/**
	 * PageBaseSgc::LoadUser()
	 *
	 * @return
	 */
	public function LoadUser()
	{

		if($this->PagePath!="Login"){
			$this->Master->ploginin->Visible = true;
			$this->Master->lblUser->Text = "<strong>" . $this->Session["SPNombreUsuario"] . "</strong>";
			$this->Master->lblOrganismo->Text = "<strong>" . $this->Session["SPNombreOrganismo"] . "</strong>";
		}
		else{
			$this->Master->ploginin->Visible = false;
		}

	}

	public function Log($message,$redirect=false){
		$logRoute = "log/error/";
		$fileName = $logRoute."error.log";
		$usuario = $this->Session['SPUsername'];

		if(file_exists($fileName)){

			if (filesize($fileName)>1024*1024) {
				rename($fileName,$fileName."_".date('YmdHis'));
			}

		}

		$errorData = date('YmdHis ')."\t".$usuario."\t".serialize($message)."\n";
		file_put_contents($fileName, $errorData, FILE_APPEND | LOCK_EX);

		if($redirect){
			$this->Response->Redirect("?page=Error");
		}

	}

	/**
	 * PageBaseSgc::Authorization()
	 *
	 * @return
	 */
	public function Authorization()
	{
		$publicPages = array("Home","Login","Error");

		if(!in_array($this->PagePath, $publicPages)){
			$data = $this->CreateDataSource("UsuarioPeer","Authorization",$this->Session["SPUsername"],$this->PagePath);

			if(count($data)==0){
				$this->Response->Redirect("?page=Home");
			}

		}

	}

	public function ArrayToHtmlEntities($array){
		$arrayAux = $array;

		foreach($arrayAux as $key => $value){

			if(is_array($value)){

				foreach($value as $key2=>$value2){

					if(!is_array($value2)){
						$arrayAux[$key][$key2] = $this->ToHtmlEntities($value2);
					}
					else{
						$arrayAux[$key][$key2] = $this->ArrayToHtmlEntities($value2);
					}

				}

			}
			else{
				$arrayAux[$key] = $this->ToHtmlEntities($value);
			}

		}

		return $arrayAux;
	}

	public function ToHtmlEntities($strToReplace)
	{
		$search = array("á","é","í","ó","ú","ü","Á","É","Í","Ó","Ú","Ü","ñ","Ñ","°", "º");
		$replace = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&uuml;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&Uuml;', '&ntilde;', '&Ntilde;', '&deg;', '&ordm;' );
		return str_replace($search, $replace , $strToReplace);
	}

	public function SaveSearchMemory($page, $control, $value){
		$page = str_replace(".", "_", $page);
		$name = $page."_".$control;
		$memory = $this->Session["SearchMemory"];
		$memory[$name] = $value;
		$this->Session["SearchMemory"] = $memory;
	}

	public function GetSearchMemory($page, $control){
		$page = str_replace(".", "_", $page);
		$name = $page."_".$control;
		$memory = $this->Session["SearchMemory"];

		if(isset($memory[$name])){
			$value = $memory[$name];
		}
		else{
			$value = "";
		}

		return $value;
	}

	public function GetProveedorWS($cuit){
		$data = array();
		/*$data[] = array(
					"IdProveedor" => -1,
					"Descripcion" => "Proveedor de prueba"
				);*/
		
		/*try{
			$pecasUsername = "usuario-test";
			$pecasPassword = "asml8372";
			$WsAutenticacion =	"http://autentica.neuquen.gov.ar:8080/scripts/autenticacion.exe/wsdl/IAutenticacion";
			$SoapClientAutenticacion = new SoapClient($WsAutenticacion);
			$idSesionPecas = $SoapClientAutenticacion->LoginPecas($pecasUsername, $pecasPassword);
			$WsAutorizacion = "http://autoriza.neuquen.gov.ar:8080/scripts/autorizacion.exe/wsdl/IAutorizacion";
			$codigoCliente = "usuario-test";
			$datoAuditado = "cuit=30710554397";
			$operador = "nombre-operador";
			$proveedor = "GP-RENTAS";
			$servicio = "ws_LibreDeudaGeneral";
			$cuerpo = base64_encode("");
			$cuerpoFirmado =
			$cuerpoEncriptado = false;
			$firma = base64_encode("");
			$SoapClientAutorizacion = new SoapClient($WsAutorizacion);
			$wsResult = $SoapClientAutorizacion->Solicitar_Servicio3($idSesionPecas, $codigoCliente, $proveedor, $servicio, $datoAuditado, $operador, $cuerpo, $cuerpoFirmado, $cuerpoEncriptado, $firma);
			echo "<pre>";print_r($wsResult);echo "</pre>";
			var_dump($wsResult->Resultado1);
			var_dump($wsResult->Resultado2);
			var_dump($wsResult->NumPedido);
			exit;
		}
		catch(Exception $e){
			echo "No se pudo realizar la verificación automática";
		}*/



		return $data;
	}

	public function SaveProveedorWS($data){
		return "";
	}

	protected function LogAuditoria($aditionalData = array(), $fileName="ssp"){
		$hoy 		= 	date("Ymd");
		$ahora		=	date("YmdHis");
		$usuario 	= 	$this->Session["SPUsername"];
		$filename 	= 	sprintf("log/auditoria/%s_%s.log", $fileName, $hoy);

		if(file_exists($filename)){

			if (filesize($filename)>1024*1024*10) { //Tamaño máximo de archivo 10MB
				$i = 1;
				$flag = false;

				while(!$flag){
					$filename = sprintf("log/auditoria/%s_%s_%s.log", $fileName, $hoy, $i);

					if(!file_exists($filename)){
						$flag = true;
					}
					else{

						if (filesize($filename)<1024*1024*10){
							$flag = true;
						}

					}

					$i++;
				}

			}

		}

		$log = fopen($filename, "a+");
		$toLog = array_merge(array("FechaHora"=>$ahora), array("Usuario"=>$usuario), $_REQUEST, $aditionalData);
		$linea = sprintf("%s\r\n", serialize($toLog));

		fwrite($log, $linea);
		fclose($log);
	}

	public function SaveIngreso($idUsuario, $login){
		//busco los ingresos no cerrados y los cierro
		$criteria = new TActiveRecordCriteria;
		$criteria->Condition = 'IdUsuario = :idusuario ';
		$criteria->Parameters[':idusuario'] = $idUsuario;
		$criteria->Condition .= ' and FechaHoraLogout is null';
		$finder = IngresoRecord::finder();
		$ingresos = $finder->findAll($criteria);

		foreach ($ingresos as $ingreso) {
			$ingreso->FechaHoraLogout = date('Y-m-d H:i:s');
			$ingreso->save();
		}

		if($login){
			//guardo el login
			$ingreso = new IngresoRecord();
			$ingreso->IdUsuario = $idUsuario;
			$ingreso->Ip = $this->Request->UserHostAddress;
			$ingreso->FechaHoraLogin = date('Y-m-d H:i:s');
			$ingreso->save();
		}

	}

	public function ValidarObraOrganismo($idOrganismo, $idObra, $comitente=false){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if($obra->IdOrganismo!=$idOrganismo){

			if($comitente){
				return $obra->IdComitente==$idOrganismo;
			}
			else{
				return false;
			}

		}
		else{
			return true;
		}

	}

	public function ValidarComitente($idOrganismo, $idObra){
		$finder = ObraRecord::finder();
		$obra = $finder->findByPk($idObra);

		if($obra->IdOrganismo!=$idOrganismo){
			return $obra->IdComitente!=$idOrganismo;
		}
		else{
			return true;
		}

	}

	public function GenerarReporte($proforma, $page, $filters, $data){
		error_reporting(E_ALL & ~E_NOTICE);
		$filters = $this->ArrayToHtmlEntities($filters);
		$data = $this->ArrayToHtmlEntities($data);
		$username = $this->Session["SPNombreUsuario"];
		$fechahora = date('d/m/Y H:i:s');
		$headerFormat = file_get_contents('protected/proforma/PdfReportHeader.php');
		ob_start();
		eval("?>{$headerFormat}");
		$header = ob_get_clean();
		$footer = file_get_contents('protected/proforma/PdfReportFooter.php');
		$htmlFooter = file_get_contents('protected/proforma/HtmlFooter.php');
		$bodyFormat = file_get_contents("protected/proforma/$proforma.php");
		ob_start();
		eval("?>{$bodyFormat}");
		$body = ob_get_clean();
		$html = $header.$body.$footer;
		$html = utf8_decode($html);
		$mpdf=new mPDF('utf-8', $page, '', '', 10, 10, 10, 10, 9, 9);
		$mpdf->ignore_invalid_utf8= true;
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->WriteHTML($html,0);
		$fileName = "output/recycled/".$proforma."_".date('YmdHis').".pdf";
		$mpdf->Output($fileName, "F");
		error_reporting(E_ALL);
		return $fileName;
	}

	public function VerificarAlarmas(){

		if(isset($this->Application->Parameters["alarmaEnabled"])){
			$enabled = $this->Application->Parameters["alarmaEnabled"];

			if($enabled){
				$show = 
				$save = true;

				if(isset($this->Session['ShowAlarmas'])){
					$show = $this->Session['ShowAlarmas'];
					$save = false;
				}
				else{
					$this->Session['ShowAlarmas'] = true;
				}

				$idRol = $this->Session["SPIdRol"];

				if($idRol==1){
					$save = false;
				}

				$idOrganismo = $this->Session["SPOrganismo"];
				$idUsuario = $this->Session["SPIdUsuario"];
				$data = array();
				$alarmas = $this->CreateDataSource("RolPeer","Alarmas", $idRol);
				$where = (!is_null($idOrganismo) ? "where v.IdOrganismo=".$idOrganismo : "");
				$count = 0;

				foreach ($alarmas as $a) {
					$sql =  "select v.* from (" . $a['Query'] . ") v $where";
					$result = $this->ExecuteQuery($sql);
					$count+=count($result);

					if($save){
						$alarmaUsuario = new AlarmaUsuarioRecord();
						$alarmaUsuario->IdAlarma = $a['IdAlarma'];
						$alarmaUsuario->IdUsuario = $idUsuario;
						$alarmaUsuario->FechaHora = date('Y-m-d H:i:s');
						$alarmaUsuario->Cantidad = count($result);
						$alarmaUsuario->save();

						foreach ($result as $r) {
							$alarmaUsuarioDetalle = new AlarmaUsuarioDetalleRecord();
							$alarmaUsuarioDetalle->IdAlarmaUsuario = $alarmaUsuario->IdAlarmaUsuario;
							$alarmaUsuarioDetalle->IdObra = $r['IdObra'];

							if(!is_null($r['IdCertificacion'])){
								$alarmaUsuarioDetalle->IdCertificacion = $r['IdCertificacion'];
							}
							
							$alarmaUsuarioDetalle->save();
						}

					}
	
					$data[] = array("IdAlarma" => $a['IdAlarma'], "Alarma" => $a['Nombre'], "Cantidad" => count($result));
				}

				if(count($data)){
					$this->Master->dgAlarmas->DataSource = $data;
					$this->Master->dgAlarmas->dataBind();
					$this->Master->pnlAlarmas->Display = "Dynamic";

					if($count){
						$this->Master->lblTituloAlarma->Text="Existen alarmas pendientes";
						$this->Master->lblTituloAlarma->Style="color: #c00;";
					}
					else{
						$this->Master->lblTituloAlarma->Text="NO existen alarmas pendientes";
						$this->Master->lblTituloAlarma->Style="color: green;";
					}

					if($show){
						$this->Master->pnlAlarmasDetalle->Display = "Dynamic";
					}
					else{
						$this->Master->pnlAlarmasDetalle->Display = "None";
					}

				}
				else{
					$this->Master->pnlAlarmas->Display = "None";
				}

			}

		}

	}

	public function ValidarProyectoOrganismo($idOrganismo, $idProyecto){
		$finder = SolicitudProyectoRecord::finder();
		$proyecto = $finder->findByPk($idProyecto);

		if($proyecto->IdOrganismo!=$idOrganismo){
			return false;
		}
		else{
			return true;
		}

	}
	
}

?>