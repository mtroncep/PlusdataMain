<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$codPais = $_SESSION['codPais'];
$codRegion = $_SESSION['codRegion'];
$codProvincia = $_SESSION['codProvincia'];
//$descComarca = $_SESSION['comarca']=$_REQUEST['comarca'];
//$descMunicipio = $_SESSION['municipio']=$_REQUEST['municipio'];
$descLocalidad = $_SESSION['localidad']=$_REQUEST['localidad'];

$codLocalidad=Funciones::SVGObtenerCodigo("localidades",$descLocalidad,$con);
echo $codLocalidad;

?>