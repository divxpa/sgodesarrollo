
<div class="container principal" >
                    <div class="col-sm-12" style="margin-bottom: 30px"><span class="pull-left" style="margin:10px 0px" >BIENVENIDO <?php echo isset($_SESSION['usrapenom']) ? $_SESSION['usrapenom'] : ''; ?></span> <span class="pull-right" style="margin:10px 0px"><a href='index.php?logout=true'> SALIR</a></span></div>

    <div class="col-lg-6 col-sm-6">

        <div class="alert alert-gris" style="height: 315px;">
            <div class="col-lg-6" style="padding: 15px 15px"><a href="http://www.mospneuquen.com.ar/sgo" target="_blank"><img src="images/botones/boton_SGO.png" class="img img-responsive"></a></div>
            <div class="col-lg-6" style="padding: 15px 15px"><a href="https://docs.google.com/spreadsheet/ccc?key=<?php echo $_SESSION["idpl"]; ?>" target="_blank"><img src="images/botones/boton_Tablero.png" class="img img-responsive"></a></div>
            <div class="col-lg-6" style="padding: 15px 15px"><a href="http://www.mospneuquen.com.ar/sgo" target="_blank"><img src="images/botones/boton_Calendario.png" class="img img-responsive"></a></div>
            <div class="col-lg-6" style="padding: 15px 15px"><a href="https://docs.google.com/spreadsheet/ccc?key=0Agy16c4yMPjSdGEyOGpmUFBSSVk4Y3BXTDdQb1ExTkE" target="_blank"><img src="images/botones/boton_TableroUnicficada.png" class="img img-responsive"></a></div>
            <div class="col-lg-6" style="padding: 15px 15px"><a href="maps/" target="_blank"><img src="images/botones/boton_GEO.png" class="img img-responsive"></a></div>
            <div class="col-lg-6" style="padding: 15px 15px"><a href="#" target="_blank"><img src="images/botones/boton_Compromisos.png" class="img img-responsive"></a></div>
        </div> 
    </div>
    <div class="col-lg-6 col-sm-6">
       
        <div class="wrapper" style="margin-top: -15px">

            <ul id="sb-slider" class="sb-slider">
                <li>
                    <img src="slider/images/1.png" alt="Estacion trasformadora Loma Campana. EPEN"/>
                    <div class="sb-description">
                        <h3>Estacion trasformadora Loma Campana. EPEN</h3>
                    </div>
                </li>
                <li>
                    <img src="slider/images/2.png" alt="Planta Cloacal de Andacollo. EPAS"/>
                    <div class="sb-description">
                        <h3>Planta Cloacal de Andacollo. EPAS</h3>
                    </div>
                </li>
                <li>
                    <img src="slider/images/3.png" alt="Direccion Provincial de Vialidad. DPV"/>
                    <div class="sb-description">
                        <h3>Direccion Provincial de Vialidad. DPV</h3>
                    </div>
                </li>
                <li>
                    <img src="slider/images/4.png" alt="Recursos Hídricos. RH"/>
                    <div class="sb-description">
                        <h3>Recursos Hídricos. RH</h3>
                    </div>
                </li>


            </ul>

            <div id="shadow" class="shadow"></div>

            <div id="nav-arrows" class="nav-arrows">
                <a href="#">Next</a>
                <a href="#">Previous</a>
            </div>

        </div>
<!--<img src="images/Foto1.png" class="img img-responsive">-->
    </div>
</div>