<?php
class UsuarioPeer
{
	public static function Authorization($username, $page){
		$sql = "select
					*
				from
					usuario u inner join
					rolpagina rp on u.IdRol=rp.IdRol inner join
					pagina p on rp.IdPagina=p.IdPagina
				where
					u.Username='$username' and
					p.Pagina='$page' and
					u.Activo=1 and
					p.Activa=1";
		return $sql;
	}

	public static function UsuariosHome($idOrganismo, $idEstado, $filter){
		$where = "";

		if($idOrganismo!="" and $idOrganismo!="0"){
			$where = " where u.IdOrganismo=$idOrganismo ";
		}

		if($idEstado!="-1" and $idEstado!=""){

			if($where!=""){
				$where .= " and u.Activo=$idEstado ";
			}
			else{
				$where = " where u.Activo=$idEstado ";
			}

		}

		if($filter!=""){

			if($where!=""){
				$where .= " and (u.Username like '%$filter%' or u.ApellidoNombre like '%$filter%') ";
			}
			else{
				$where = " where (u.Username like '%$filter%' or u.ApellidoNombre like '%$filter%') "; 
			}

		}

		$sql = "select
				  u.IdUsuario,
				  u.ApellidoNombre,
				  u.Username,
				  r.Nombre as Rol,
				  og.Nombre as Organismo,
				  (case
				  	when u.Activo=1 then 'Si'
				  	else 'No'
				  end) as ActivoDesc
				from
				  usuario u inner join
				  rol r on u.IdRol = r.IdRol left join
				  organismo og on u.IdOrganismo = og.IdOrganismo
				$where
				order by
				  u.ApellidoNombre";

		return $sql;
	}

	public static function IngresosHome($idOrganismo, $orden, $tipo){
		$where = "";

		if($idOrganismo!="" and $idOrganismo!="0"){
			$where = " and u.IdOrganismo=$idOrganismo ";
		}

		switch ($orden) {
			case "1":
				$order = "Usuario";
				break;
			case "2":
				$order = "Organismo";
				break;
			default:
				$order = "UltimoLogin";
				break;
		}

		if($tipo=="1"){
			$type = "DESC";
		}
		else{
			$type = "ASC";
		}

		$sql = "select
				  o.Nombre as Organismo,
				  u.ApellidoNombre as Usuario,
				  r.Nombre as Rol,
				  (select max(FechaHoraLogin) from ingreso where IdUsuario=u.IdUsuario) as UltimoLogin,
				  (select max(FechaHoraLogout) from ingreso where IdUsuario=u.IdUsuario) as UltimoLogout
				from
				  usuario u inner join 
				  organismo o on u.IdOrganismo = o.IdOrganismo inner join 
				  rol r on u.IdRol = r.IdRol
				where
				  u.Activo = 1 $where
				order by $order $type";

		return $sql;
	}

}
?>