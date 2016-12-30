<?php
class FuenteFinanciamientoPeer
{
	public static function FuentesFinanciamientoSelect(){
		$sql = "select
					IdFuenteFinanciamiento,
					CONCAT(CodigoFufi,' ',Descripcion) as Descripcion
				from
					fuentefinanciamiento
				order by
					CodigoFufi";
		return $sql;
	}

	public static function FuentesFinanciamientoHome($filter){
		$where = "";

		if($filter!=""){
			$where = " where f.Descripcion like '%$filter%' or f.CodigoFufi like '%$filter%' ";
		}


		$sql = "select
				  *
				from
				  fuentefinanciamiento f
				$where
				order by
				  f.CodigoFufi";

		return $sql;
	}
}
?>