<!DOCTYPE html>
<?php
/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

if (isset($_POST['anuncio'])){
    $anuncio = $_POST['anuncio'];
    if ($anuncio !== null) {
        $result = Funciones::borrarAnuncioReservado($anuncio,$con);
        error_log("Resultado de la borratina de la reserva: ".$result.",anuncio borrado: ".$anuncio);
        return $result;
    } else {
        return false;
    }
}
?>