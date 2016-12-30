<?php include('conf/db.php'); ?>
<div class="titulo">
<input type="checkbox" checked="checked" onclick="HideShowLayout(this,0);HideShowLayout(this,1);HideShowLayout(this,2);HideShowLayout(this,3);CheckUncheck('zonanorte',this.checked);CheckUncheck('zonacentro',this.checked);CheckUncheck('zonaconfluencia',this.checked);CheckUncheck('zonasur',this.checked);">
<span class="group">ZONAS</span></div>
<div class="subgroup">
	<ul>
		<li >
                    <input id="zonanorte" type="checkbox" checked="checked" onclick="HideShowLayout(this, 0);"><div class='zona uno'></div> ZONA NORTE
		</li>
		<li>
			<input id="zonacentro" type="checkbox" checked="checked" onclick="HideShowLayout(this,1);"><div class='zona dos'></div> ZONA CENTRO
		</li>
		<li>
			<input id="zonaconfluencia" type="checkbox" checked="checked" onclick="HideShowLayout(this,2);"><div class='zona tres'></div> ZONA CONFLUENCIA
		</li>
		<li>
			<input id="zonasur" type="checkbox" checked="checked" onclick="HideShowLayout(this,3);"><div class='zona cuatro'></div> ZONA SUR
		</li>
	</ul>
</div>
<div class="titulo">
<input type="checkbox" checked="checked" onclick="CheckUncheckGroup('compromisoorganismo',this.checked);RefreshData();">
<span class="group">ORGANISMOS</span></div>
<div class="subgroup">
	<ul id="organismos">
		<?php
			$sql = "select * from compromisoorganismo order by Tag";
			$query = mysql_query($sql); 

			while($row = mysql_fetch_object($query)) 
			{ 
		?>
 		<li style="color:<?php echo $row->Color; ?>">
			<input id="compromisoorganismo_<?php echo $row->IdCompromisoOrganismo; ?>" type="checkbox" checked="checked" onclick="RefreshData();"><?php echo $row->Tag; ?>
		</li> 
		<?php
			}

		?>
	</ul>
</div>
