<?php
require_once("cargarDatosFormulario.php");
    // Varios destinatarios
$para = $_SESSION['emailReg']; // atención a la coma
$telReg = $_SESSION['telReg'];

if (gethostname() == "DESKTOP-6LCGB06"){
    // Entorno local
    $file_path = "localhost/HMIPrueba" . $file_name;
} else {
    // Entorno de producciÃ³n
    //$file_path = "https://plusdata.info";
    $file_path = $_SERVER['REQUEST_URI'];
    if ($file_path === ''){
        $file_path='https://plusdata.info';
    } else {
        $file_path='https://plusdata.info/testing';        
    }
}

//código aleatorio

$codigo = rand(100000,999999);

$_SESSION['codigoVerifMail'] = $codigo;

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
        <p style='font-size: 18px; color:#003781'><a href='$file_path/confirm.php?email=$para&telReg=$telReg'>Activa tu cuenta</a></p>
    </div>
</body>
</html>
";

$subject = "Activacion de cuenta en BUSCADOR PLUSDATA";
$enviado = Funciones::enviarMail($para,$cuerpoMail,$subject,"");

//error_log("REGISTRO: ".$para.", Cuerpo mail: ".$cuerpoMail.", Enviado: ".$enviado,0);

?>