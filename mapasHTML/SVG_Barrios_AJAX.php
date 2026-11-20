<?php
session_start();

ob_start();
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
ob_end_clean();

$descBarrio=$_SESSION['barrio']=$_REQUEST['barrio'];
$codBarrio=Funciones::SVGObtenerCodigo("barrios",$descBarrio,$con);
echo $codBarrio;

?>