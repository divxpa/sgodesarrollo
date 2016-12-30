<?php
class RolPeer
{
	public static function Alarmas($idRol){
		$sql = "select
					a.*
				from
					alarma a inner join
					alarmarol ar on a.IdAlarma=ar.IdAlarma
				where
					a.Activo=1 and
					a.Alcance in (1, 3) and
					ar.IdRol=$idRol
				order by
					a.Nombre";

		return $sql;
	}

}
?>