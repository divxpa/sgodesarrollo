<?php
class TipoObraPeer
{
	public static function TiposObraHome($filter){
		$where = "";

		if($filter!=""){
			$where = " where t.Descripcion like '%$filter%' ";
		}


		$sql = "select
				  *
				from
				  tipoobra t
				$where
				order by
				  t.Descripcion";

		return $sql;
	}
}
?>