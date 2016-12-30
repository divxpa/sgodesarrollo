<h1 style="text-align: left;">Detalle de Alarma</h1>
<h2>Alarma: <?php echo $data["Alarma"]; ?></h2>
<table>
	<thead>
		<tr>
<?php
	if(count($data["Alarmas"])){
		$tableHeader = $data["Alarmas"][0];
		
		foreach ($tableHeader as $clave=>$valor) {
?>
			<th align="center" valign="top"><?php echo $clave; ?></th>
		
<?php
		}
	}	
?>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($data["Alarmas"] as $d){
?>
		<tr>
<?php
		foreach ($d as $clave=>$valor) {
?>
			<td align="left" valign="top"><?php echo $valor; ?></td>
<?php
		}
?>
		</tr>
<?php
	}
?>
	</tbody>
</table>
