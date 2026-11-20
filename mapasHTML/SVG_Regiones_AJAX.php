<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$codPais = $_SESSION['codPais'];
$descRegion=$_SESSION['region']=$_REQUEST['region'];
$codRegion=Funciones::SVGObtenerCodigo("regiones",$descRegion,$con);
echo $codRegion;

?>