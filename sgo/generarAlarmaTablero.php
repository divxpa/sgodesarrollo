<?php
// datos para la coneccion a mysql
define('DB_SERVER','localhost');
define('DB_NAME','ssp');
define('DB_USER','root');
define('DB_PASS','root');

define('DIAS_AVISO', 7);
define('DIAS_TOPE', 180);

$con = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME,$con);
mysql_set_charset ('utf8');

//genero las alarmas para todos los usuarios con fecha anterior a 7 dias
$sql = "select a.* from alarma a where a.Activo=1 and a.Alcance in (1, 3) order by a.Nombre";
$query = mysql_query($sql) or die(mysql_error());	

while($row = mysql_fetch_object($query)) {
	$sqlAlarma =  "select v.* from (" . $row->Query . ") v";
	$queryAlarma = mysql_query($sqlAlarma) or die(mysql_error());
	$save = true;
	$i = 0;
	$idAlarmaUsuario = "";
	
	while($rowAlarma = mysql_fetch_object($queryAlarma)) {

		if($save){
			$insertAlarmaUsuario = "insert into alarmausuario(IdAlarma, IdUsuario, FechaHora, Cantidad) values (".$row->IdAlarma.", 1, DATE_SUB(CURDATE(),INTERVAL 8 DAY), 0)";
			$queryAlarmaUsuario = mysql_query($insertAlarmaUsuario) or die(mysql_error());			
			$idAlarmaUsuario = mysql_insert_id();
			$save = false;
		}

		$insertAlarmaUsuarioDetalle = "insert into alarmausuariodetalle(IdAlarmaUsuario, IdObra, IdCertificacion) values($idAlarmaUsuario, ".$rowAlarma->IdObra.",".(!is_null($rowAlarma->IdCertificacion) ? $rowAlarma->IdCertificacion : "null").")";
		$queryAlarmaUsuarioDetalle = mysql_query($insertAlarmaUsuarioDetalle) or die(mysql_error());
		$i++;
	}
	
	if($idAlarmaUsuario!=""){
		$updateAlarmaUsuario = "update alarmausuario set Cantidad=$i where IdAlarmaUsuario=$idAlarmaUsuario";
		$queryAlarmaUsuario = mysql_query($updateAlarmaUsuario) or die(mysql_error());
	}

}

//limpio la tabla alarmatablero
mysql_query("delete from alarmatablero") or die(mysql_error());
//elimino alarmas que tengan mas de 90 dias
mysql_query("delete from alarmausuariodetalle where exists(select * from alarmausuario where IdAlarmaUsuario=alarmausuariodetalle.IdAlarmaUsuario and datediff(current_date, FechaHora) > ". DIAS_TOPE.")") or die(mysql_error());
mysql_query("delete from alarmausuario where datediff(current_date, FechaHora) > " . DIAS_TOPE) or die(mysql_error());

$sqlAlarmas = "select * from alarma a where a.Activo=1 and a.Alcance in (2, 3) order by a.Nombre";
$queryAlarmas = mysql_query($sqlAlarmas) or die(mysql_error());

while($rowAlarmas = mysql_fetch_object($queryAlarmas)) {
        $idAlarma = $rowAlarmas->IdAlarma;
        $sqlAlarma = $rowAlarmas->Query;
        $sqlAlarma = "insert into alarmatablero(IdAlarma, IdObra, IdCertificacion, FechaHora) select $idAlarma, v.IdObra, v.IdCertificacion, current_timestamp from (" . $sqlAlarma . ") v where exists(select * from alarmausuariodetalle aud inner join alarmausuario au on aud.IdAlarmaUsuario=au.IdAlarmaUsuario where au.IdAlarma=$idAlarma and aud.IdObra=v.IdObra and (aud.IdCertificacion=v.IdCertificacion or (aud.IdCertificacion is null and v.IdCertificacion is null)) and datediff(current_date, au.FechaHora) > ". DIAS_AVISO . ")";
        $queryAlarma = mysql_query($sqlAlarma);
}

?>
