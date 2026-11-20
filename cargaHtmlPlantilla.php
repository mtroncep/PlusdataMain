<?php
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$rutaHTMLTemplate = 'C:/xampp/htdocs/HMIPrueba/actualizaCP.php'; // Asegúrate que la ruta sea correcta
$archivoHTMLTemplate=file_get_contents($rutaHTMLTemplate);
$archivoHTMLTemplate=mysqli_real_escape_string($con, $archivoHTMLTemplate);


$insert = "insert into email_templates (content) values ('$archivoHTMLTemplate')";
$aux=Funciones::actualizarAnuncioBD($insert, "N", $con);

?>