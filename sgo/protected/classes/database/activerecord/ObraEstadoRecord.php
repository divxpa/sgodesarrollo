<?php
class ObraEstadoRecord extends TActiveRecord
{
	const TABLE='obraestado';

	public $IdObraEstado;
	public $IdObra;
	public $IdEstadoObra;
	public $Fecha;
	public $DetalleEstado;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>