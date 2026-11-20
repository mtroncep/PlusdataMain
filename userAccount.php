<?php
session_start();
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$_SESSION['mailOlvidoClave'] = $_POST['email'];
$_SESSION['tipoCambioClave'] = "olvido";

$email=$_POST['email'];

$estadoEnvio=Funciones::ejecutar2FA($email,"CP",$con);

echo "<script>window.location.href='confirm2FA.php?email=$email&vuelta=CP'</script>";

//echo ('<script type="text/javascript">'."window.location.href = 'confirm2FA.php?email=$email'</script>");
//return true;
?>
