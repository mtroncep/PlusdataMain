<?php

include "mysql.php";

if(isset($_GET["opt"]) && $_GET["opt"]=="country"){
    $maxCodNum = $mysqli->query("select max(COD_NUM) as maximo from paises");
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$mysqli->query("insert into paises(COD_NUM, DESCRIPCION) value (\"$maxCodNumStr[maximo]\", \"$_POST[name]\")");
		header("Location: Nuevo.php?paisadd=1&paisFijar=".$maxCodNumStr[maximo]);
	}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="region"){
		$maxCodRegion = $mysqli->query("select max(COD_REGION) as maximo from regiones");
		if ($maxCodRegion->num_rows == 1) {
			$maxCodRegionStr = $maxCodRegion->fetch_assoc();
			++$maxCodRegionStr['maximo'];
			$mysqli->query("insert into regiones(COD_PAIS, COD_REGION, DESCRIPCION) value (\"$_POST[pais_id]\",\"$maxCodRegionStr[maximo]\",\"$_POST[name]\")");
			header("Location: Nuevo.php?regionadd=1&paisFijar=".$_POST[pais_id]);
		}
	}
else if(isset($_GET["opt"]) && $_GET["opt"]=="provincia"){
		$maxCodProvincia = $mysqli->query("select max(COD_PROVINCIA) as maximo from provincias");
		if ($maxCodProvincia->num_rows == 1) {
			$maxCodProvinciaStr = $maxCodProvincia->fetch_assoc();
			++$maxCodProvinciaStr['maximo'];
			$mysqli->query("insert into provincias(COD_REGION, COD_PROVINCIA, DESCRIPCION) value (\"$_POST[regionProv_id]\",\"$maxCodProvinciaStr[maximo]\",\"$_POST[name]\")");
			header("Location: Nuevo.php?provinciaadd=1");
		}else {
			$mysqli->query("insert into provincias(COD_REGION, COD_PROVINCIA, DESCRIPCION) value (\"$_POST[region_id]\",1,\"$_POST[name]\")");
			header("Location: Nuevo.php?provinciaadd=1");
		}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="all"){
	
	$mysqli->query("insert into combo(continente_id,pais_id,ciudad_id) value ($_POST[continente_id],$_POST[pais_id],$_POST[ciudad_id])");
	setcookie("comboadd",1);
	header("Location: index.php");
}
?>