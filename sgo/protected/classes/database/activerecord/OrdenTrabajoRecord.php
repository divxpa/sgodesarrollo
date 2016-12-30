<?php
class OrdenTrabajoRecord extends TActiveRecord
{
	const TABLE='ordentrabajo';

	public $IdOrdenTrabajo;
	public $IdContrato;
	public $Numero;
	public $NormaLegalAutorizacion;
	public $Monto;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>