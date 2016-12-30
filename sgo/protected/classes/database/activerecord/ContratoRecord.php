<?php
class ContratoRecord extends TActiveRecord
{
	const TABLE='contrato';

	public $IdContrato;
	public $IdObra;
	public $IdProveedor;
	public $Numero;
	public $Fecha;
	public $Monto;
	public $FechaBaseMonto;
	public $NormaLegalAutorizacion;
	public $NormaLegalAdjudicacion;
	public $PlazoEjecucion;
	public $FechaInicio;
	public $FechaFinalizacion;
	public $Decreto;
	public $MontoProvincia;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>