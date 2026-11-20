<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$codPais = $_SESSION['codPais'];
$codRegion = $_SESSION['codRegion'];
$descProvincia = $_SESSION['provincia']=$_REQUEST['provincia'];
$codProvincia=Funciones::SVGObtenerCodigo("provincias",$descProvincia,$con);

echo $codProvincia;

?>