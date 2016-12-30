<div class="container principal" >

    <div class="col-lg-6">
        <div class="col-sm-12" style="margin-bottom: 30px"><span class="pull-left" style="margin:10px 0px" >BIENVENIDO <?php echo isset($_SESSION['usrapenom']) ? $_SESSION['usrapenom']: '';?></span></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="http://www.mospneuquen.com.ar/sgo" target="_blank"><img src="images/botones/boton_SGO.png" class="img img-responsive"></a></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="https://docs.google.com/spreadsheet/ccc?key=<?php echo $_SESSION["idpl"]; ?>" target="_blank"><img src="images/botones/boton_Tablero.png" class="img img-responsive"></a></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="http://www.mospneuquen.com.ar/sgo" target="_blank"><img src="images/botones/boton_Calendario.png" class="img img-responsive"></a></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="https://docs.google.com/spreadsheet/ccc?key=0Agy16c4yMPjSdGEyOGpmUFBSSVk4Y3BXTDdQb1ExTkE" target="_blank"><img src="images/botones/boton_TableroUnicficada.png" class="img img-responsive"></a></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="maps/" target="_blank"><img src="images/botones/boton_GEO.png" class="img img-responsive"></a></div>
        <div class="col-lg-6" style="padding: 15px 15px"><a href="#" target="_blank"><img src="images/botones/boton_Compromisos.png" class="img img-responsive"></a></div>
            
    </div>
    <div class="col-lg-6">
            <span class="pull-right" style="margin:10px 0px"><a href='index.php?logout=true'> SALIR</a></span>
         <img src="images/Foto1.png" class="img img-responsive">
    </div>
</div>