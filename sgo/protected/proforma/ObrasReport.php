<h1>Reporte de Obras</h1>

<div class="filter">
	<h2>Filtros</h2>
	<table>
		<tr>
			<td valign="top" width="10%">C&oacute;digo:</td>
			<td valign="top" width="10%"><?php echo $filters[0]; ?></td>
			<td valign="top" width="10%">Denominaci&oacute;n:</td>
			<td valign="top" width="10%"><?php echo $filters[1]; ?></td>
			<td valign="top" width="10%">Expediente:</td>
			<td valign="top" width="10%"><?php echo $filters[2]; ?></td>
			<td valign="top" width="10%">Localidad:</td>
			<td valign="top" width="10%"><?php echo $filters[3]; ?></td>
			<td valign="top" width="10%">Estado:</td>
			<td valign="top" width="10%"><?php echo $filters[4]; ?></td>
		</tr>
	</table>
</div>

<table>
	<thead>
		<tr>
			<th align="center" valign="top" width="4%">C&oacute;d.</th>
			<th align="center" valign="top" width="16%">Denominaci&oacute;n</th>
			<th align="center" valign="top" width="10%">Localidad</th>
			<th align="center" valign="top" width="7%">Expediente</th>
			<th align="center" valign="top" width="10%">Proveedor</th>
			<th align="center" valign="top" width="4%">Nro. Cont.</th>
			<th align="center" valign="top" width="7%">Monto Cont.</th>
			<th align="center" valign="top" width="7%">Alter.</th>
			<th align="center" valign="top" width="7%">Red. Prec.</th>
			<th align="center" valign="top" width="4%">% Certif.</th>
			<th align="center" valign="top" width="7%">$ Certif.</th>
			<th align="center" valign="top" width="7%">Estado</th>
			<th align="center" valign="top" width="10%">Detalle</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($data as $d){
?>
		<tr>
			<td align="left" valign="top"><?php echo $d["Codigo"]; ?></td>
			<td align="left" valign="top"><?php echo $d["Denominacion"]; ?></td>
			<td align="left" valign="top"><?php echo $d["Localidad"]; ?></td>
			<td align="left" valign="top"><?php echo $d["Expediente"]; ?></td>
			<td align="left" valign="top"><?php echo $d["Proveedor"]; ?></td>
			<td align="left" valign="top"><?php echo $d["NroContrato"]; ?></td>
			<td align="right" valign="top"><?php echo number_format(floatval($d["Monto"]), 2, ",", "."); ?></td>
			<td align="right" valign="top"><?php echo number_format(floatval($d["Alteracion"]), 2, ",", "."); ?></td>
			<td align="right" valign="top"><?php echo number_format(floatval($d["RedeterminacionPrecios"]), 2, ",", "."); ?></td>
			<td align="right" valign="top"><?php echo number_format(floatval($d["PorcentajeAvance"]), 2, ",", "."); ?></td>
			<td align="right" valign="top"><?php echo number_format(floatval($d["MontoAvance"]), 2, ",", "."); ?></td>
			<td align="left" valign="top"><?php echo $d["Estado"]; ?></td>
			<td align="left" valign="top"><?php echo $d["DetalleEstado"]; ?></td>
		</tr>
<?php
		$certificaciones = $d["Certificaciones"];

		if(is_array($certificaciones) and count($certificaciones)){
?>
		<tr>
			<td>&nbsp;</td>
			<td colspan="12">
				<table width="1000px" class="subtabla">
					<tr class="head">
						<td align="center" valign="top" width="6%">Tipo</td>
						<td align="center" valign="top" width="3%">Nro.</td>
						<td align="center" valign="top" width="5%">Periodo</td>
						<td align="center" valign="top" width="4%">% Cert.</td>
						<td align="center" valign="top" width="7%">$ Cert.</td>
						<td align="center" valign="top" width="7%">Ant. Fin.</td>
						<td align="center" valign="top" width="7%">Dto. Ant.</td>
						<td align="center" valign="top" width="7%">Ret. Multa</td>
						<td align="center" valign="top" width="6%">Fdo. Rep.</td>
						<td align="center" valign="top" width="6%">Red. Prec.</td>
						<td align="center" valign="top" width="6%">Otros</td>
						<td align="center" valign="top" width="7%">Imp. Neto</td>
						<td align="center" valign="top" width="5%">M. Obra</td>
						<td align="center" valign="top" width="5%">F. Vto.</td>
						<td align="center" valign="top" width="7%">Imp. Pag.</td>
						<td align="center" valign="top" width="5%">F. Pag.</td>
						<td align="center" valign="top" width="7%">Saldo</td>
					</tr>
					<tbody>						
<?php
			$porcentajeAvance =
			$montoAvance =
			$anticipoFinanciero =
			$descuentoAnticipo =
			$retencionMulta =
			$retencionFondoReparo =
			$redeterminacionPrecios =
			$otrosConceptos =
			$importeNeto =
			$manoObraOcupada =
			$importePagado =
			$saldo = 0;

			foreach($certificaciones as $d1){
				$porcentajeAvance += $d1["PorcentajeAvance"];
				$montoAvance += $d1["MontoAvance"];
				$anticipoFinanciero += $d1["AnticipoFinanciero"];
				$descuentoAnticipo += $d1["DescuentoAnticipo"];
				$retencionMulta += $d1["RetencionMulta"];
				$retencionFondoReparo += $d1["RetencionFondoReparo"];
				$redeterminacionPrecios += $d1["RedeterminacionPrecios"];
				$otrosConceptos += $d1["OtrosConceptos"];
				$importeNeto += $d1["ImporteNeto"];
				$manoObraOcupada += $d1["ManoObraOcupada"];
				$importePagado += $d1["ImportePagado"];
				$saldo += $d1["Saldo"];
?>
					<tr>
						<td align="left" valign="top"><?php echo $d1["TipoCertificado"]; ?></td>
						<td align="center" valign="top"><?php echo $d1["NroCertificado"]; ?></td>
						<td align="center" valign="top"><?php echo $d1["Periodo"]; ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["PorcentajeAvance"])){echo number_format(floatval($d1["PorcentajeAvance"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["MontoAvance"])){echo number_format(floatval($d1["MontoAvance"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["AnticipoFinanciero"])){echo number_format(floatval($d1["AnticipoFinanciero"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["DescuentoAnticipo"])){echo number_format(floatval($d1["DescuentoAnticipo"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["RetencionMulta"])){echo number_format(floatval($d1["RetencionMulta"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["RetencionFondoReparo"])){echo number_format(floatval($d1["RetencionFondoReparo"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["RedeterminacionPrecios"])){echo number_format(floatval($d1["RedeterminacionPrecios"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["OtrosConceptos"])){echo number_format(floatval($d1["OtrosConceptos"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["ImporteNeto"])){echo number_format(floatval($d1["ImporteNeto"]), 2, ",", ".");} ?></td>
						<td align="right" valign="top"><?php echo $d1["ManoObraOcupada"]; ?></td>
						<td align="center" valign="top"><?php echo $d1["FechaVtoPago"]; ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["ImportePagado"])){echo number_format(floatval($d1["ImportePagado"]), 2, ",", ".");} ?></td>
						<td align="center" valign="top"><?php echo $d1["FechaPago"]; ?></td>
						<td align="right" valign="top"><?php if(!is_null($d1["Saldo"])){echo number_format(floatval($d1["Saldo"]), 2, ",", ".");} ?></td>
					</tr>
<?php
			}
?>				
					</tbody>
					<tfoot>
						<tr>
							<td align="center" colspan="3">TOTALES</td>
							<td align="right" valign="top"><?php echo number_format($porcentajeAvance, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($montoAvance, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($anticipoFinanciero, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($descuentoAnticipo, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($retencionMulta, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($retencionFondoReparo, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($redeterminacionPrecios, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($otrosConceptos, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($importeNeto, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo $manoObraOcupada; ?></td>
							<td align="right" valign="top"><?php echo number_format($importePagado, 2, ",", "."); ?></td>
							<td align="right" valign="top"><?php echo number_format($saldo, 2, ",", "."); ?></td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
<?php
		}

	}
?>
	</tbody>
</table>
<h2>Cantidad de registros: <?php echo count($data); ?></h2>