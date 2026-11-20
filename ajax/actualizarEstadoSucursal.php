<!doctype html>
<?php
	session_start();

    require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
    require_once("../cargarDatosFormulario.php");

//    $resultado = Funciones::transferirAnunciante();

    if ($_POST['oper'] === '0'){
        if (!Funciones::transferirAnunciante($_POST['anunciante'])){
            return false;
        }
    }

//    echo "id: ".$_POST['id'];
    $sql = "UPDATE fichas SET estadosucursal = ".$_POST['oper']." WHERE id=".$_POST['id'];
    $query = mysqli_query($con,$sql);
    return;
?>