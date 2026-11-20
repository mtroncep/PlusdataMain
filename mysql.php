<?php

	if (gethostname() == "DESKTOP-6LCGB06"){
		$DB_HOST = 'localhost';
		$DB_USER = 'qafk337';
		$DB_PASS = 'Miguel2206';
		$DB_NAME = 'qafk337';
	} else {
		$DB_HOST = 'localhost';
		$DB_USER = 'plusdataAdmDB';
		$DB_PASS = '6Xe#4p4o7';
		$DB_NAME = 'plusdataDB';
	}

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$acentos = $mysqli->query("SET NAMES 'utf8'");
?>
