<?php
class TipoObraRecord extends TActiveRecord
{
	const TABLE='tipoobra';

	public $IdTipoObra;
	public $Descripcion;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>