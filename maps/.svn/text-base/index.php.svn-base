<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" href="css/style.css">
		<link rel="favicon icon" href="images/favicon.ico">
		<title>S.G.O. - GEO</title>
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
  			<div style="float:right; margin-right: 10px;">
		         <img src="images/logo_neuquen.png" height="74px" border="0" />
		    </div>
		  	<div id="logo" style="float:left;">
		         <img src="images/logosgo.png" height="76px" border="0" />
		    </div>
		</div>
            <div class="linea"></div>

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
	</body>
</html>