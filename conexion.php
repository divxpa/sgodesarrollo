<?php 
// datos para la conexión a mysql 
include_once(__DIR__."/global/db.php");
$con = mysql_connect(DB_SERVER, DB_USER, DB_PASS); 
mysql_select_db(DB_NAME, $con); 

mysql_query("SET 
character_set_results = 'utf8', 
character_set_client = 'utf8', 
character_set_connection = 'utf8', 
character_set_database = 'utf8', 
character_set_server = 'utf8'
", $con);
?>
