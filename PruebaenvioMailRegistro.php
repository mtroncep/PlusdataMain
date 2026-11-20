<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

// Varios destinatarios
$emailReg="mtroncellito@gmail.com";
$para  = $emailReg; // atención a la coma
//echo $para;

//código aleatorio

$codigo = rand(100000,999999);

// mensaje
$cuerpoMail = "
<html>
<head> 
    <title>Plusata.info Mail para registro</title>
</head>
<body>
    <div style='margin: 0; font-family: arial,sans-serif,serif; font-size: 20px; color:#003781'>
        <p><strong>Gracias por registrarte en BUSCADOR PLUSDATA</strong></p>
        <p>Tu código de verificación es: <strong>$codigo</strong></p>
        <p style='font-size: 18px; color:#003781'><a href='https://plusdata.info/confirm.php?email='$para'>Activa tu cuenta</a></p>
    </div>
</body>
</html>
";

$subject = "Activacion de cuenta en BUSCADOR PLUSDATA";
Funciones::enviarMail($para,$cuerpoMail,$subject,"");
?>