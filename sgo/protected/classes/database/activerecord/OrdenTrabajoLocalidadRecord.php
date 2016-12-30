<?php
class OrdenTrabajoLocalidadRecord extends TActiveRecord
{
	const TABLE='ordentrabajolocalidad';

	public $IdOrdenTrabajo;
	public $IdLocalidad;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>