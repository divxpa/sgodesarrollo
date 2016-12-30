<?php
include_once "conexion.php";
include_once "global/SessionSGO.php";
SessionSGO::init();
//print_r(SessionSGO::get('botones')?SessionSGO::get('botones'):"N");

/* include_once "classes/MySqlQuery.php";
  echo "<pre>";
  print_r(MySqlQuery::getOne("Select 1 as a, 1 as b, 1 as c
  union Select 2 as a, 2 as b, 2 as c
  union Select 3 as a, 3 as b, 3 as c"));
  die ("</pre>"); */
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
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="slider/css/slicebox.css" />
        <link rel="stylesheet" type="text/css" href="slider/css/custom.css" />
        <script type="text/javascript" src="slider/js/modernizr.custom.46884.js"></script>

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
            <div class=" col-lg-12 col-xs-12 titulo" >
                <div class="col-lg-5 col-xs-7 palabra2">SISTEMA GERENCIAL DE CONSULTAS PARA<br>
                    EL SEGUIMIENTO Y CONTROL DE OBRAS EN EJECUCIÓN</div>
                <div class="col-lg-4 col-lg-offset-3 col-xs-5"><img src="images/provinciaBlanco.png" class="img img-responsive"></div>
            </div>
        </div>
        <div class='row-fluid'style='' >
            <?php include("main.php"); ?>     
        </div>
        <br>
        <div class="footer">
            <span style="color:#fff;font-size: 9px">Coordinación Técnica Ministerio de Energía, Servicios Públicos y Recursos Naturales -</span> <a href="http://www.puntogap.com.ar" target="_blank">  Desarrollado por PUNTOGAP (<?php echo date('Y') ?>)</a>
        </div>
        <!--<script src='http://code.jquery.com/jquery.js'></script>-->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

        <script src='js/bootstrap.min.js'></script>
        <script type="text/javascript" src="slider/js/jquery.slicebox.js"></script>
        <script type="text/javascript">
//                    $('button').click(function(){
//$('#myModal').modal('show');
//});
            $(function () {
            $("button#submit").click(function(){
// $('#botones_estado').submit(function() {
console.log('algo');
                console.log($('form').serialize());
		   	$.ajax({
    		   	type: "POST",
			url: "sendSolicitud.php",
			data: $('form').serialize(),
        		success: function(msg){
 	          		  $("#thanks").html(msg)
 		        	$("#myModal").modal('hide');	
 		        },
			error: function(){
				alert("failure");
				}
      			});
	});
                var Page = (function () {

                    var $navArrows = $('#nav-arrows').hide(),
                            $shadow = $('#shadow').hide(),
                            slicebox = $('#sb-slider').slicebox({
                        onReady: function () {

                            $navArrows.show();
                            $shadow.show();

                        },
//							orientation : 'r',
//							cuboidsRandom : true,
                        disperseFactor: 3,
                        interval: 3000,
                        autoplay: true
                    }),
                            init = function () {

                                initEvents();

                            },
                            initEvents = function () {

                                // add navigation events
                                $navArrows.children(':first').on('click', function () {

                                    slicebox.next();
                                    return false;

                                });

                                $navArrows.children(':last').on('click', function () {

                                    slicebox.previous();
                                    return false;

                                });

                            };

                    return {init: init};

                })();

                Page.init();

            });
        </script>
<!--<script>$('.carousel').carousel()</script>-->
    </body>
</html>