<!doctype html>
<?php
	session_start();

    require_once ("conexion.php");//Contiene funcion que conecta a la base de datos
    require_once("cargarDatosFormulario.php");

    if (!isset($_POST['estado']) || !isset($_POST['idAnunciante'])){
        return false;
    }
    
    $sql = "UPDATE anunciantes SET estado = ".$_POST['estado']." WHERE idanunciante=".$_POST['idAnunciante'];
    $query = mysqli_query($con,$sql);
?>