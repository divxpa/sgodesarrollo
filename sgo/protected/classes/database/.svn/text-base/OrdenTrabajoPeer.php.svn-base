<?php
class OrdenTrabajoPeer
{

	public static function SiguienteNumero($idContrato){
		$sql = "select
				  ifnull(max(Numero)+1,1) as Numero
				from
				  ordentrabajo
				where
				  IdContrato=$idContrato";
		return $sql;
	}

	public static function DeductivosByOrdenTrabajo($idOrdenTrabajo){
		$sql = "select 
				  otd.IdOrdenTrabajoDeductivo,
				  otd.IdOrdenTrabajo,
				  otd.Importe,
				  date_format(otd.Fecha, '%d/%m/%Y') as Fecha,
				  otd.NormaLegalAprobacion,
				  c.IdContrato,
				  c.IdObra
				from
				  ordentrabajodeductivo otd inner join
				  ordentrabajo ot on otd.IdOrdenTrabajo = ot.IdOrdenTrabajo inner join
				  contrato c on ot.IdContrato = c.IdContrato
				where
				  otd.IdOrdenTrabajo=$idOrdenTrabajo
				order by
				  otd.Fecha";
		return $sql;
	}

	public static function RecalcularCertificacionesByOrdenTrabajo($idOrdenTrabajo){
		$sql = "call spRecalcularCertificacionesOT($idOrdenTrabajo)";
		return $sql;
	}

	public static function MontoOrdenTrabajo($idOrdenTrabajo){
		$sql = "select
				  ot.Monto - ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=ot.IdOrdenTrabajo),0) as MontoTotal
				from
				  ordentrabajo ot
				where
				  ot.IdOrdenTrabajo=$idOrdenTrabajo";
		return $sql;
	}

	public static function PorcentajeAvance($idOrdenTrabajo, $idCertificacion){
		$where = "";

		if(!is_null($idCertificacion)){
			$where = "and IdCertificacion<>$idCertificacion";
		}

		$sql = "select
					ifnull((select sum(montoavance) from certificacion ce where ce.IdOrdenTrabajo=o.IdOrdenTrabajo $where),0)/(o.Monto- ifnull((select sum(Importe) from ordentrabajodeductivo where IdOrdenTrabajo=o.IdOrdenTrabajo),0))*100 as PorcentajeAvance
				from
					ordentrabajo o
				where
					o.IdOrdenTrabajo=$idOrdenTrabajo";

		return $sql;
	}

}
?>