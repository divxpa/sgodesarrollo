<?php
class ProveedorRecord extends TActiveRecord
{
	const TABLE='proveedor';

	public $IdProveedor;
	public $Cuit;
	public $RazonSocial;
	public $Domicilio;
	public $IdLocalidad;
	public $RepresentanteTecnico;
	public $Telefono;
	public $Email;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>