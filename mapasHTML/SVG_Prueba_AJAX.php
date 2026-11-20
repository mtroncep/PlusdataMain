<?php
session_start();

require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");

$codPais = $_SESSION['codPais'];

$descRegion=$_SESSION['region']=$_REQUEST['region'];
$codRegion=Funciones::SVGObtenerCodigo("regiones",$descRegion,$con);

//echo "<p>".$codPais."-".$codRegion."</p>";
echo $codPais.'-'.$codRegion;

?>