<?php
include_once "classes/RegisterLog.php";

function verificar_login($user, $password, &$result) {
    $sql = "SELECT 
        usuario.IdUsuario,
        usuario.Username,
        usuario.IdPlanilla,
        usuario.ApellidoNombre,
        usuario.Sgo,
        usuario.Tablero,
        usuario.Geo,
        usuario.GeoCompromisos,
        usuario.Compromisos,
        usuario.CertificacionMunicipio,
        usuario.Calendario,
        usuario.TableroUnificado,
        usuario.Activo AS SgoActivo,
        ifnull(usuario.IdOrganismo, 0) AS SgoIdOrganismo,
        usuario.IdRol AS SgoIdRol,
        usuario.Email AS SgoEmail,
        ifnull(organismo.Nombre, '') AS SgoNombreOrganismo
    FROM 
        usuario 
        LEFT JOIN organismo AS organismo ON organismo.IdOrganismo = usuario.IdOrganismo
    WHERE 
        Username = '$user' and Password = md5('$password') limit 1";

    //echo "<pre>";print_r($sql); die();

    $rec = mysql_query($sql);
    if ($exist = ($row = mysql_fetch_object($rec)))
        $result = json_decode(json_encode($row), true); //convierte a array


    // Cachea los roles:
    $idUsuario = $result['IdUsuario'];
    $result['Roles'] = [];
    $sql = "SELECT IdRol FROM usuario_rol WHERE IdUsuario = '$idUsuario'";
    $rec = mysql_query($sql);
    while ($row = mysql_fetch_object($rec))
        $result['Roles'][] = $row->IdRol;


    // Cachea los botones:
    $result['Botones'] = [];
    $sql = "SELECT DISTINCT
        boton.Nombre 
    FROM 
        usuario_rol 
        INNER JOIN rolboton ON usuario_rol.IdRol = rolboton.IdRol
        INNER JOIN boton ON rolboton.IdBoton = boton.IdBoton
    WHERE IdUsuario = '$idUsuario'";
    $rec = mysql_query($sql);
    while ($row = mysql_fetch_object($rec))
        $result['Botones'][] = $row->Nombre;



    //echo "<pre>";print_r($sql); die();
    return $exist;

}// --ifnull(usuario.IdOrganismo, 0) AS SgoIdOrganismo, //  usuario.IdOrganismo AS SgoIdOrganismo,
?> 

<?php
$error='';
if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];

    if (verificar_login($user, $password, $result)) {

        // Guarda registro del login en la Base
        RegisterLog::login($result['IdUsuario'], SessionSGO::getId());

        // Inicializa variables de sesión

        SessionSGO::set('usr_id', $result['IdUsuario']);
        SessionSGO::set('usr_username', $result['Username']);
        SessionSGO::set('usr_idPlanilla', $result['IdPlanilla']);
        SessionSGO::set('usr_apellidoNombre', $result['ApellidoNombre']);
        SessionSGO::set('usr_roles', $result['Roles']);
        SessionSGO::set('usr_botones', $result['Botones']);

        SessionSGO::set('usr_sgo', $result['Sgo'] && $result['SgoActivo']);
        SessionSGO::set('usr_tablero', $result['Tablero']);
        SessionSGO::set('usr_geo', $result['Geo']);
        SessionSGO::set('usr_geocompromisos', $result['GeoCompromisos']);
        SessionSGO::set('usr_compromisos', $result['Compromisos']);
        SessionSGO::set('usr_cetificacionmunicipio', $result['CertificacionMunicipio']);
        SessionSGO::set('usr_calendario', $result['Calendario']);
        SessionSGO::set('usr_tablerounificado', $result['TableroUnificado']);

        
        if (SessionSGO::get('usr_sgo') || SessionSGO::get('usr_calendario')) {
            SessionSGO::set('usr_sgo_idOrganismo', $result['SgoIdOrganismo']);
            //SessionSGO::set('usr_sgo_idRol', $result['SgoIdRol']);
            SessionSGO::set('usr_sgo_email', $result['SgoEmail']);
            SessionSGO::set('usr_sgo_nombreOrganismo', $result['SgoNombreOrganismo']);
        }

        //echo "<pre>"; print_r($_SESSION); die();
        header("location:index.php");
    } else {
        $error='<div class="error" >Su usuario es incorrecto, intente nuevamente.</div>';
    }

}
?>
<div class="container" style="margin-top: 7%">
    <div class="col-lg-6 col-xs-6">
		<div class="wrapper">

            <ul id="sb-slider" class="sb-slider" style="padding-top: 15px">
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
    <div class="col-lg-6 " ><span style="text-align: center;font-size: 18px;display: block">inicio de sesión</span>
        <div class="alert alert-gris" style="height: 315px;padding-top: 75px">
            <form class="form-horizontal" action="" method="post" id="main">
                <div class="form-group">
                    <label for="user" class="col-sm-4 control-label">nombre de usuario</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="user" name="user" placeholder="nombre de usuario">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">contraseña</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="contraseña">
                    </div>
                </div>
               <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> recordarme
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class=" col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-azul btn-block" name="login">Ingresar</button>
                    </div>
                </div>
                 
            </form>
            <?php echo $error; ?>
        </div>
    </div>

</div>