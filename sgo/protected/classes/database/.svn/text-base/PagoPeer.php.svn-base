<?php
class PagoPeer
{
	public static function PagosHome($idOrganismo, $idProveedor, $fechaDesde, $fechaHasta, $busqueda){
		$where = "where p.IdOrganismo=$idOrganismo ";

		if($idProveedor!="" and $idProveedor!="0"){
			$where .= " and p.IdProveedor=$idProveedor ";
		}

		if($fechaDesde!=""){
			$fechaDesde = explode("/", $fechaDesde);
			$fechaDesde = $fechaDesde[2]."-".$fechaDesde[1]."-".$fechaDesde[0];
			$where .= " and p.Fecha >= '$fechaDesde' ";
		}

		if($fechaHasta!=""){
			$fechaHasta = explode("/", $fechaHasta);
			$fechaHasta = $fechaHasta[2]."-".$fechaHasta[1]."-".$fechaHasta[0];
			$where .= " and p.Fecha <= '$fechaHasta' ";
		}

		if($busqueda!=""){
			$where .= " and p.OrdenPago like '%$busqueda%' ";
		}

		$sql = "select
				  p.IdPago,
				  p.IdProveedor,
				  date_format(p.Fecha, '%d/%m/%Y') as Fecha,
				  p.OrdenPago,
				  p.ImporteBruto,
				  p.Retenciones,
				  p.ImporteNeto,
				  concat(po.Cuit, ' ', po.RazonSocial) as Proveedor
				from
				  pago p inner join
				  proveedor po on p.IdProveedor = po.IdProveedor
				$where
				order by
				  p.Fecha desc,
				  p.OrdenPago desc";
		return $sql;
	}

	public static function CertificacionesPendientesPago($idOrganismo, $idProveedor){
		$sql = "select
				  ce.IdCertificacion,
				  concat(og.PrefijoCodigo,'-',o.Codigo,' ',o.Denominacion) as Obra,
				  c.Numero as Contrato,
				  (case
				  	when ce.IdOrdenTrabajo is null then ce.NroCertificado
				  	else concat(ce.NroCertificado,' - OT ', ot.Numero)
				  end) as NroCertificado,
				  concat(substring(ce.Periodo, 5, 2), '/', substring(ce.Periodo, 1, 4)) as Periodo,
				  ce.PorcentajeAvance,
				  ce.MontoAvance,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as PorcentajeAvanceAcum,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as MontoAvanceAcum,
				  ce.ImporteNeto,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as ImportePagado,
				  ImporteNeto - ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as Saldo,
				  null as ImporteAPagar,
				  -1 as TipoPago,
				  'false' as ImporteEnabled,
				  (case
				  	when ce.TipoCertificado=0 then 'Obra'
				  	when ce.TipoCertificado=1 then 'Anticipo'
				  	when ce.TipoCertificado=2 then 'Redeterminación'
				  	when ce.TipoCertificado=3 then 'Adicional'
				  end) as TipoCertificado,
				  ifnull(concat(o.Expediente, '-', ce.Alcance), o.Expediente) as Expediente
				from
				  organismo og inner join
				  obra o on og.IdOrganismo = o.IdOrganismo inner join
				  contrato c on o.IdObra = c.IdObra inner join
				  certificacion ce on c.IdContrato = ce.IdContrato left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo
				where
				  o.IdComitente=$idOrganismo and
				  c.IdProveedor=$idProveedor and
				  ImporteNeto > ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0)
				order by
				  o.Codigo,
				  ot.Numero,
				  ce.Periodo,
				  ce.NroCertificado";

