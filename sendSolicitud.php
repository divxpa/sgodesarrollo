<?php
//print_r($_POST);
$message = "Nombre y Apellido: " . $_POST['nombres'].", ".$_POST['apellidos'] .
        "<br /> Dependencia: ".stripcslashes($_POST['dependencia']).
        "<br />E-mail: " . stripcslashes($_POST['email']) .
        "<br />Teléfono: " . stripcslashes($_POST['telefono']) .
        "<br />Mensaje:" . stripcslashes($_POST['descripcion']);
include_once("phpmailer/PHPMailerAutoload.php");
$mail = new PHPMailer();
$mail->CharSet = "utf-8";
$mail->ContentType = "text/html";
//$mail->Username = "no-responder@neuquen.com.ar";
//$mail->Password = "no*123reply";
$mail->From=$_POST['email'];
$mail->FromName = "Solicitud";
$mail->Subject = "Solicitud de Acceso";
$mail->Body = $message;
$mail->AddReplyTo($_POST['email'], $_POST['nombres'].", ".$_POST['apellidos'] );
$mail->AddAddress('erikkaweb@gmail.com');
//if(mail('erikkaweb@gmail.com', 'Mi título', $message))
if ($mail->Send())
{
    echo 'Su solicitud a sido enviada';
}else{
    echo 'Su solicitud no pudo ser procesa por favor intente nuevamente <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                Solicitar acceso
              </button>';
}