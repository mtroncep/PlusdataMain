<!doctype html>
<?php

require_once ("../conexion.php");


$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$techo = $data['techo'];
$detalleNuevo = $data['detalle'];
$detalleAnterior= $data['detalleant'];
$movimientoUPD = "update detalles set mensajeayuda = '".$techo."' , detalle='".$detalleNuevo."' where id = "."$id";
//error_log("UDATE detalles: ".$movimiento);
$resulMovimientoUPD = mysqli_query($con, $movimientoUPD);
if(!$resulMovimientoUPD){
//    echo $resulMovimientoUPD;
    echo "0";
}

if ($detalleAnterior <> $detalleNuevo){
    $movimientoCALL = "CALL ControlarDetallesCambiados('".$detalleAnterior."','".$detalleNuevo."')";
//    error_log("CALL detalles: ".$movimientoCALL);
    $resulMovimientoCALL = mysqli_query($con, $movimientoCALL);
    if(!$resulMovimientoCALL){
    //    echo $resulMovimientoCALL;
        echo "0";
    } else {
        echo "1";
    }
} else {
    echo "1";
}
?>
