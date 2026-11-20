<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

//Create a new PHPMailer instance
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mailPara='mtroncellito@gmail.com';

$codigo = rand(100000,999999);
//$emailReg='aquimasdatosl@gmail.com';
$emailReg='mtroncellito@gmail.com';

$mensaje = '
<html>
<head> 
    <title>Mail para registro</title>
</head>
<body>
  <h2>VALIDACION DE CORREO ELECTRONICO DE REGISTRO Y NRO. MOVIL</h2>
  <h2>'.'Tu código de validación de Correo es: '.$codigo.'</h2>
  <p><h2>Hasta que no valides tu correo y nro. de movil recibido por SMS (si no lo hiciste, por favor consultalo), no termina de ser efectivo tu ingreso al buscador.</h2></p>
  <p> 
    <a href="confirm.php?email='.$emailReg.'"><h2>Validar Correo y Móvil</h2></a> 
  </p>
</body>
</html>
';

$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use);
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
//$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
//Set the hostname of the mail server
$mail->Host = 'plusdata.info';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = 'admin@plusdata.info';
//Password to use for SMTP authentication
$mail->Password = 'Ni3vXeGp#7yss^z2';
//Set who the message is to be sent from
$mail->setFrom('info@plusdata.info', 'Plusdata');
//$mail->setFrom('aquimasdatosl@gmail.com', 'Magic Mailer');
//$mail->setFrom('info@plusdata.info', 'Plusdata.info');
//Set an alternative reply-to address
//$mail->addReplyTo('mtroncellitoml@gmail.com', 'Magic');
//Set who the message is to be sent to
$mail->addAddress("$emailReg");
//Set the subject line
$mail->Subject = 'Prueba de PHPMailer SMTP de plusdata.net';
$mail->isHTML(true);
$mail->Body = "$mensaje"; // Mensaje a enviar
//$mail->msgHTML = "$mensaje"; // Mensaje a enviar
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}