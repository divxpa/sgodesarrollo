<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */

class Logout extends SessionPage {

	public function onLoad($param){

		parent::onLoad($param);

		if($this->Session->exists("usr_id")){
			$this->SaveIngreso($this->Session->get("usr_id"));
			$this->Session->delete("usr_id");
			$this->Session->delete("usr_apellidoNombre");
			$this->Session->delete("usr_username");
			$this->Session->delete("usr_sgo_idOrganismo");
			$this->Session->delete("usr_sgo_nombreOrganismo");
			$this->Session->delete("usr_sgo_idRol");
		}

		$this->Session->delete("ShowAlarmas");

		$this->Session->end();
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