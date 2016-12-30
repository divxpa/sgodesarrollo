<?php
class ObraRecord extends TActiveRecord
{
	const TABLE='obra';

	public $IdObra;
	public $Codigo;
	public $Denominacion;
	public $IdOrganismo;
	public $CreditoPresupuestarioAprobado;
	public $Expediente;
	public $MemoriaDescriptiva;
	public $CantidadBeneficiarios;
	public $PresupuestoOficial;
	public $FechaPresupuestoOficial;
	public $IdTipoObra;
	public $Latitud;
	public $Longitud;
	public $IdEstadoObra;
	public $DetalleEstado;
	public $IdComitente;
	public $Agil;
	public $FechaInauguracion;
	public $PorAdministracion;
	public $Activo;
	public $ultimaActualizacion;
	public $detalleLocalidad;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>