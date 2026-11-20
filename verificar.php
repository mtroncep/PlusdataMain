<?php
	session_start();

    require_once "conexion.php";
    require_once("cargarDatosFormulario.php");

    $email   = $_POST['email'];
    $codigo  = $_POST['codigo'];

    if (isset($_POST['codigom']) && isset($_POST['movil'])){
        $codigom = $_POST['codigom'];
        $telReg  = $_POST['movil'];
    } else {
        $codigom = "";
        $telReg  = 0;
    }
/*
    error_log("&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&",0);
    error_log("E-Mail: ".$email,0);
    error_log("Código E-Mail: ".$codigo,0);
    error_log("Código E-Mail Session: '".$_SESSION['codigoVerifMail']."'",0);
    error_log("telReg: ".$telReg,0);
    error_log("Código telReg: ".$codigom,0);
    error_log("&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&",0);
*/
    $validacionEmail=0;
    $validacionMovil=0;
//    if( $_SESSION['codigoVerifMail'] === $codigo ){
    if( intval($_SESSION['codigoVerifMail']) === intval($codigo) ){
        $validacionEmail = 1;

        // Validar código SMS solo si vino codigom
        if ($codigom !== ""){
            $resultadoSMS = funciones::OTPControlCodSMS($telReg, $codigom);

//            error_log("????????????????????????? resultadoSMS: ".$resultadoSMS,0);

            if ($resultadoSMS == 0){
                $validacionMovil = 1;
            } else {
                echo "codigo invalido";
            }
        } else {
            // No se envió SMS, entonces lo consideramos válido
            $validacionMovil = 0;
        }

    } else {
        echo "codigo invalido";
    }
/*
    error_log("+++++++++++++++++++++++++++++++",0);
    error_log("codigo: ".$codigo,0);
    error_log("validacionMail: ".$validacionEmail,0);
    error_log("verif_mail: ".$_SESSION['verif_mail'],0);
    error_log("validacionMovil: ".$validacionMovil,0);
    error_log("verif_sms: ".$_SESSION['verif_sms'],0);
    error_log("+++++++++++++++++++++++++++++++",0);
*/
    if (($validacionEmail && $_SESSION['verif_mail'] && $validacionMovil && $_SESSION['verif_sms']) || 
        ($validacionEmail && $_SESSION['verif_mail'] && !$validacionMovil && !$_SESSION['verif_sms'])) {
        echo ('<script type="text/javascript">'.'window.location.href = "registro.php"</script>');
//        echo '<h1>TODO CORRECTO!!! ya puedes <a href="indexNew.php">Iniciar sesion</a></h1>';
    } else {
        echo 'Se produjo un error en la verificación. ¡Intentelo de nuevo! <a href="indexNew.php">Inicio</a>';
    }
?>