<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ajustá la ruta según tu proyecto

// Validación básica
if (!isset($_POST['nombre'], $_POST['email'], $_POST['mensaje'])) {
    echo "Faltan datos obligatorios";
    exit;
}

$nombre  = htmlspecialchars(trim($_POST['nombre']));
$email   = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$mensaje = htmlspecialchars(trim($_POST['mensaje']));

if (!$email) {
    echo "El correo electrónico no es válido";
    exit;
}

$mail = new PHPMailer(true);

try {
    if (gethostname() === "DESKTOP-6LCGB06"){
        // Entorno local
        error_log("EMISOR: mtroncellito",0);
        $mail->setFrom('no-replay@plusdata.info', 'Formulario Web');
        $mail->addReplyTo($email, $nombre);
    } else {
        // Entorno de producciÃ³n
        error_log("EMISOR: info",0);
//        $mail->setFrom('no-replay@plusdata.info', 'Formulario Web');
        $mail->setFrom('plusdata@plusdata.info', 'Formulario Web');
        $mail->addReplyTo($email, $nombre);
    }

    // Codificación UTF‑8
    $mail->CharSet = 'UTF-8';

    // Remitente
//    $mail->setFrom($email, $nombre);

    // Destinatario
    $mail->addAddress('plusdata@plusdata.info', 'Administración');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde el sitio web';
    $mail->Body    = "
        <h3>Consulta desde el formulario</h3>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mensaje:</strong><br>$mensaje</p>
    ";

    // Alternativa en texto plano
    $mail->AltBody = "Nombre: $nombre\nEmail: $email\nMensaje:\n$mensaje";

    $mail->send();
    echo "ok_envio_email_contacto";
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>
