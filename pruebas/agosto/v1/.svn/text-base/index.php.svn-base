<?php
session_start();
include_once "conexion.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>S.G.O</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon" />
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <!--    <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href='css/bootstrap-responsive.css' rel='stylesheet'>-->
        <link href="css/style.css" rel="stylesheet">
        <style>
            body{
                background: url(images/fondo.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
            }
        </style>
    </head>
    <body style='margin: 0px'>
        <div class='toolbar' >
            <?php
            $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fechaActual = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
            echo $fechaActual;
            ?>&nbsp;&nbsp;&nbsp;
        </div>
        <div class="container" >
            <div class=" col-lg-12 titulo" >
                <div class="col-lg-2 palabra">S.G.O</div>
                <div class="col-lg-4 col-lg-offset-6 col-xs-5"><img src="images/provinciaBlanco.png" class="img img-responsive"></div>
            </div>
        </div>
        <!--<div class="container">-->
            <?php include("main.php"); ?>    
<!--            <div class="col-lg-4 col-4"><span style="text-align: center;font-size: 18px">inicio de sesión</span>
                <div class="alert alert-info">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                    </form>
                </div>
            </div>-->
        <!--</div>-->
        <!--      <div class="container">
                  <div class="col-lg-12"><img src="images/logoCompleto.png" class="img img-responsive"></div>
              </div>-->
        <!--<div class="salir">   <a href="?logout">Salir</a></div>-->
        <!--    <div class='row-fluid ' style='background: #376b9b;margin-top: 35px;px'>
              <div class='container'>
                  <div class='span3 logoProv'><img src="images/logoProvinciaEscudo.png" alt="logo" class="img"></div>
                <div class='span6'><h1 style="margin: 33PX 0px;">Sistema gerencial de consultas<br> para el seguimiento y control de <br>obras en ejecución</h1></div>
                <div class='span3 logoProv der'><img src="images/logoProvincia.png" alt="logo" class="img"></div>
        
              </div>
            </div>-->
        <!--<div class='row hidden-tablet hidden-phone'  style='height: 55px '></div>-->
<!--        <div class='row-fluid'style='' >
            <?php // include("main.php"); ?>     
        </div>-->
        <br>
        <div class="footer">
            <a href="http://www.puntogap.com.ar" target="_blank">  Desarrollado por PUNTOGAP (<?php echo date('Y') ?>)</a>
        </div>
        <script src='http://code.jquery.com/jquery.js'></script>
        <script src='js/bootstrap.min.js'></script>
        <!--<script>$('.carousel').carousel()</script>-->
    </body>
</html>