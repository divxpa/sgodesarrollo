<?php
class MenuPeer
{
	
	public static function MenuByUsuario($username){
		$where = "WHERE X.USERNAME='$username' OR X.USERNAME IS NULL AND ((X.IDPAGINA IS NOT NULL AND X.CONTENIDOS>0) OR (X.CONTENIDOS>0))";

		$sql = "select
  					*
				from
			  		(select
			            IdMenu as IDMENU,
			      		null as IDPAGINA,
			            Nombre as NOMBRE,
			            Orden is null as PIVOT,
			            Orden as ORDEN,
			            null as PAGINA,
			         	null as CONTENEDOR,
			         	null as IDCONTENEDOR,
			         	null as USERNAME,
			            (select count(IdMenu) from menu inner join rolpagina on menu.IdPagina = rolpagina.IdPagina inner join usuario on
			            rolpagina.IdRol = usuario.IdRol inner join pagina on rolpagina.IdPagina=pagina.IdPagina where
			            menu.IdMenuContenedor = m.IdMenu and usuario.Username='$username' and menu.Activo=1 and pagina.Activa=1) +
			            (select count(mh.IdMenu) from menu mp inner join menu mh on mp.IdMenu = mh.IdMenuContenedor inner join rolpagina
			            on mh.IdPagina = rolpagina.IdPagina inner join usuario on rolpagina.IdRol = usuario.IdRol inner join
			            pagina on rolpagina.IdPagina=pagina.IdPagina where mp.IdMenuContenedor = m.IdMenu and usuario.Username='$username' and
			            pagina.Activa=1 and mp.Activo=1 and mh.Activo=1) AS CONTENIDOS,
			         	Target as TARGET
			        from
			        	menu m
			       	where
			       		Activo = 1 and
			       		IdMenuContenedor is null and
			       		IdPagina is null
					union
					SELECT
						m.IdMenu as IDMENU,
						p.IdPagina as IDPAGINA,
						m.Nombre as NOMBRE,
						m.Orden is null as PIVOT,
						m.Orden as ORDEN,
						p.Pagina as PAGINA,
						mc.Nombre AS CONTENEDOR,
						mc.IdMenu AS IDCONTENEDOR,
						usuario.Username AS USERNAME,
						(select count(IdMenu) from menu inner join rolpagina on menu.IdPagina = rolpagina.IdPagina inner join usuario
						on rolpagina.IdRol = usuario.IdRol inner join pagina on rolpagina.IdPagina=pagina.IdPagina where menu.IdMenuContenedor = m.IdMenu
						and usuario.Username='$username' and pagina.Activa=1 and menu.Activo=1)+
						(select count(mh.IdMenu) from menu mp inner join menu mh on mp.IdMenu = mh.IdMenuContenedor inner join rolpagina
						on mh.IdPagina = rolpagina.IdPagina inner join pagina on rolpagina.IdPagina=pagina.IdPagina where
						mp.IdMenuContenedor = m.IdMenu and usuario.Username='$username' and pagina.Activa=1 and mp.Activo=1 and mh.Activo=1) as CONTENIDOS,
						m.Target as TARGET
					from
						menu m left join
						pagina p on m.IdPagina = p.IdPagina left join
						menu mc on m.IdMenuContenedor = mc.IdMenu left join
						rolpagina r on p.IdPagina = r.IdPagina left join
						usuario on r.IdRol = usuario.IdRol
					where
						usuario.Username='$username' and
						m.Activo=1
			) X
			$where
			order by
				PIVOT ASC,
				ORDEN ASC,
				X.NOMBRE ASC";

		return $sql;
	}

}
?>