<?php
class OrdenTrabajoDeductivoRecord extends TActiveRecord
{
	const TABLE='ordentrabajodeductivo';

	public $IdOrdenTrabajoDeductivo;
	public $IdOrdenTrabajo;
	public $Importe;
	public $Fecha;
	public $NormaLegalAprobacion;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>