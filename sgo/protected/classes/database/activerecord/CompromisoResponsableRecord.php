<?php
class CompromisoResponsableRecord extends TActiveRecord
{
	const TABLE='compromisoresponsable';

	public $IdCompromisoResponsable;
	public $IdUsuario;
	public $IdOrganismo;
	public $Activo;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>