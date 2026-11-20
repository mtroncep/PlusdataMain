<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$codPais = $_SESSION['codPais'];
$codRegion = $_SESSION['codRegion'];
$codProvincia = $_SESSION['codProvincia'];

$descMunicipio = $_SESSION['municipio']=$_REQUEST['municipio'];
$_SESSION['codMunicipio']=$codMunicipio=Funciones::SVGObtenerCodigo("municipiosxcomarca",$descMunicipio,$con);

echo $codMunicipio;

?>