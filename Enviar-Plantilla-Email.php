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
    $plantilla = "$file_path/Plantilla-Email.html";
    $template = file_get_contents($plantilla);

    $templateAuxiliar=str_replace("\n", "<br>", $_POST['tema']);
    $template = str_replace('{{tema}}', $templateAuxiliar, $template);
    $template = str_replace('{{comentarios}}', nl2br($_POST['comentarios']), $template);
    $template = str_replace('{{texto}}', $_POST['texto'], $template);

    $destinatario = $_POST['destinatario'];

//    echo $template;

    Funciones::enviarMail($destinatario,$template,"Información importante para usted!!!","");
/*
    $destinatario = $_POST['destinatario'];
    $asunto = "Correo desde plantilla";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: empresa@tudominio.com\r\n";

    mail($destinatario, $asunto, $template, $headers);
*/
?>