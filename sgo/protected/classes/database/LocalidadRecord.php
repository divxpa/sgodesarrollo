<?php
class LocalidadRecord extends TActiveRecord
{
	const TABLE='localidad';

	public $IdLocalidad;
	public $Nombre;
	public $CodigoPostal;
	public $Aniversario;
	public $Categoria;
	public $Autoridad;
	public $FotoAutoridad;
	public $FotoEscudo;
	public $FotoLocalidad;
	public $Zona;
	public $Habitantes;
        public $MarcaLocalidad;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>