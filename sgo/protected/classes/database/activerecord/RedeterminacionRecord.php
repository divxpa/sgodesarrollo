<?php
class RedeterminacionRecord extends TActiveRecord
{
	const TABLE='redeterminacion';

	public $IdRedeterminacion;
	public $IdContrato;
	public $Fecha;
	public $NormaLegalAprobacion;
	public $Importe;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>