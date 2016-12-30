<?php
class CompromisoResponsablePeer
{
	public static function CompromisoResponsableSelect(){
		$sql = "Select 
					 compromisoresponsable.IdCompromisoResponsable, 
					 CONCAT (usuario.ApellidoNombre , ' - ' , compromisoorganismo.tag) as ApellidoNombre
				From compromisoresponsable
					Inner Join usuario on compromisoresponsable.IdUsuario = usuario.IdUsuario
          			Inner join compromisoorganismo on compromisoorganismo.IdCompromisoOrganismo = compromisoresponsable.IdOrganismo
				Order by
					usuario.ApellidoNombre";
		return $sql;
	}
}
?>