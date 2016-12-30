<?php
class AlteracionRecord extends TActiveRecord
{
	const TABLE='alteracion';

	public $IdAlteracion;
	public $IdContrato;
	public $Fecha;
	public $NormaLegalAprobacion;
	public $AdicionalDeductivo;
	public $Importe;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>