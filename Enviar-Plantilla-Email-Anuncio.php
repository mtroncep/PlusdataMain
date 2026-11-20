<!doctype html>
<?php
    require_once("cargarDatosFormulario.php");

    if (gethostname() == "DESKTOP-6LCGB06"){
        // Entorno local
        $file_path = "C:/xampp/htdocs/HMIPrueba";
    } else {
        // Entorno de producciÃ³n
        $file_path = "https://plusdata.info";
    }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Remitente y destinatario
    $mail->setFrom('info@plusdata.info', 'Promociones');
    $mail->addAddress('mtroncellito@gmail.com');

    // Adjuntar imagen con CID
    $plantilla = 'anuncio.png';
    $imagen=$file_path.'/imagenes/'.$plantilla;
    $mail->AddEmbeddedImage($imagen, 'anuncioimg');

    // Contenido HTML
    $mail->isHTML(true);
    $mail->Subject = '¡Oferta exclusiva!';
    $mail->Body = '
    <html>
    <body>
      <h2>¡No te pierdas esto!</h2>
      <img src="cid:anuncioimg" border="1" alt="Anuncio" style="width:600px; display:block;">
    </body>
    </html>';
    // Enviar
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->send();
    echo 'Mensaje enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar: {$mail->ErrorInfo}";
}

//    echo $template;

//    Funciones::enviarMail($destinatario,$template,"Información importante para usted!!!","");
/*
    $destinatario = $_POST['destinatario'];
    $asunto = "Correo desde plantilla";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: empresa@tudominio.com\r\n";

    mail($destinatario, $asunto, $template, $headers);
*/
?>