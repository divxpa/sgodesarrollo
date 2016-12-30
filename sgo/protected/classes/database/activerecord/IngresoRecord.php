<?php
class IngresoRecord extends TActiveRecord
{
	const TABLE='ingreso';

	public $IdIngreso;
	public $IdUsuario;
	public $Ip;
	public $SessionId;
	public $FechaHoraLogin;
	public $FechaHoraLogout;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>