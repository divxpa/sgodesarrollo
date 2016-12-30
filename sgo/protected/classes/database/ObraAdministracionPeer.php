.<?php
class ObraAdministracionPeer
{
	public static function ObrasHome($idOrganismo, $idLocalidad, $idFufi, $idEstado, $busqueda, $idObra='',$codigoOrganismo, $codigoObra){
		$where = "";

		if($idOrganismo!=""){
			$where = " where (o.IdOrganismo=$idOrganismo or o.IdComitente=$idOrganismo) ";
		}

		if($idLocalidad!="" and $idLocalidad!="0"){

			if($where!=""){
				$where .= " and exists(select * from obralocalidad where IdObra=o.IdObra and IdLocalidad=$idLocalidad) ";
			}
			else{
				$where = " where exists(select * from obralocalidad where IdObra=o.IdObra and IdLocalidad=$idLocalidad) ";
			}

		}

		if($idFufi!="" and $idFufi!="0"){

			if($where!=""){
				$where .= " and exists(select * from obrafuentefinanciamiento where IdObra=o.IdObra and IdFuenteFinanciamiento=$idFufi) ";
			}
			else{
				$where = " where exists(select * from obrafuentefinanciamiento where IdObra=o.IdObra and IdFuenteFinanciamiento=$idFufi) ";
			}

		}

		if($idEstado!="" and $idEstado!="0"){

			if($where!=""){
				$where .= " and o.IdEstadoObra=$idEstado ";
			}
			else{
				$where = " where o.IdEstadoObra=$idEstado ";
			}

		}

		if($codigoOrganismo!=""){

					if($where!=""){
						$where .= " and (og.PrefijoCodigo = $codigoOrganismo) ";
					}
					else{
						$where = " where (og.PrefijoCodigo = $codigoOrganismo) ";
					}

				}

				if($codigoObra!=""){

					if($where!=""){
						$where .= " and (o.Codigo = $codigoObra) ";
					}
					else{
						$where = " where (o.Codigo = $codigoObra) ";
					}

		}
		
		if($busqueda!=""){

			if($where!=""){
				$where .= " and (o.Denominacion like '%$busqueda%' or o.Expediente like '%$busqueda%') ";
			}
			else{
				$where = " where (o.Denominacion like '%$busqueda%' or o.Expediente like '%$busqueda%') ";
			}

		}

		if($idObra!=''){
			$where .= ($where!="" ? " and " : " where ") . " o.IdObra = $idObra ";
		}

		$sql = "select
				  o.IdObra,
				  contrato.IdContrato,
				  concat(og.PrefijoCodigo,'-',o.Codigo) as Codigo,
				  og.Nombre as Organismo,
				  og2.Nombre as Comitente,
				  o.Denominacion,
				  tio.Descripcion as TipoObra,
				  fnLocalidadesxObra(o.IdObra, '<br />') as Localidad,
				  fnFufisxObra(o.IdObra) as FuenteFinanciamiento,
				  o.Expediente,
				  o.CreditoPresupuestarioAprobado,
				  ifnull((select sum(Importe) from refuerzopartida where IdObra=o.IdObra) ,0) as RefuerzoPartida,
				  o.CantidadBeneficiarios,
				  o.PresupuestoOficial,
				  date_format(o.FechaPresupuestoOficial,'%d/%m/%Y') as FechaPresupuestoOficial,
				  eo.Descripcion as Estado,
				  ifnull((select sum(montoavance) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),0)/ifnull((select sum(Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=contrato.IdContrato),0)) from contrato where IdObra=o.IdObra), 0)*100 as PorcentajeAvance,
				  ifnull((select sum(montoavance) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),0) as MontoAvance,
				  ifnull(o.CreditoPresupuestarioAprobado, 0) + ifnull((select sum(Importe) from refuerzopartida where IdObra=o.IdObra) ,0) - ifnull((select sum(montoavance) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),0) - ifnull((select sum(redeterminacionprecios) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),0) as SaldoCreditoPresup,
				  ifnull((select concat(substring(max(Periodo),5,2), '/', substring(max(Periodo),1,4)) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),'-') as UltimoCertificado,
				  (case
				  	when o.IdOrganismo=o.IdComitente then 'true'
				  	when o.IdComitente=$idOrganismo then 'false'
				  	else 'true'
				  end) as EditarVisible,
				  (case
				  	when o.IdObra in (13,16,25,26,27,130,131,147,177,266,267,272,274,275,294,298,301,303,304,330,333,336,346,436) then 1
				  	else 2
				  end) as Orden
				from
				  obra o inner join
				  organismo og on o.IdOrganismo = og.IdOrganismo left join
				  tipoobra tio on o.IdTipoObra = tio.IdTipoObra inner join
				  estadoobra eo on o.IdEstadoObra=eo.IdEstadoObra inner join
				  organismo og2 on o.IdComitente=og2.IdOrganismo
				  left join contrato on o.IdObra = contrato.IdObra
				$where
					and o.PorAdministracion = 1 and o.activo = 1
				order by
				  Orden, o.Codigo";
		return $sql;
	}

	public static function SiguienteCodigoObra($idOrganismo){
		$sql = "select
				  lpad(ifnull(max(cast(Codigo as unsigned))+1,1),4,'0') as Codigo
				from
				  obra
				where
				  IdOrganismo=$idOrganismo";
		return $sql;
	}

	public static function LocalidadesConObraSelect($idOrganismo){
		$sql = "select
				  l.IdLocalidad,
				  l.Nombre
				from
				  localidad l
				where
				  exists(select * from obra o inner join obralocalidad ol on o.IdObra = ol.IdObra where ol.IdLocalidad=l.IdLocalidad and o.IdOrganismo=$idOrganismo and o.poradministracion = 1)
				order by
				  l.Nombre";
		return $sql;
	}

	public static function FufisConObraSelect($idOrganismo){
		$sql = "select
				  f.IdFuenteFinanciamiento,
				  concat(f.CodigoFufi,' ', f.Descripcion) as Descripcion
				from
				  fuentefinanciamiento f
				where
				  exists(select * from obra o inner join obrafuentefinanciamiento of on o.IdObra = of.IdObra where of.IdFuenteFinanciamiento=f.IdFuenteFinanciamiento and o.IdOrganismo=$idOrganismo)
				order by
				  f.CodigoFufi";
		return $sql;
	}

	public static function LocalidadesPorObra($idObra){
		$sql = "select fnLocalidadesxObra($idObra,' - ') as Localidades from dual";
		return $sql;
	}

	public static function ObrasReport($idOrganismo, $codigo, $denominacion, $expediente, $idLocalidad, $idEstados){
		$where = "";

		if($idOrganismo!=""){
			$where = " where (o.IdOrganismo=$idOrganismo or o.IdComitente=$idOrganismo) ";
		}

		if($idLocalidad!="" and $idLocalidad!="0"){

			if($where!=""){
				$where .= " and exists(select * from obralocalidad where IdObra=o.IdObra and IdLocalidad=$idLocalidad) ";
			}
			else{
				$where = " where exists(select * from obralocalidad where IdObra=o.IdObra and IdLocalidad=$idLocalidad) ";
			}

		}

		if(count($idEstados)){
			$estados = implode(",", $idEstados);

			if($where!=""){
				$where .= " and o.IdEstadoObra in ($estados) ";
			}
			else{
				$where = " where o.IdEstadoObra in ($estados) ";
			}

		}

		if($codigo!=""){

			if($where!=""){
				$where .= " and concat(og.PrefijoCodigo,'-',o.Codigo) like '%$codigo%' ";
			}
			else{
				$where = " where concat(og.PrefijoCodigo,'-',o.Codigo) like '%$codigo%' ";
			}

		}

		if($denominacion!=""){

			if($where!=""){
				$where .= " and o.Denominacion like '%$denominacion%' ";
			}
			else{
				$where = " where o.Denominacion like '%$denominacion%' ";
			}

		}

		if($expediente!=""){

			if($where!=""){
				$where .= " and o.Expediente like '%$expediente%' ";
			}
			else{
				$where = " where o.Expediente like '%$expediente%' ";
			}

		}

		$sql = "select
				  o.IdObra,
				  c.IdContrato,
				  concat(og.PrefijoCodigo,'-',o.Codigo) as Codigo,
				  o.Denominacion,
				  fnLocalidadesxObra(o.IdObra, '<br />') as Localidad,
				  o.Expediente,
				  concat(p.Cuit,' ',p.RazonSocial) as Proveedor,
				  c.Numero as NroContrato,
				  c.Monto,
				  ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=c.IdContrato),0) as Alteracion,
				  ifnull((select sum(Importe) from redeterminacion where IdContrato=c.IdContrato),0) as RedeterminacionPrecios,
				  ifnull((select sum(montoavance) from certificacion where IdContrato=c.IdContrato),0)/(c.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=c.IdContrato),0))*100 as PorcentajeAvance,
				  ifnull((select sum(montoavance) from certificacion where IdContrato=c.IdContrato),0) as MontoAvance,
				  eo.Descripcion as Estado,
				  o.DetalleEstado
				from
				  obra o left join 
				  contrato c on o.IdObra = c.IdObra inner join 
				  organismo og on o.IdOrganismo = og.IdOrganismo left join 
				  proveedor p on c.IdProveedor = p.IdProveedor inner join
				  estadoobra eo on o.IdEstadoObra=eo.IdEstadoObra
				$where
				order by
				  o.Codigo";
		return $sql;
	}

	public static function ObrasByLocalidad($idLocalidad){
		$sql = "select
				  o.Denominacion as Obra,
				  fnFufisxObra(o.IdObra) as FuenteFinanciamiento,
				  ifnull((select sum(Monto) from contrato where IdObra=o.IdObra), o.PresupuestoOficial) as Monto,
				  ifnull((select sum(montoavance) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra),0)/ifnull((select sum(Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=contrato.IdContrato),0)) from contrato where IdObra=o.IdObra), 0)*100 as PorcentajeAvance,
				  ifnull(o.CantidadBeneficiarios, 0) as CantidadBeneficiarios,
				  ifnull((select sum(ManoObraOcupada) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra), 0) as CantidadManoObra,
				  eo.Descripcion as Estado,
				  o.DetalleEstado,
				  og.Nombre as Organismo,
				  og2.Nombre as Comitente,
				  o.MemoriaDescriptiva,
				  date_format(c.FechaInicio, '%d/%m/%Y') as FechaInicio,
				  date_format(ifnull((select max(NuevaFechaFinalizacion) from contratoplazo where IdContrato=c.IdContrato),c.FechaFinalizacion), '%d/%m/%Y') as FechaFinalizacion
				from
				  obra o inner join
				  organismo og on o.IdOrganismo = og.IdOrganismo inner join
				  estadoobra eo on o.IdEstadoObra=eo.IdEstadoObra inner join
				  organismo og2 on o.IdComitente=og2.IdOrganismo left join
				  contrato c on o.IdObra=c.IdObra
				where 
					exists(select * from obralocalidad where IdObra=o.IdObra and IdLocalidad=$idLocalidad)
				order by
				  og.Nombre,
				  eo.Descripcion,
				  o.Denominacion";
		return $sql;
	}

	public static function PorcentajeAvance($idObra, $idCertificacion){
		$where = "";

		if(!is_null($idCertificacion)){
			$where = "and IdCertificacion<>$idCertificacion";
		}

		$sql = "select
					ifnull((select sum(montoavance) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra $where),0)/ifnull((select sum(Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=contrato.IdContrato),0)) from contrato where IdObra=o.IdObra), 0)*100 as PorcentajeAvance
				from
					obra o
				where
					o.IdObra=$idObra";

		return $sql;
	}

	public static function RefuerzosPartidaByObra($idObra){
		$sql = "select
				  r.IdRefuerzoPartida,
				  r.IdObra,
				  date_format(r.Fecha, '%d/%m/%Y') as Fecha,
				  r.NormaLegal,
				  r.Importe
				from
				  refuerzopartida r inner join
				  obra o on r.IdObra=o.IdObra
				where
					o.IdObra=$idObra
				order by
				  r.Fecha";
		return $sql;
	}

	public static function ProyectosInversionHome($idOrganismo, $busqueda){
		$where = "";

		if($idOrganismo!=""){
			$where = " where s.IdOrganismo=$idOrganismo ";
		}

		if($busqueda!=""){

			if($where!=""){
				$where .= " and (sd.Proyecto like '%$busqueda%') ";
			}
			else{
				$where = " where (sd.Proyecto like '%$busqueda%') ";
			}

		}


		$sql = "select
					s.*,
					sd.*,
					date_format(s.FechaSolicitud, '%d-%m-%Y') as FechaSolicitudDesc,
					(case
						when sd.Moneda=1 then 'Peso'
						when sd.Moneda=2 then 'Dólar'
						else ''
					end) as MonedaDesc,
					(case
						when sd.Estado=1 then 'Perfil'
						when sd.Estado=2 then 'Anteproyecto'
						when sd.Estado=3 then 'Proyecto'
						when sd.Estado=4 then 'Licitación'
						when sd.Estado=5 then 'Ejecución'
						else ''
					end) as EstadoDesc,
					(case
						when sd.Prioridad=1 then 'Urgente'
						when sd.Prioridad=2 then 'Estructural'
						when sd.Prioridad=3 then 'Estratégica'
						else ''
					end) as PrioridadDesc
				from
				  solicitudproyecto s inner join
				  solicitudproyectodetalle sd on s.IdSolicitudProyecto=sd.IdSolicitudProyecto
				$where
				order by
				  s.FechaSolicitud desc";

		return $sql;
	}

	public static function DetalleSolicitud($id){
		$sql = "select 
					spd.*,
					date_format(spd.FechaEstimacionCosto, '%d-%m-%Y') as FechaEstimacionCostoDesc,
					(case
						when spd.Moneda=1 then 'Peso'
						when spd.Moneda=2 then 'Dólar'
						else ''
					end) as MonedaDesc,
					(case
						when spd.Estado=1 then 'Perfil'
						when spd.Estado=2 then 'Anteproyecto'
						when spd.Estado=3 then 'Proyecto'
						when spd.Estado=4 then 'Licitación'
						when spd.Estado=5 then 'Ejecución'
						else ''
					end) as EstadoDesc,
					(case
						when spd.Prioridad=1 then 'Urgente'
						when spd.Prioridad=2 then 'Estructural'
						when spd.Prioridad=3 then 'Estratégica'
						else ''
					end) as PrioridadDesc
				from solicitudproyectodetalle spd where IdSolicitudProyecto=$id";
		return $sql;
	}

}
?>