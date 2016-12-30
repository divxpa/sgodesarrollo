<?php
class RolRecord extends TActiveRecord
{
	const TABLE='rol';

	public $IdRol;
	public $Nombre;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>