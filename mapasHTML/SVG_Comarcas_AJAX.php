<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$codPais = $_SESSION['codPais'];
$codRegion = $_SESSION['codRegion'];
$codProvincia = $_SESSION['codProvincia'];

$descComarca=$_SESSION['comarca']=$_REQUEST['comarca'];
$_SESSION['codComarca']=$codComara=Funciones::SVGObtenerCodigo("comarcas",$descComarca,$con);

echo $codComara;

?>