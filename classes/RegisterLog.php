<?php

class RegisterLog {
	
	const TABLE = "ingreso";

	public function login($idUsuario, $idSesion)
	{
		$table = self::TABLE;

        $usuarioLogin = [
            'IdUsuario' => $idUsuario,
            'Ip' => getHostByName(getHostName()),
            'SessionId' => $idSesion,
            'FechaHoraLogin' => date('Y-m-d H:i:s'), //>> Hacer helper para fechaSQL
        ];

        //>> esta implementación va a servir para un insert genérico en MySqlQuery
		$keys = implode(', ', array_keys($usuarioLogin));
		$values = implode(', ', array_map(function($elem){
			return "'".$elem."'";
		}, $usuarioLogin));

		$sql = "INSERT INTO $table ($keys) VALUES ($values)";

	    $result = mysql_query($sql);

	    return $result;
	}

	public function logout($idUsuario, $idSesion)
	{
		$table = self::TABLE;

		$fechaHoraLogout = date('Y-m-d H:i:s');
		$fechaHoraClose = '1900-01-01 00:00:00';

		// Logout correctamente realizado
		$sql = "UPDATE 
				$table SET FechaHoraLogout = '$fechaHoraLogout' 
			WHERE 
				IdUsuario = '$idUsuario' 
				AND FechaHoraLogout IS NULL 
				AND SessionId = '$idSesion'
			ORDER BY FechaHoraLogin DESC LIMIT 1";

		$result = mysql_query($sql);

		// Posibles Logouts por cerrar navegador
		$sql = "UPDATE 
				$table SET FechaHoraLogout = '$fechaHoraClose' 
			WHERE 
				IdUsuario = '$idUsuario' 
				AND FechaHoraLogout IS NULL";

		$result = $result && mysql_query($sql);

		//>>!! Si se loguea con 2 sesiones diferentes en una misma vez (ej: chrome común e incognito) al 
		    // hacer logout en uno, se registra el logout el otro también (corregir de alguna manera!!!)

	    return $result;
	}

}