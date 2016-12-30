<?php
class CertificacionItemRecord extends TActiveRecord
{
	const TABLE='certificacionitem';

        public $IdCertificacionItem;
	public $IdCertificacion;
	public $IdContratoItem;
	public $PorcentajeActual;
	public $ImporteActual;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>