<?php
session_start();
require_once("cargarDatosFormulario.php");
require_once "conexion.php";

$anunciante=8;
$anuncio=1011;
$_SESSION['clientetipo'] = 'R';
Funciones::cartelAnuncio($anunciante,$anuncio,$con);
unset($_SESSION['clientetipo']);
?>