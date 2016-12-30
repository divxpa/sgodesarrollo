<?php
class UsuarioRecord extends TActiveRecord
{
	const TABLE='usuario';

	public $IdUsuario;
	public $ApellidoNombre;
	public $Username;
	public $Password;
	public $IdOrganismo;
	public $Activo;
	public $IdRol;
	public $Email;
	public $IdLocalidad;	

	// Agregados recientemente para que no tire error
	public $IdPlanilla;
	public $Sgo;
	public $Tablero;
	public $Geo;
	public $GeoCompromisos;
	public $Compromisos;
	public $CertificacionMunicipio;
	public $Calendario;
	public $TableroUnificado;


	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>