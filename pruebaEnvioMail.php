<?php
$to = "mtroncellito@gmail.com";
$from = "info@plusdata.info";
$subject = "Envio letrero publicación";

//$file_path = "http://localhost/HMIPrueba/informes/letreros/"; // Ruta al archivo PDF
$file_name = "Letrero_8_258_20-07-2025.pdf"; // Nombre del archivo adjunto
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Entorno local
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file_name;
} else {
    // Entorno de producción
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/httpdocs/informes/letreros/" . $file_name;
}

// Leer el contenido del archivo PDF
$file_content = file_get_contents($file_path);
$file_content_encoded = base64_encode($file_content);
$file_size = strlen($file_content);

// Generar un boundary único
$boundary = md5(time());

// Cabeceras del correo
$headers = "From: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";

// Cuerpo del correo
$message = "--".$boundary."\r\n";
$message .= "Content-Type: text/plain; charset=UTF-8\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n";
$message .= "\r\n";
$message .= "Adjunto encontrará el archivo PDF con el letrero de la publicación que recientemente ingreso.\r\n"; // Mensaje de texto
$message .= "\r\n";
$message .= "--".$boundary."\r\n";
$message .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n";
$message .= "Content-Disposition: attachment; filename=\"".$file_name."\"\r\n";
$message .= "\r\n";
$message .= $file_content_encoded."\r\n";
$message .= "--".$boundary."--";

// Enviar el correo
if (mail($to, $subject, $message, $headers)) {
    echo "Correo enviado correctamente.";
} else {
    echo "Error al enviar el correo.";
}
?>