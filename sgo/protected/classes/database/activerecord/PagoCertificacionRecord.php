<?php
class PagoCertificacionRecord extends TActiveRecord
{
	const TABLE='pagocertificacion';

	public $IdPagoCertificacion;
	public $IdPago;
	public $IdCertificacion;
	public $Importe;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>