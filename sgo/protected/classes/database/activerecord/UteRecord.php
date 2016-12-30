<?php
class UteRecord extends TActiveRecord
{
	const TABLE='ute';

	public $IdProveedor;
	public $IdProveedorSocio;
	public $PorcentajeSocio;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>