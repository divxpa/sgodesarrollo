<?php
class ContratoItemRecord extends TActiveRecord
{
	const TABLE='contratoitem';

	public $IdContratoItem;
	public $IdContrato;
	public $Item;
	public $Cantidad;
	public $UnidadMedida;
	public $PrecioUnitario;
	public $PrecioTotal;
	public $Orden;
	public $IdContratoItemPadre;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>

