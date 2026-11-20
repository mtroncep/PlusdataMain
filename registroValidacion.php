<?php
session_start();

// NO borrar captcha_extra aquí
unset($_SESSION['tipocliReg']);
unset($_SESSION['emailReg']);
unset($_SESSION['pwdReg']);
unset($_SESSION['nombreReg']);
unset($_SESSION['telReg']);
unset($_SESSION['menuinicio']);
unset($_SESSION['nroInmob']);
unset($_SESSION['infonoticias']);

$_SESSION['captcha_extra'] = false;

require_once("conexion.php");
require_once("cargarDatosFormulario.php");

if (!isset($_POST['terminos'])) {
    $errores[] = "Debe aceptar los términos y condiciones para registrarse.";
}
// -------------------------------------------------------------
// PROCESAR REGISTRO NORMAL
// -------------------------------------------------------------
$clientetipo  = $_SESSION['tipocliReg']   = $_POST['tipocliReg'];
$emailReg     = $_SESSION['emailReg']     = $_POST['emailReg'];
$pwdReg       = $_SESSION['pwdReg']       = $_POST['pwdReg'];
$nombreReg    = $_SESSION['nombreReg']    = $_POST['nombreReg'];
$telReg       = $_SESSION['telReg']       = $_POST['telReg'];
$infonoticias = $_SESSION['infonoticias'] = $_POST['infonoticias'];

$_SESSION['menuinicio'] = 'indexNew.php';

if ($clientetipo != "O") {
    $nroInmob = 0;
} else {
    $nroInmob = $_SESSION['nroInmob'] = $_POST['nroInmob'];
}


// -------------------------------------------------------------
// 5) ENVÍO DE MAIL Y SMS
// -------------------------------------------------------------
$activada = "NO";

if (Funciones::obtenerValorParametro($con, 'VERIFICAR_EMAIL')) {

    $_SESSION['verif_mail'] = 1;

    $envio_SMS = Funciones::obtenerValorParametro($con, 'ENVIO_SMS');
    $_SESSION['verif_sms'] = $envio_SMS;

    $estadoSMS = 0;

    if ($envio_SMS) {
        $estadoSMS = Funciones::OTPEnvioCodSMS($telReg, 0);
    }

    $_SESSION['estadoSMS'] = $estadoSMS;

    include "envioMailRegistro.php";

    if ($_SESSION['verif_mail'] == 1 && $_SESSION['verif_sms'] == 1) {
        echo "<script>alert('Hemos enviado un E-Mail y SMS de verificación. Por favor, revise su bandeja de entrada (incluida la carpeta de no deseados/spam) y su teléfono móvil para obtener los códigos correspondientes.');</script>";
    } else {
        echo "<script>alert('Hemos enviado un E-Mail de verificación. Por favor, revise su bandeja de entrada (incluida la carpeta de no deseados/spam), para obtener el código correspondiente.');</script>";
    }

    echo "<script>window.location.href='indexNew.php';</script>";
    exit;
}


// -------------------------------------------------------------
// 6) SI NO SE REQUIERE VERIFICACIÓN → REDIRIGIR
// -------------------------------------------------------------
echo "<script>window.location.href='indexNew.php';</script>";
exit;

?>
