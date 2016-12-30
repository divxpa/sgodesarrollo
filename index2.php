<!DOCTYPE html>
<html>
  <head>
    <title>S.G.O</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='css/bootstrap-responsive.css' rel='stylesheet'>
 <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style='margin: 0px'>

          <div class='toolbar' >
              <?php 
                $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 		$fechaActual= $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
                echo $fechaActual?>
          </div>
          <div class='row-fluid ' style='background: #376b9b;margin-top: 35px;px'>
              <div class='container'>
              <div class='span4 logoProv'><img src="images/logoProvincia_2012.png" alt="logo" class="img"></div>
              <div class='span6'><h1 style="margin: 33PX 0px;" >Sistema gerencial de consultas<br> para el siguiemto y control de <br>obras en ejecución</h1></div>
          </div>
         </div>
      <div class='row hidden-tablet hidden-phone'  style='height: 90px '></div>
          <div class='row-fluid'style='' >
              <div class='span12' >
                  <center>
              <div class='span3 offset1 '>  
                  <a href="https://docs.google.com/spreadsheet/ccc?key=0Agy16c4yMPjSdFdrSlk0LXhOX3BoYkxITU04UXh1eWc&usp=drive_web#gid=1" target="_blank">
                  <div class='img2'> 
                      <img  src='images/cubo3_b2.png'>
                      <br>
                      <h3>tablero</h3>
                  </div></a></div>
              <div class='span4 ' >
                  <a href="http://www.mospneuquen.com.ar/sgo" target="_blank">
                  <div class='img2'>
                      
                       <img src='images/cubo3.png'>
                       <br>
                       <h3>S.G.O</h3>
                  </div> </a></div>
                      <div class='span3 ' >
                  <a href="#" target="_blank">
                  <div class='img2'>
                      
                       <img src='images/cuboCalendarioChico.png'>
                       <br>
                       <h3>calendario</h3>
                  </div> </a></div>
                 </center>
               </div>
                 
               </div>
             
         

<div class="footer">
    <a href="http://www.puntogap.com.ar" target="_blank">  Sistema desarrollado por PuntoGap</a>
</div>
  </body>
</html>