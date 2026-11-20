<?php
session_start();
header('Content-Type: application/json');

$respuestaUsuario = mb_strtolower(trim($_POST['captcha_respuesta']));
$respuestaCorrecta = mb_strtolower($_SESSION['captcha_respuesta']);

if ($respuestaUsuario === '' || $respuestaUsuario !== $respuestaCorrecta) {
    echo json_encode(['success' => false, 'error' => 'Respuesta incorrecta']);
    exit;
}

unset($_SESSION['captcha_respuesta']);

// Recuperamos lo que habíamos guardado ANTES de mostrar el captcha
$pendiente = $_SESSION['captcha_pendiente'];
unset($_SESSION['captcha_pendiente']);

if (!$pendiente) {
    echo json_encode(['success' => false, 'error' => 'Sesión expirada, recargá la página']);
    exit;
}

// ==== ACÁ CONTINÚA LO QUE TENÍAS DESPUÉS EN TU SCRIPT ====
// "Enviamos el mail de confirmación de mail y si esta activo verificamos nro movil"
/*
$activada = "NO";
if ($pendiente['verificar_email']) {
    // ... tu lógica de envío de mail con $pendiente['email']
    if ($pendiente['envio_sms']) {
        // ... tu lógica de envío de SMS
    }
}
*/
echo json_encode(['success' => true, 'redirect' => '']); 
// o lo que corresponda hacer una vez completado el proceso