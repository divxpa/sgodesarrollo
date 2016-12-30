<?php

function verificar_login($user, $password, &$result) {
    $sql = "SELECT * FROM usuariomosp WHERE Username = '$user' and Password = md5('$password')";
    $rec = mysql_query($sql);
    $count = 0;

    while ($row = mysql_fetch_object($rec)) {
        $count++;
        $result = $row;
    }

    if ($count == 1) {
        return 1;
    } else {
        return 0;
    }
}
?>
<?php
$error='';
if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];

    if (verificar_login($user, $password, $result) == 1) {
        $_SESSION['usr'] = $result->IdUsuario;
        $_SESSION['idpl'] = $result->IdPlanilla;
        $_SESSION['usrapenom'] = $result->ApellidoNombre;
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