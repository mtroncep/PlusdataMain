<?php
session_start();

// Varios destinatarios
//$para  = $_SESSION['emailReg']; // atención a la coma
$para  = "mtroncellito@gmail.com"; // atención a la coma
//$para .= 'wez@example.com';

// título
$titulo = 'REGISTRO EN PLUSDAT.INFO';

//código aleatorio

// mensaje
$mensaje = "Gracias por registrate";
// Para enviar un correo HTML, debe establecerse la cabecera Content-type

$cabeceras  = 'MIME-Version: 1.0'."\r\n";
$cabeceras .= 'Content-type: text/html; charset="utf-8"' . "\r\n";
$cabeceras .= 'From:'.'admin@plusdata.info'.'\r\n';
// Enviarlo
//mail($para, $titulo, $mensaje, $cabeceras);
$enviado=false;
if(mail($para, $titulo, $mensaje, $cabeceras)){
    echo "<br>"."El mail salio con fritas!!!!";
   $enviado=true;
} else {
    echo "¡¡¡ERROR!!!";
}
?>