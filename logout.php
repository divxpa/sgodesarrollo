<?php
include_once "global/SessionSGO.php";
include_once "classes/RegisterLog.php";

// Guarda registro del login en la Base
RegisterLog::logout(SessionSGO::get('usr_id'), SessionSGO::getId());

SessionSGO::end();
header("location:index.php"); 

?>