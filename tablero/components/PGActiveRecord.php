<?php
namespace app\components;

use Yii;
use app\models\Auditoria;
use app\classes\SessionSGO;

class PGActiveRecord extends \yii\db\ActiveRecord
{

	/**
	*Hace el guardado propio del ActiveRecord y realiza el guardado de auditoria
	*/
	public function save($runValidation = true, $attributeNames = NULL)
    {
    	$auditoria = NULL;
    	$isNew = $this->isNewRecord;

		if(!$isNew){
			$pk = $this->primaryKey;
			$old = $this->findOne($pk);
		}

		$datoNuevo = 
		$datoAnterior = "";

		foreach ($this->attributes as $att => $val) {
			$datoNuevo .= $att."==".$val."||";

			if(!$isNew){
				$datoAnterior .= $att."==".$old->$att."||";
			}

		}

		if($datoAnterior!=$datoNuevo){
			$auditoria=new Auditoria;
			$auditoria->IdUsuario = Yii::$app->user->getId();
			$auditoria->FechaHora = date('Y-m-d H:i:s');
			$auditoria->Ip = Yii::$app->request->getUserIp();
			$auditoria->SessionId = SessionSGO::getId();
			$auditoria->Tabla = $this->tableName();

			if($isNew){
				$auditoria->TipoMovimiento = "I";
				$auditoria->DatoAnterior = null;
			}
			else{
				$auditoria->TipoMovimiento = "U";
				$datoAnterior = substr($datoAnterior, 0, strlen($datoAnterior)-2);
				$auditoria->DatoAnterior = $datoAnterior;
			}

			$datoNuevo = substr($datoNuevo, 0, strlen($datoNuevo)-2);
			$auditoria->DatoNuevo = $datoNuevo;
		}

		if(parent::save($runValidation, $attributeNames)){
			
			if(is_object($auditoria)){
				$pk = $this->primaryKey;

				if(is_array($pk)){
					$pkAux = "";

					foreach ($pk as $p) {
						$pkAux .= $p . "||";
					}

					$pk = substr($pkAux, 0, strlen($pkAux)-2);
				}
				
				$auditoria->PrimaryKey = $pk;
				$auditoria->save();
			}

			return true;
		}
		else{
			return false;
		}

    }

    /**
	*Hace el borrado propio del ActiveRecord y realiza el guardado de auditoria
	*/
    public function delete(){
    	$auditoria=new Auditoria;
		$auditoria->IdUsuario = Yii::$app->user->getId();
		$auditoria->FechaHora = date('Y-m-d H:i:s');
		$auditoria->Ip = Yii::$app->request->getUserIp();
		$auditoria->SessionId = Yii::$app->session->getId();
		$auditoria->Tabla = $this->tableName();
		$pk = $this->primaryKey;

		if(is_array($pk)){
			$pkAux = "";

			foreach ($pk as $p) {
				$pkAux .= $p . "||";
			}

			$pk = substr($pkAux, 0, strlen($pkAux)-2);
		}

		$auditoria->PrimaryKey = $pk;
		$auditoria->TipoMovimiento = "D";

		$datoAnterior = "";

		foreach ($this->attributes as $att => $val) {
			$datoAnterior .= $att."==".$val."||";
		}
		
		$datoAnterior = substr($datoAnterior, 0, strlen($datoAnterior)-1);
		$auditoria->DatoAnterior = $datoAnterior;
		$auditoria->DatoNuevo = null;

		if(parent::delete()){
			$auditoria->save();
			return true;
		}
		else{
			return false;
		}

    }

}

?>