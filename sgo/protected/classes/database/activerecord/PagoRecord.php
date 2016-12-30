<?php
class PagoRecord extends TActiveRecord
{
	const TABLE='pago';

	public $IdPago;
	public $IdProveedor;
	public $Fecha;
	public $OrdenPago;
	public $ImporteBruto;
	public $Retenciones;
	public $ImporteNeto;
	public $IdOrganismo;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>