<?php
class EstadoObraPeer
{
	public static function EstadosObraHome($filter){
		$where = "";

		if($filter!=""){
			$where = " where e.Descripcion like '%$filter%' ";
		}


		$sql = "select
				  *
				from
				  estadoobra e
				$where
				order by
				  e.Descripcion";

		return $sql;
	}
}
?>