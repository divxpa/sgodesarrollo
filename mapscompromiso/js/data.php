<?php

include('../conf/db.php');

$data = array();

$data[][] = array(
				'G', 
				'ZonaNorte', 
				array('-38.369667','-71.217503','-36.104428','-68.253675'),
				'', 
				array('images/zonanorte.png',0.5), 
				1);

$data[][] = array(
				'G', 
				'ZonaCentro', 
				array('-39.531119','-71.466208','-38.17905','-69.270961'), 
				'', 
				array('images/zonacentro.png',0.5),
				1);


$data[][] = array(
				'G', 
				'ZonaConfluencia', 
				array('-39.803547','-70.031742','-37.70205','-67.998147'), 
				'', 
				array('images/zonaconfluencia.png',0.5), 
				1);

$data[][] = array(
				'G', 
				'ZonaSur', 
				array('-41.093842','-71.965469','-39.012383','-69.544461'), 
				'', 
				array('images/zonasur.png',0.5), 
				1);

$sql = 

		// "SELECT 
		  // o.Denominacion,
		  // o.Latitud,
		  // o.Longitud,
		  // og.Nombre as Organismo,
		  // eo.Descripcion as Estado,
		  // 'NCSF' as Zona,
		  // og.Color,
		  // o.IdEstadoObra,
		  // o.IdOrganismo,
		  // o.MemoriaDescriptiva,
		  // ifnull(o.PresupuestoOficial, 0) as PresupuestoOficial,
		  // ifnull(c.Monto, 0) as Monto,
		  // ifnull(date_format(c.FechaInicio, '%d/%m/%Y'), '-') as FechaInicio,
		  // ifnull(date_format(ifnull((select max(NuevaFechaFinalizacion) from contratoplazo where IdContrato=c.IdContrato),c.FechaFinalizacion), '%d/%m/%Y'), '-') as FechaFinalizacion,
		  // ifnull(o.CantidadBeneficiarios, 0) as CantidadBeneficiarios,
		  // ifnull((select sum(ManoObraOcupada) from certificacion ce inner join contrato co on ce.IdContrato=co.IdContrato where co.IdObra=o.IdObra), 0) as CantidadManoObra,
		  // ifnull((select sum(montoavance) from certificacion where IdContrato=c.IdContrato),0)/(c.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=c.IdContrato),0))*100 as PorcentajeAvance,
		  // fnLocalidadesxObra(o.IdObra, ' - ') as Localidades
		// FROM 
		  // obra o inner join
		  // organismo og on o.IdOrganismo = og.IdOrganismo inner join 
		  // estadoobra eo ON o.IdEstadoObra = eo.IdEstadoObra left join 
		  // contrato c on o.IdObra = c.IdObra
		// where 
		  // o.Latitud <> '' and 
		  // o.Longitud <> ''";
		"
		SELECT
			c.compromiso as Denominacion,
			c.Latitud, 
			c.Longitud,
			co.Nombre as Organismo,
			co.Color,
			1 as IdEstadoObra, 
			co.IdCompromisoOrganismo as IdOrganismo, 
			c.Fecha  as FechaInicio, 
			c.Plazo as Plazo, 
			l.Nombre as Localidades,
			u.ApellidoNombre as Responsable	  
		FROM 
			compromiso c inner join
			compromisoresponsable cr on cr.IdCompromisoResponsable = c.IdResponsable inner join
			usuario u on u.IdUsuario = cr.IdUsuario inner join      
			compromisoorganismo co on co.IdCompromisoOrganismo = cr.IdOrganismo inner join
			localidad l on l.IdLocalidad = c.IdLocalidad      
		WHERE
			c.Latitud <> ''
			and  c.Longitud <> '' 
		";
			
$query = mysql_query($sql); 

while($row = mysql_fetch_object($query)) 
{ 	
	$content = "<span class='label' style='font-size: 1em;'>Organismo:</span><span class='data'>" . $row->Organismo . "</span><br />";
	$content = "<span class='label' style='font-size: 1em;'>Localidad:</span><span class='data'>" . $row->Localidades . "</span><br />";
	$content.= "<span class='label' style='font-size: 1em;'>Plazo:</span><span class='data'>" . $row->Plazo . " dias</span><br />";
	$content.= "<span class='label' style='font-size: 1em;'>Responsable:</span><span class='data'>" . $row->Responsable . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Cant. Mano de Obra:</span><span class='data'>" . $row->CantidadManoObra . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Presupuesto Oficial:</span><span class='data'>$ " . number_format($row->PresupuestoOficial, 2, ",", ".") . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Monto de Contrato:</span><span class='data'>$ " . number_format($row->Monto, 2, ",.", ".") . "</span><br />";
	$content.= "<span class='label' style='font-size: 1em;'>Fecha:</span><span class='data'>" . $row->FechaInicio . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>F. Fin:</span><span class='data'>" . $row->FechaFinalizacion . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>% Avance:</span><span class='data'>" . number_format($row->PorcentajeAvance, 2, ",", ".") . " %</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Estado de Obra:</span><span class='data'>" . $row->Estado . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Memoria Descriptiva:</span><span class='data'>" . $row->MemoriaDescriptiva . "</span>";
	
	// MODELO GEO
	// $content = "<span class='label' style='font-size: 1em;'>Organismo:</span><span class='data'>" . $row->Organismo . "</span><br />";
	// $content = "<span class='label' style='font-size: 1em;'>Localidad:</span><span class='data'>" . $row->Localidades . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Cant. Beneficiarios:</span><span class='data'>" . $row->CantidadBeneficiarios . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Cant. Mano de Obra:</span><span class='data'>" . $row->CantidadManoObra . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Presupuesto Oficial:</span><span class='data'>$ " . number_format($row->PresupuestoOficial, 2, ",", ".") . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Monto de Contrato:</span><span class='data'>$ " . number_format($row->Monto, 2, ",.", ".") . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>F. Inicio:</span><span class='data'>" . $row->FechaInicio . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>F. Fin:</span><span class='data'>" . $row->FechaFinalizacion . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>% Avance:</span><span class='data'>" . number_format($row->PorcentajeAvance, 2, ",", ".") . " %</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Estado de Obra:</span><span class='data'>" . $row->Estado . "</span><br />";
	// $content.= "<span class='label' style='font-size: 1em;'>Memoria Descriptiva:</span><span class='data'>" . $row->MemoriaDescriptiva . "</span>";
	// FIN MODELO VIEJO
	$data[][] = array(
					'M', 
					$row->Denominacion, 
					array($row->Latitud, $row->Longitud), 
					$content, 
					array($row->Color), 
					1,
					// $row->Zona,
					// $row->IdOrganismo,
					// $row->IdEstadoObra);
					$row->IdOrganismo);
}

echo json_encode($data);

?>
