
<?php
    // Cachea los botones:
    $result = [];
    $sql = "SELECT
        *
    FROM 
        boton WHERE Activo <> 0 ORDER BY Orden ASC";
    $rec = mysql_query($sql);
    while ($row = mysql_fetch_object($rec))
        $result[] = json_decode(json_encode($row), true);

    //echo "<pre>"; print_r($result); die();

?>

<div class="container principal" >
    <div class="col-sm-12" style="margin-bottom: 30px"><span class="pull-left" style="margin:10px 0px" >BIENVENIDO <?php echo SessionSGO::get('usr_apellidoNombre'); ?></span> <span class="pull-right" style="margin:10px 0px"><a href='index.php?logout=true'> SALIR</a></span></div>
    <div class="col-lg-6 col-sm-6">
        <div class="alert alert-gris" style="height: 385px;">

            <?php foreach ($result as $boton) { ?>

                <div class="col-lg-6" style="padding: 15px 15px">   
                    <?php if (in_array($boton['Nombre'], SessionSGO::get('usr_botones'))) { ?>  
                        <a href="<?php echo $boton['Link'];?>" target="_blank"><img src="images/botones/<?php echo $boton['Img'];?>" class="img img-responsive"></a>
                    <?php } else { ?>
                        <a href="javascript:void(0)" title="Sin autorización de acceso a la aplicación" ><img style="cursor: default; opacity: 0.5;"  src="images/botones/<?php echo $boton['Img'];?>" class="img img-responsive"></a>
                    <?php } ?>
                </div>

            <?php } ?>

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

                <li>
                    <img src="slider/images/5.png" alt="Planta de Hidenesa"/>
                    <div class="sb-description">
                        <h3>Planta de Hidenesa</h3>
                    </div>
                </li>
            </ul>

            <div id="shadow" class="shadow"></div>

            <div id="nav-arrows" class="nav-arrows">
                <a href="#">Next</a>
                <a href="#">Previous</a>
            </div>

        </div>
        <div class="boxBlanco">
            <label>Mesa de Ayuda:</label> ayudasgo@neuquen.gov.ar <br>
            <label>Teléfono:</label> 449-4840 <br>
            <!--<label>Solicitar Acceso</label>-->

            <!-- Button trigger modal -->
<!--          <div id="thanks">  <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                Solicitar acceso
              </button>
          </div>-->

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" >
                    <div class="modal-content"  id="contact_dialog" role="dialog"> 
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Solicitar acceso</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="acceso" >
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Nombre</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Apellidos</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Dependencia</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="dependencia" name="dependencia" placeholder="Dependencia">
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Teléfono</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">Descripción</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="descripcion" name="descripcion" placeholder=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm"  id="submit">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>