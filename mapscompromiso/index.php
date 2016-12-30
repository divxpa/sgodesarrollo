<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" href="css/style.css">
		<link rel="favicon icon" href="images/favicon.ico">
		<title>S.G.O. - GEO COMPROMISO</title>
                                 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQzU87aK3pnqRUsL54i5BdVP335F32N1w"
  type="text/javascript"></script>
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="conf/settings.js"></script>
		<script type="text/javascript" src="js/functions_original.js"></script>
		<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript">
			function CheckUncheck(id,checked){
				var control = document.getElementById(id);
				control.checked = checked;
			}

			function MaximizeMinimize(id, child){
				var control = document.getElementById(id);
				var childControl = document.getElementById(child);

				if(control.innerHTML=="[+]"){
					control.innerHTML="[-]";
					childControl.style.display = "block";
				}
				else{
					control.innerHTML="[+]"
					childControl.style.display = "none";
				}

			}

			function CheckUncheckGroup(group, checked){
				$("input[id*="+ group +"]").each(function () {
						this.checked = checked;
				});
			}

		</script>
	</head>
	<body onload="initialize();">

		<div id="header">
  			       <div style="float:left; " class="palabra">
		         SISTEMA GERENCIAL DE CONSULTAS PARA<br>
EL SEGUIMIENTO Y CONTROL DE OBRAS EN EJECUCIÓN
		    </div>
		  	<div id="logo" style="float:right;">
                            <img style="padding: 5px 20px;height: 60px;" src="images/provinciaBlanco.png" height="76px" border="0" />
		    </div>
		</div>
            <!--<div class="linea"></div>-->

		<div id="sidebar">
			<!--<div id="logoIadep" >
				<img src="images/logo1.png" 	/>
			</div>
			<br />-->
			<?php
				include('sidebar.php');
			?>
			
		</div>

			<div id="map"></div>

		<div id="locations"></div>
                  <div class="footer">
        <span style="color:#fff;font-size: 9px">Coordinación Técnica Ministerio de Energía, Servicios Públicos y Recursos Naturales -</span> <a href="http://www.puntogap.com.ar" target="_blank">  Desarrollado por PUNTOGAP (<?php echo date('Y')?>)</a>
    </div>
	</body>
</html>