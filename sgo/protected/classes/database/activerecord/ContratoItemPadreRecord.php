<?php
class ContratoItemPadreRecord extends TActiveRecord
{
	const TABLE='contratoitempadre';

	public $idContratoItemPadre;
	public $IdContrato;
	public $Orden;
	public $Item;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>
