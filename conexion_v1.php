<?php
    // DB credentials.
	if (gethostname() == "DESKTOP-6LCGB06"){
		define('DB_HOST','localhost');
		define('DB_USER','qafk337_v1');
		define('DB_PASS','Miguel2206');
		define('DB_NAME','qafk337');
	} else {
		define('DB_HOST','localhost');
		define('DB_USER','plusdataAdmDB');
		define('DB_PASS','6Xe#4p4o7');
		define('DB_NAME','plusdataDB');
	}
//	require 'C:\Users\marce\Documents\Data\credenciales.php';
	# conectare la base de datos
//    $con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	mysqli_set_charset($con, 'utf8');
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
?>