<?php
require_once "conexion.php";
require_once("cargarDatosFormulario.php");

$para  = "mtroncellito@gmail.com"; // atención a la coma
$file_name = "Letrero_8_258_20-07-2025.pdf";
$estado= Funciones::enviarMailPlantillaSMTP($para,$file_name);
if ($estado){
    echo "E-Mail enviado!!!";
}
?>