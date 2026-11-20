<!doctype html>
<?php
	require_once ("conexion.php");//Contiene funcion que conecta a la base de datos

    $operacion = $_POST['operacion'];
    $nroanuncio = $_POST['nroanuncio'];

    if ($operacion === "SEL"){
        $columna = "cntseleccionado";
    } else {
        $columna = "cntvercontacto";
    }

    $sql = "UPDATE datosanuncios SET ".$columna." = ".$columna." + 1 WHERE nroanuncio=".$nroanuncio;
    $query = mysqli_query($con,$sql);

    return $query;
?>