<?php
class ObraFuenteFinanciamientoRecord extends TActiveRecord
{
	const TABLE='obrafuentefinanciamiento';

	public $IdObra;
	public $IdFuenteFinanciamiento;

	public static function finder($className=__CLASS__)
	{
		return parent::finder($className);
	}
}
?>