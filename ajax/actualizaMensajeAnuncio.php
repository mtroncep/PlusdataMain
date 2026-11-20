<!doctype html>
<?php
session_start();
require_once ("conexion.php");

$data = json_decode(file_get_contents('php://input'), true);
$idFor = $data['idFor'];
$comentario = $data['comentario'];
$update = "UPDATE detalles SET mensajeayuda = ".$comentario." WHERE id = ".$idFor;
$resulUpdate = mysqli_query($con, $update);
return $resulUpdate;
?>