		return $sql;
	}

	public static function CertificacionesPendientesPagoUpdate($idOrganismo, $idProveedor, $idPago){
		$sql = "select
				  ce.IdCertificacion,
				  concat(og.PrefijoCodigo,'-',o.Codigo,' ',o.Denominacion) as Obra,
				  c.Numero as Contrato,
				  (case
				  	when ce.IdOrdenTrabajo is null then ce.NroCertificado
				  	else concat(ce.NroCertificado,' - OT ', ot.Numero)
				  end) as NroCertificado,
				  concat(substring(ce.Periodo, 5, 2), '/', substring(ce.Periodo, 1, 4)) as Periodo,
				  ce.PorcentajeAvance,
				  ce.MontoAvance,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as PorcentajeAvanceAcum,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as MontoAvanceAcum,
				  ce.ImporteNeto,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion and IdPago<>$idPago),0) as ImportePagado,
				  ImporteNeto - ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion and IdPago<>$idPago),0) as Saldo,
				  pc.Importe as ImporteAPagar,
				  1 as TipoPago,
				  'true' as ImporteEnabled,
				  (case
				  	when ce.TipoCertificado=0 then 'Obra'
				  	when ce.TipoCertificado=1 then 'Anticipo'
				  	when ce.TipoCertificado=2 then 'Redeterminación'
				  	when ce.TipoCertificado=3 then 'Adicional'
				  end) as TipoCertificado,
				  ifnull(concat(o.Expediente, '-', ce.Alcance), o.Expediente) as Expediente
				from
				  organismo og inner join
				  obra o on og.IdOrganismo = o.IdOrganismo inner join
				  contrato c on o.IdObra = c.IdObra inner join
				  certificacion ce on c.IdContrato = ce.IdContrato inner join
				  pagocertificacion pc on ce.IdCertificacion = pc.IdCertificacion left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo
				where
				  o.IdOrganismo=$idOrganismo and
				  c.IdProveedor=$idProveedor and
				  pc.IdPago=$idPago
				union
				select
				  ce.IdCertificacion,
				  concat(og.PrefijoCodigo,'-',o.Codigo,' ',o.Denominacion) as Obra,
				  c.Numero as Contrato,
				  (case
				  	when ce.IdOrdenTrabajo is null then ce.NroCertificado
				  	else concat(ce.NroCertificado,' - OT ', ot.Numero)
				  end) as NroCertificado,
				  concat(substring(ce.Periodo, 5, 2), '/', substring(ce.Periodo, 1, 4)) as Periodo,
				  ce.PorcentajeAvance,
				  ce.MontoAvance,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as PorcentajeAvanceAcum,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as MontoAvanceAcum,
				  ce.ImporteNeto,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as ImportePagado,
				  ImporteNeto - ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as Saldo,
				  null as ImporteAPagar,
				  -1 as TipoPago,
				  'false' as ImporteEnabled,
				  (case
				  	when ce.TipoCertificado=0 then 'Obra'
				  	when ce.TipoCertificado=1 then 'Anticipo'
				  	when ce.TipoCertificado=2 then 'Redeterminación'
				  	when ce.TipoCertificado=3 then 'Adicional'
				  end) as TipoCertificado,
				  o.Expediente
				from
				  organismo og inner join
				  obra o on og.IdOrganismo = o.IdOrganismo inner join
				  contrato c on o.IdObra = c.IdObra inner join
				  certificacion ce on c.IdContrato = ce.IdContrato left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo
				where
				  o.IdOrganismo=$idOrganismo and
				  c.IdProveedor=$idProveedor and
				  ImporteNeto > ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) and
				  not exists(select * from pagocertificacion where IdPago=$idPago and IdCertificacion=ce.IdCertificacion)";

		return $sql;
	}

	public static function PagosByCertificado($idCertificacion){
		$sql = "select
				  date_format(p.Fecha, '%d/%m/%Y') as Fecha,
				  p.OrdenPago,
				  pc.Importe
				from
				  pago p inner join
				  pagocertificacion pc on p.IdPago = pc.IdPago
				where
				  pc.IdCertificacion=$idCertificacion
				order by
				  p.Fecha";
		return $sql;
	}

}
?>