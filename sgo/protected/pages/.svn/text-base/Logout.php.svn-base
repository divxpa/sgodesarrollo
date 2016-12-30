<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */

class Logout extends TPage{

	public function onLoad($param){

		if(isset($_SESSION["SPIdUsuario"])){
			$this->SaveIngreso($_SESSION["SPIdUsuario"]);
			unset($_SESSION["SPIdUsuario"]);
			unset($_SESSION["SPNombreUsuario"]);
			unset($_SESSION["SPUsername"]);
			unset($_SESSION["SPOrganismo"]);
			unset($_SESSION["SPNombreOrganismo"]);
			unset($_SESSION["SPIdRol"]);
		}

		if(isset($_SESSION["ShowAlarmas"])){
			unset($_SESSION["ShowAlarmas"]);
		}

		@$this->Session->destroy();
		@session_destroy();
		$this->Response->redirect("?page=Login");
	}

	public function SaveIngreso($idUsuario){
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

	}

}

?>