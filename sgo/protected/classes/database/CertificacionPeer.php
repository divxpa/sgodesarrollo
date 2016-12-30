<?php
class CertificacionPeer
{
	public static function CertificacionesHome($idContrato, $idOrganismo){
		$where = " where ce.IdContrato=$idContrato ";

		$sql = "select
				  ce.IdCertificacion,
				  ce.IdContrato,
				  co.IdObra,
				  (case
				  	when ce.IdOrdenTrabajo is null then ce.NroCertificado
				  	else concat(ce.NroCertificado,' - OT ', ot.Numero)
				  end) as NroCertificado,
				  concat(substring(ce.Periodo, 5, 2), '/', substring(ce.Periodo, 1, 4)) as Periodo,
				  /*ce.PorcentajeAvance,*/
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      ce.MontoAvance/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
				    else
				      ce.MontoAvance/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
				  end) as PorcentajeAvance,
				  ce.MontoAvance,
				  ce.AnticipoFinanciero,
				  ce.DescuentoAnticipo,
				  ce.RetencionMulta,
				  ce.RetencionFondoReparo,
				  ce.RedeterminacionPrecios,
				  ce.OtrosConceptos,
				  ce.ImporteNeto,
				  /*(case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(PorcentajeAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as PorcentajeAvanceAcum,*/
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
				  end) as PorcentajeAvanceAcum,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as MontoAvanceAcum,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as ImportePagado,
				  ImporteNeto - ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as Saldo,
				  ce.ManoObraOcupada,
				  (case
				  	when ce.TipoCertificado=0 then 'Obra'
				  	when ce.TipoCertificado=1 then 'Anticipo'
				  	when ce.TipoCertificado=2 then 'Redeterminación'
				  	when ce.TipoCertificado=3 then 'Adicional'
				  end) as TipoCertificado,
				  (case
				  	when o.IdOrganismo=o.IdComitente then 'true'
				  	when o.IdComitente=$idOrganismo then 'false'
				  	else 'true' 
				  end) as EditarVisible
				from
				  certificacion ce inner join
				  contrato co on ce.IdContrato=co.IdContrato left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo inner join
				  obra o on co.IdObra=o.IdObra
				$where 
				order by
				  ot.Numero,
				  ce.Periodo,
				  ce.NroCertificado";
		return $sql;
	}

	public static function SiguienteNumeroCertificado($idContrato, $tipo="0", $idOrdenTrabajo=""){
		$whereAux = "";

		if($idOrdenTrabajo!="" and $idOrdenTrabajo!="0"){
			$whereAux = " and IdOrdenTrabajo=$idOrdenTrabajo";
		}
		else{
			$whereAux = " and IdOrdenTrabajo is null";
		}

		$sql = "select
				  ifnull(max(NroCertificado)+1,1) as Numero,
				  ifnull(date_format(DATE_ADD(str_to_date(concat(max(Periodo), '01'), '%Y%m%d'), INTERVAL 1 MONTH), '%m/%Y'), '') as Periodo
				from
				  certificacion
				where
				  IdContrato=$idContrato and
				  TipoCertificado=$tipo $whereAux";

		return $sql;
	}

	public static function SiguienteNumeroCertificacionPorPeriodo($idContrato, $tipo="0", $periodo){
		$sql = "select
				  ifnull(max(NroCertificado)+1,1) as Numero
				from
				  certificacion
				where
				  IdContrato=$idContrato and
				  TipoCertificado=$tipo and
				  Periodo<='$periodo'";
		return $sql;
	}

	public static function CertificacionesPorPeriodo($idOrganismo, $periodo){
		$sql = "select
				  *
				from
				(select
				  o.IdObra,
				  co.IdContrato,
				  ce.IdCertificacion,
				  concat(og.PrefijoCodigo, '-', o.Codigo, ' ', o.Denominacion, ' - ', fnLocalidadesxObra(o.IdObra,' - ')) as Obra,
				  concat(co.Numero, ' - ', p.Cuit, ' ', p.RazonSocial) as Contrato,
				  ce.NroCertificado,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      ce.MontoAvance/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
				    else
				      ce.MontoAvance/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
				  end) as PorcentajeAvance,
				  ce.MontoAvance,
				  ce.AnticipoFinanciero,
				  ce.DescuentoAnticipo,
				  ce.RetencionMulta,
				  ce.RetencionFondoReparo,
				  ce.RedeterminacionPrecios,
				  ce.OtrosConceptos,
				  ce.ImporteNeto,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ce.ManoObraOcupada,
				  ce.TipoCertificado
				from
				  organismo og inner join
				  obra o on og.IdOrganismo = o.IdOrganismo inner join
				  contrato co on o.IdObra = co.IdObra inner join
				  proveedor p on co.IdProveedor = p.IdProveedor inner join
				  certificacion ce on ce.IdContrato = co.IdContrato left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo
				where
				  og.IdOrganismo=$idOrganismo and
				  ce.Periodo='$periodo'
				union
				select
				  o.IdObra,
				  co.IdContrato,
				  null as IdCertificacion,
				  concat(og.PrefijoCodigo, '-', o.Codigo, ' ', o.Denominacion, ' - ', fnLocalidadesxObra(o.IdObra,' - ')) as Obra,
				  concat(co.Numero, ' - ', p.Cuit, ' ', p.RazonSocial) as Contrato,
				  ifnull((select max(NroCertificado)+1 from certificacion where IdContrato=co.IdContrato and Periodo<'$periodo' and TipoCertificado=0),1) NroCertificado,
				  null as PorcentajeAvance,
				  null as MontoAvance,
				  null as AnticipoFinanciero,
				  null as DescuentoAnticipo,
				  null as RetencionMulta,
				  null as RetencionFondoReparo,
				  null as RedeterminacionPrecios,
				  null as OtrosConceptos,
				  null as ImporteNeto,
				  null as FechaVtoPago,
				  null as ManoObraOcupada,
				  0 as TipoCertificado
				from
				  organismo og inner join
				  obra o on og.IdOrganismo = o.IdOrganismo inner join
				  contrato co on o.IdObra = co.IdObra inner join
				  proveedor p on co.IdProveedor = p.IdProveedor 
				where
				  og.IdOrganismo=$idOrganismo and
				  not exists(select * from certificacion where IdContrato=co.IdContrato and Periodo='$periodo') and
				  ifnull((select sum(PorcentajeAvance) from certificacion where IdContrato=co.IdContrato and Periodo<='$periodo'),0)<100
				) v
				order by
				  Obra,
				  Contrato";

		return $sql;
	}

	public static function ObrasReportDetalle($idContrato){
		$where = " where ce.IdContrato=$idContrato ";

		$sql = "select
				  ce.IdCertificacion,
				  ce.IdContrato,
				  co.IdObra,
				  (case
				  	when ce.IdOrdenTrabajo is null then ce.NroCertificado
				  	else concat(ce.NroCertificado,' - OT ', ot.Numero)
				  end) as NroCertificado,
				  concat(substring(ce.Periodo, 5, 2), '/', substring(ce.Periodo, 1, 4)) as Periodo,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      ce.MontoAvance/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
				    else
				      ce.MontoAvance/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
				  end) as PorcentajeAvance,
				  ce.MontoAvance,
				  ce.AnticipoFinanciero,
				  ce.DescuentoAnticipo,
				  ce.RetencionMulta,
				  ce.RetencionFondoReparo,
				  ce.RedeterminacionPrecios,
				  ce.OtrosConceptos,
				  ce.ImporteNeto,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
				  end) as PorcentajeAvanceAcum,
				  (case
				    when ce.IdOrdenTrabajo is not null then
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo and IdOrdenTrabajo=ce.IdOrdenTrabajo)
				    else
				      (select sum(MontoAvance) from certificacion where IdContrato=ce.IdContrato and Periodo <= ce.Periodo)
				  end) as MontoAvanceAcum,
				  date_format(ce.FechaVtoPago, '%d/%m/%Y') as FechaVtoPago,
				  ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as ImportePagado,
				  ImporteNeto - ifnull((select sum(Importe) from pagocertificacion where IdCertificacion=ce.IdCertificacion),0) as Saldo,
				  ce.ManoObraOcupada,
				  (case
				  	when ce.TipoCertificado=0 then 'Obra'
				  	when ce.TipoCertificado=1 then 'Anticipo'
				  	when ce.TipoCertificado=2 then 'Redeterminación'
				  	when ce.TipoCertificado=3 then 'Adicional'
				  end) as TipoCertificado,
				  (select date_format(max(Fecha), '%d/%m/%Y') from pagocertificacion pc inner join pago p on pc.IdPago=p.IdPago where pc.IdCertificacion=ce.IdCertificacion) as FechaPago
				from
				  certificacion ce inner join
				  contrato co on ce.IdContrato=co.IdContrato left join
				  ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo inner join
				  obra o on co.IdObra=o.IdObra
				$where 
				order by
				  ot.Numero,
				  ce.Periodo,
				  ce.NroCertificado";
		return $sql;
	}

	public static function getCertificacion($idCertificacion){
		$sql = "select 
                                ce.*,
					(case
					  when ce.IdOrdenTrabajo is not null then
					    ce.MontoAvance/(ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0))*100
					  else
					    ce.MontoAvance/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100
					end) as PorcentajeAvanceCalc,
                                        	ifnull((select max(p.Fecha) from pagocertificacion pce inner join 
                                                pago p on p.IdPago=pce.IdPago                                 
					where pce.IdCertificacion=$idCertificacion and (ce.AnticipoFinanciero is not null or ce.AnticipoFinanciero<>0 )),0) as FechaPagoAnticipo
				from 
					certificacion ce inner join
					contrato co on ce.IdContrato=co.IdContrato left join
					ordentrabajo ot on ce.IdOrdenTrabajo=ot.IdOrdenTrabajo
				where 
					IdCertificacion=$idCertificacion";

		return $sql;
	}
public static function getCertificacionAnterior($idContrato,$periodo){
    $sql = "select sum(ce.ImporteNeto) as ImporteCertifAnterior,sum(ce.AnticipoFinanciero) as AnticipoFinancieroAnterior,
                sum(ce.DescuentoAnticipo) as DescuentoAnticipoAnterior,
                    (select max(p.Fecha) from pagocertificacion pce inner join pago p on p.IdPago=pce.IdPago where pce.IdCertificacion=ce.IdCertificacion ) as FechaUltimoPago
				from 
                                        certificacion ce  inner join
					contrato co on ce.IdContrato=co.IdContrato 
				where 
					co.IdContrato=$idContrato and ce.Periodo< '$periodo' order by ce.IdCertificacion desc";

		return $sql;
	}
}
?>