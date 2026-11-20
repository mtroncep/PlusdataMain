<!doctype html>
<?php
session_start();

require_once ("../conexion.php");

if (!isset($_SESSION['anuncio'])){
    error_log("El numero de anuncio no esta definido. Se estan buscando publicaciones.");
    return true;
}

$data = json_decode(file_get_contents('php://input'), true);
$idFor = $data['idFor'];
$comentario = $data['comentario'];
error_log("Nro.Anuncio         : ".$_SESSION['anuncio']);
error_log("Id checkbox         : ".$idFor);
error_log("Comentario a guardar: ".$comentario);
// Acá tu lógica real: conexión, prepared statement, etc.
$sql = "insert into mensajesanunciantetmp (nroanuncio, idposicion, mensaje) values (".$_SESSION['anuncio'].", '".$idFor."', '".$comentario."')";
$query = mysqli_query($con,$sql);
if (!$query){
    $upd="update mensajesanunciantetmp set mensaje = '".$comentario."' where nroanuncio = ".$_SESSION['anuncio'].
        " and idposicion = '".$idFor."'";
    $query = mysqli_query($con,$upd);
}
?>
