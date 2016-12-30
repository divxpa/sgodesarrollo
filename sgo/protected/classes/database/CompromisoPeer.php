<?php
class CompromisoPeer
{

	public static function CompromisoSelect(){
		$sql = "
				Select compromiso.IdCompromiso as IdCompromiso, compromiso.fecha as fecha,
				compromiso.compromiso as compromiso,
				 
				 usuario.ApellidoNombre as responsable, compromisoorganismo.tag as organismo
				From compromiso 
				join compromisoresponsable on compromiso.IdResponsable = compromisoresponsable.IdCompromisoResponsable
				join usuario on compromisoresponsable.IdUsuario = usuario.IdUsuario
				join compromisoorganismo on compromisoorganismo.IdCompromisoOrganismo = compromisoresponsable.IdOrganismo
				Where compromiso.activo = 1
				Order by compromiso.Fecha DESC";
		return $sql;	
	}

	public static function CompromisoHome($idLocalidad, $idOrganismo, $IdResponsable, $busqueda){
		//$where = "";
		$where = " Where compromiso.activo = 1 ";

		if($idLocalidad!="" and $idLocalidad!="0"){
			$where .= " and (compromiso.IdLocalidad = $idLocalidad) ";
		}

		if($idOrganismo!="" and $idOrganismo!="0"){
			$where .= " and (compromisoorganismo.IdCompromisoOrganismo = $idOrganismo) ";
		}

		if($IdResponsable!="" and $IdResponsable!="0"){
			$where .= " and (compromisoresponsable.IdCompromisoResponsable = $IdResponsable) ";
		}		

		if($busqueda!=""){
			$where .= " and (compromiso.compromiso like '%$busqueda%') ";
		}

		$sql = 
				"Select compromiso.IdCompromiso as IdCompromiso, compromiso.fecha as fecha, 
				localidad.Nombre, localidad.Nombre,
				CONCAT (SUBSTRING(compromiso.compromiso,1, 100),'...') as compromiso,				
				usuario.ApellidoNombre as responsable , compromisoorganismo.tag as organismo
				From compromiso 
				join compromisoresponsable on compromiso.IdResponsable = compromisoresponsable.IdCompromisoResponsable
				join usuario on compromisoresponsable.IdUsuario = usuario.IdUsuario
				join compromisoorganismo on compromisoorganismo.IdCompromisoOrganismo = compromisoresponsable.IdOrganismo
				join localidad on localidad.IdLocalidad = compromiso.IdLocalidad
				$where
				Order by compromiso.Fecha DESC";

		return $sql;
	}

	public static function UltimaCreacion(){
		// Falta implementar la ultima fecha de registro para un organismo determinado
		$sql = "Select DATE_FORMAT(MAX(FechaRegistro),'%d-%m-%Y') as ultimo From compromiso";
		return $sql;
	}

	public static function UltimaRevision(){
		// Falta implementar la ultima fecha de registro para un organismo determinado
		$sql = 
			"Select DATE_FORMAT(MAX(Fecha),'%d-%m-%Y') as ultimo From compromisorevision";
		return $sql;
	}

	public static function LocalidadesConCompromisoSelect(){
		$sql = 
				"Select l.idLocalidad, l.Nombre 
				From compromiso c
					inner join localidad l  on l.IdLocalidad = c.IdLocalidad  
				Group by l.IdLocalidad
				Order by c.idLocalidad";
		return $sql;
	}

	public static function OrganismosConCompromisoSelect(){
		$sql = "Select IdCompromisoOrganismo, Tag From compromisoorganismo Order by Tag;";
		return $sql;
	}



	public static function RevisionesDelCompromiso($IdCompromiso){

		$sql = "Select cr.IdCompromisoRevision, cr.IdCompromiso, u.ApellidoNombre as Usuario, DATE_FORMAT(cr.Fecha,'%d-%m-%Y') as Fecha, cr.Revision
			from compromisorevision cr inner join usuario u on cr.IdUsuario = u.IdUsuario
			where cr.IdCompromiso = $IdCompromiso;";
		return $sql;
	}


}
?>