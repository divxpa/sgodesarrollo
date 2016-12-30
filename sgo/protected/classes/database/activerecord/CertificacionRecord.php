<?php
class CertificacionRecord extends TActiveRecord
{
	const TABLE='certificacion';

	public $IdCertificacion;
	public $IdContrato;
	public $Periodo;
	public $NroCertificado;
	public $PorcentajeAvance;
	public $MontoAvance;
	public $AnticipoFinanciero;
	public $DescuentoAnticipo;
	public $ImporteNeto;
	public $FechaVtoPago;
	public $RetencionMulta;
	public $OtrosConceptos;
	public $ManoObraOcupada;
	public $Observaciones;
	public $RedeterminacionPrecios;
	public $RetencionFondoReparo;
	public $TipoCertificado;
	public $IdOrdenTrabajo;
	public $Alcance;
	public $FechaMedicion;
        public $PorcentajeAvanceReal;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>