<?php
class CompromisoRevisionRecord extends TActiveRecord
{
	const TABLE='compromisorevision';

	public $IdCompromisoRevision;
	public $IdCompromiso;
	public $IdUsuario;
	public $Fecha;
	public $Revision;
	public $Activo;	

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}	
}
?>