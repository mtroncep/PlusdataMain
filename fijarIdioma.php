<?php
session_start();
require_once("conexion.php");

$data = array();
$idioma=$_REQUEST['idiomapermanente'];
$ficha=$_SESSION['idFicha'];
$update="update fichas set idiomapermanente = '$idioma' WHERE idFicha = '$ficha'";
error_log("UPDATE: ".$update,0);
$result=mysqli_query($con,$update);
if($result){
    $data['status'] = '1';
} else {
    $data['status'] = '0';
}
echo json_encode($data);
return;
?>
