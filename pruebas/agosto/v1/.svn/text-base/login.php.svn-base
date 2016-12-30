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

<!--<div style="text-align: left; margin-left: 50px;" class="span3">

        <form action="" method="post" id="main">	
                <label>Nombre de usuario:</label>
                <input type="text" name="user" placeholder="Nombre de Usuario">
                <label>Contraseña:</label>
                <input type="password" name="password" placeholder="Contraseña">
                <br />
                <input name="login" type="submit" value="Ingresar">
        </form>

<?php
//		if(isset($_POST['login'])){
//			$user = $_POST['user'];
//			$password = $_POST['password'];
//
//			if(verificar_login($user, $password, $result) == 1) 
//	        { 
//	            $_SESSION['usr'] = $result->IdUsuario; 
//	            $_SESSION['idpl'] = $result->IdPlanilla; 
//	            $_SESSION['usrapenom'] = $result->ApellidoNombre; 
//	            header("location:index.php"); 
//	        } 
//	        else 
//	        { 
//	            echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>'; 
//	        } 
//
//		}
?>

</div>-->
<div class="container" style="margin-top: 8%">
    <div class="col-lg-6 col-lg-offset-3"><span style="text-align: center;font-size: 18px">inicio de sesión</span>
        <div class="alert alert-gris" >
            <form class="form-horizontal"  action="" method="post" id="main">
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
                <div class="form-group" style="margin-bottom: 0px">
                    <div class=" col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-azul btn-block" name="login">Ingresar</button>
                    </div>
                </div>
                 
            </form>
        </div>
    </div>
<?php
if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];

    if (verificar_login($user, $password, $result) == 1) {
        $_SESSION['usr'] = $result->IdUsuario;
        $_SESSION['idpl'] = $result->IdPlanilla;
        $_SESSION['usrapenom'] = $result->ApellidoNombre;
        header("location:index.php");
    } else {
        echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>';
    }
}
?>
</div>