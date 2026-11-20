<?php
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");
$fechaIni = $_REQUEST['fecha'];
$fechaDat = date_create($fechaIni);
date_add($fechaDat, date_interval_create_from_date_string('2 month'));
$update="update busquedasguardadas set fechavigencia='".$fechaDat->format('Y-m-d')."' where nrobusqueda=".$_REQUEST['nrobusqueda'];
$aux=Funciones::actualizarAnuncioBD($update, "N", $con);
if ($aux){
    echo $fechaDat->format('Y-m-d');
} else {
    echo "ERR-UPD";
}
?>