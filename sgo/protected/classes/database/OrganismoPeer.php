<?php
class OrganismoPeer
{

	public static function OrganismosHome($filter){
		$where = "";

		if($filter!=""){
			$where = " where o.Nombre like '%$filter%') ";
		}


		$sql = "select
				  o.*,
				  (case
				  	when Comitente=1 then 'Si'
				  	else 'No'
				  end) as ComitenteDesc
				from
				  organismo o
				$where
				order by
				  o.Nombre";

		return $sql;
	}

	public static function SiguienteCodigo(){
		$sql = "select
				  lpad(ifnull(max(cast(PrefijoCodigo as unsigned))+1,1),2,'0') as Codigo
				from
				  organismo";
		return $sql;
	}

}
?>