<?php
class AlarmaUsuarioPeer
{
	public static function ComentarioByAlarma($idOrganismo, $idAlarma, $idObra, $idCertificacion){
		// TODO: falta si el organismo es nulo
		$where = "";
		$where .= ($idCertificacion!="" ? " and aud.IdCertificacion = $idCertificacion " : " and aud.IdCertificacion is null ");
		$where .= ($idOrganismo!="" ? " and u.IdOrganismo = $idOrganismo " : "");

		$sql = "select
				  date_format(au.FechaHora, '%d/%m/%Y %H:%i') as 'Fecha/Hora',
				  u.ApellidoNombre as Usuario,
				  aud.Comentario
				from
				  alarmausuariodetalle aud inner join
				  alarmausuario au on aud.IdAlarmaUsuario = au.IdAlarmaUsuario inner join
				  usuario u on au.IdUsuario = u.IdUsuario
				where
				  au.IdAlarma = $idAlarma and
				  aud.Comentario is not null and
				  aud.IdObra = $idObra
				  $where
				order by
				  au.FechaHora asc";
		return $sql;
	}

}
?>