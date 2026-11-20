<?php
include ("conexion.php");
//$para = $_SESSION['emailReg']; // atención a la coma
$para = 'mtroncellito@gmail.com';

// título
$titulo = 'TE QUEREMOS CONTAR DEL NUEVO BUSCADOR INMOBILIARIO PLUSDATA';

$userId = 42103; // Supongamos que lo sacás desde tu base de datos
$pixelUrl = "https://plusdata.info/tracker.php?user_id={$userId}";
$mensaje = "
    <html>
    <body>
        <p>Hola 👋, te enviamos este correo para... bla bla</p>
        <img src='{$pixelUrl}' width='1' height='1' style='display:none;'>
    </body>
    </html>
";

$cabeceras  = "From: admin@plusdata.info". "\r\n";
$cabeceras .= 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$cabeceras .= 'X-Mailer: PHP/' . phpversion();

$enviado=0;

if(mail($para, $titulo, $mensaje, $cabeceras)){
    $enviado=1;
    $fecha = date('Y-m-d H:i:s');
    $update = "UPDATE inmobiliarias set fchenvio = '".$fecha."', fchlectura = null, ipreceptor = null WHERE id = ".$userId;
    $updateEnv = mysqli_query($con, $update);
   echo "Enviado!!!!";
} else {
    echo "¡¡¡ERROR!!!";
}
?>