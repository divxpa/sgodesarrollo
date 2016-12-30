<?php
class RecepcionContratoRecord extends TActiveRecord
{
	const TABLE='recepcioncontrato';

	public $IdRecepcionContrato;
	public $IdContrato;
	public $ProvisoriaDefinitiva;
	public $ParcialTotal;
	public $Fecha;
	public $NormaLegalAprobacion;
	public $PorcentajeRecepcion;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>