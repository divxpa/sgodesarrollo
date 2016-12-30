<?php
class ProveedorPeer
{
	public static function ProveedoresSelect(){
		$sql = "select
					IdProveedor,
					CONCAT(RazonSocial,' (',Cuit, ')') as Descripcion
				from
					proveedor
				order by
					RazonSocial";
		return $sql;
	}

	public static function ProveedoresConContratoSelect($idOrganismo){
		$sql = "select
					IdProveedor,
					CONCAT(RazonSocial,' (',Cuit, ')') as Descripcion
				from
					proveedor
				where
					exists(select * from obra o inner join contrato c on o.IdObra = c.IdObra where c.IdProveedor=proveedor.IdProveedor and o.IdComitente=$idOrganismo)
				order by
					RazonSocial";
		return $sql;
	}

	public static function ProveedoresConPagoSelect($idOrganismo){
		$sql = "select
					IdProveedor,
					CONCAT(RazonSocial,' (',Cuit, ')') as Descripcion
				from
					proveedor
				where
					exists(select * from pago p where p.IdProveedor=proveedor.IdProveedor and p.IdOrganismo=$idOrganismo)
				order by
					RazonSocial";
		return $sql;
	}

	public static function ProveedoresAutocomplete($filter){
		$sql = "select
					IdProveedor,
					CONCAT(RazonSocial,' (',Cuit, ')') as Descripcion
				from
					proveedor
				where
					RazonSocial like '%$filter%' or Cuit like '%$filter%'
				order by
					RazonSocial";
		return $sql;
	}

	public static function ProveedoresHome($filter){
		$where = "";

		if($filter!=""){
			$where = " where p.Cuit like '%$filter%' or p.RazonSocial like '%$filter%' ";
		}


		$sql = "select
				  p.*,
				  (select Nombre from localidad where IdLocalidad=p.IdLocalidad) as Localidad
				from
				  proveedor p
				$where
				order by
				  p.RazonSocial";

		return $sql;
	}

	public static function ProveedoresNoUteAutocomplete($filter){
		$sql = "select
					IdProveedor,
					CONCAT(RazonSocial,' (',Cuit, ')') as Descripcion
				from
					proveedor
				where
					(RazonSocial like '%$filter%' or Cuit like '%$filter%') and
					not exists(select * from ute where IdProveedor=proveedor.IdProveedor)
				order by
					RazonSocial";
		return $sql;
	}

}
?>