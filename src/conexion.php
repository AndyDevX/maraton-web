<?php   
	$hostname = getenv('DB_HOST') ?: 'localhost';
	$username = getenv('DB_USER') ?: "root";
	$password = getenv('DB_PASS') ?: "";
	$dbname   = getenv('DB_NAME') ?: "maraton";

	$conectar=mysqli_connect($hostname,$username, $password, $dbname) or die ("html>script language='JavaScript'>alert('¡No es posible conectarse a la base de datos! Vuelve a intentarlo más tarde.'),history.go(-1)/script>/html>");