<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

// Varios destinatarios
$emailReg="mtroncellito@gmail.com";
$para  = $emailReg; // atención a la coma
//echo $para;

if (gethostname() == "DESKTOP-6LCGB06"){
    // Entorno local
    $file_path = "localhost/HMIPrueba";
} else {
    // Entorno de producciÃ³n
    $file_path = "https://plusdata.info";
}

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
        <p style='font-size: 18px; color:#003781'><a href='$file_path/confirm2FA.php?email=$para'>Activa tu cuenta</a></p>
    </div>
</body>
</html>
";

$subject = "Segundo Factor de Acceso";
Funciones::enviarMail($para,$cuerpoMail,$subject,"");
?>