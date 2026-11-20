<?php
include ("conexion.php");

if (isset($_GET['user_id'])){
    $userId = $_GET['user_id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $fecha = date('Y-m-d H:i:s');
} else {
// Retornar imagen transparente
    header("Content-Type: image/gif");
    readfile("imagenes/tilde.png");
    return;
}
// Guardar evento en la base de datos

$update = "UPDATE inmobiliarias set fchlectura = '".$fecha."' , ipreceptor = '".$ip."', cntmailleidos = cntmailleidos + 1  WHERE id = ".$userId." and fchlectura = null";
$updatetrk = mysqli_query($con, $update);

// Retornar imagen transparente
header("Content-Type: image/gif");
readfile("imagenes/tilde.png");
?>