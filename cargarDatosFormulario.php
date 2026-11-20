<?php
//ob_start();

require_once("conexion.php");

$auth_basic = base64_encode("sms@plusdata.info:WgKKcEGdwDwVq91L6cZjvJdAyGh86hpR");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';

require 'vendor/autoload.php'; // o el path manual si no usÃ¡s Composer
require_once "fpdf/fpdf.php";

class PDF extends FPDF
{
     function CentrarTexto($text, $font, $tamanioFuente, $posY)
        {
            $this->SetY($posY);
            $this->SetX(0);
            $this->SetFont($font,'B',$tamanioFuente);
            $pageWidth = $this->GetPageWidth();
            $textWidth = $this->GetStringWidth($text);
//            error_log('Ancho texto: '.$textWidth.', Ancho pagina: '.$pageWidth,0);
            if ($textWidth > $pageWidth){
                $this->SetY($posY);
                $nuevoTamano = ($tamanioFuente * ($pageWidth / $textWidth));
                $this->SetFont($font, 'B', $nuevoTamano);
                $textWidth = $this->GetStringWidth($text);
//                error_log('Ancho texto: '.$textWidth.', Ancho pagina: '.$pageWidth.', Nuevo font: '.$nuevoTamano,0);
                $x = ($pageWidth - $textWidth) / 2;
                $this->SetX($x);
                $this->Cell($textWidth,0,$text,0,1);
            } else {
                $x = ($pageWidth - $textWidth) / 2;
                $this->SetX($x);
                $this->Cell($textWidth,0,$text,0,1);
            }

        }
}
//ob_end_clean();

class Funciones
{
    function __construct()
    {
    }

public static function obtenerNumerador($numerador, $conexion){
    // Bloquea la fila
    $sql = "SELECT ultimo_valor FROM numeradores WHERE tipo ='".$numerador."' FOR UPDATE;";
    error_log("Select último valor: ".$sql,0);
    $res = mysqli_query($conexion, $sql);
    $fila = mysqli_fetch_array($res);

    $codigo = $fila['ultimo_valor'] + 1;
    error_log("Select último valor: ".$codigo,0);

    $sql = "UPDATE numeradores SET ultimo_valor = $codigo WHERE tipo = '$numerador';";
    error_log("Update último valor: ".$sql,0);
    mysqli_query($conexion, $sql);
    
    return $codigo;
}

public static function obtenerTipoCampo($detalle, $con){
    $sql="SELECT tipo_campo FROM detalles where detalle = '$detalle'";
	$tc = mysqli_query($con,$sql);
	if ($tc->num_rows == 1) {
        $row = mysqli_fetch_array($tc);
        return $row['tipo_campo'];
		} else {
        return "";
        }
}
    
public static function obtenerMensajeAyuda($detalleAyuda, $con){
    $sql="select texto from traducciones where clave like 'AY$detalleAyuda' and idioma='".$_SESSION['idioma']."'";
//    error_log("!!!!!!!!!!!!!!!!!!!SQL Texto ayuda: "."AY".$detalleAyuda,0);
	$ay = mysqli_query($con,$sql);

	if ($ay->num_rows == 1) {
        $row = mysqli_fetch_array($ay);
        return $row['texto'];
		} else {
        return "";
        }
}
    
public static function guardarCodigo2FA($codigo,$para,$con){
    $movimiento = "update anunciantes set codigo2fa = '$codigo' where email = '$para'";
    $resulMovimiento = mysqli_query($con, $movimiento);
    return $resulMovimiento;
}
    
public static function ejecutar2FA($para,$ingreso,$con){
    $entorno=gethostname();
    if ($entorno === "DESKTOP-6LCGB06"){
        // Entorno local
        $file_path = "localhost/HMIPrueba";
    } else {
        // Entorno de producciÃ³n
        $file_path = "https://plusdata.info";
    }
    //código aleatorio
    $codigo = rand(100000,999999);
    
    Funciones::guardarCodigo2FA($codigo,$para,$con);
    
    // mensaje
    if ($ingreso === "IN"){
        $cuerpoMail = "
            <div style='margin: 0; font-family: helbetica,arial,sans-serif,serif; font-size: 20px; color:#003781'>
                <br>
                <p style='margin: 0; line-height: 1.2; font-size: 25px;'><strong>Segundo control de acceso a tu cuenta</strong></p>
                <p style='margin: 0; font-size: 12px; line-height: 1.2;'>(Lo realizamos para ayudar a proteger tus datos)</span></p>
                <p>El código para ingresar a tu cuenta es: <strong>$codigo</strong></p>
            </div>
        ";
    }

    $subject = "Segundo Factor de Control";
    $archivo="";
    Funciones::enviarMailVerif($para,$cuerpoMail,$subject,$archivo);
    return true;
}
    
public static function cartelAnuncio($anunciante,$anuncio,$con){
    if (!Funciones::obtenerValorParametro($con, 'ENVIO_CARTEL')){
        return 1;
    }

    $actualizar=1;
    $anuncianteIndividual=0;

    setlocale (LC_TIME, "es_ES");
    $sql="SELECT * from fichas where anunciante = ".$anunciante;

    $resultado = $con->query($sql);

    $anuncianteCorriente = $anunciante;

    $pdf = new PDF("L", "mm", "A4");
    $pdf->AddPage();

    $fila = $resultado->fetch_assoc();
        $pdf->SetMargins(20, 20, 20);

        $nombre = Funciones::obtenerNombreAnunciante("C",$anunciante, $con);
        $pdf->CentrarTexto(utf8_decode($nombre), 'Times', 90, 35);

        $operacionInmob = mb_strtoupper(Funciones::buscarOperacionInmob($con, $anuncio));
        $pdf->CentrarTexto(utf8_decode($operacionInmob), 'Arial', 230, 90);

        $nroAnuncio = Funciones::formato_numero($anuncio, 8, 4);
        $pdf->CentrarTexto(utf8_decode("Anuncio N°: ".$nroAnuncio), 'Arial', 75, 140);    

        $pdf->CentrarTexto('www.plusdata.info', 'Times', 95, 170);    

        $file_name = "Letrero_".$anuncianteCorriente."_".$anuncio."_".date('d-m-Y').".pdf";
        if (gethostname() == "DESKTOP-6LCGB06"){
            // Entorno local
            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file_name;
        } else {
            // Entorno de producción
            $file_path = "informes/letreros/" . $file_name;
            error_log("Letrero generado: ".$file_path);
        }
        $letrero = "Letrero_".$anuncianteCorriente."_".$anuncio."_".date('d-m-Y').".pdf";
        $pdf->Output($file_path, 'F');
        $to=Funciones::obtenerEmailAnunciante($anunciante, $con);
        $envioLetrero = Funciones::enviarMailLetrero($to, $letrero);
}
    
public static function verActivada($email, $con){
    $select = "SELECT activada FROM anunciantes WHERE email = '$email'";
    $resulSelect = mysqli_query($con, $select);
    if ($resulSelect){
        $row = mysqli_fetch_array($resulSelect);
        return $row['activada'];
    } else {
        return false;
    }
}

public static function obtenerFechaHoraAcept($anunciante, $con)
{
    $select = "
        SELECT DATE_FORMAT(fechaaceptacionterminos, '%d/%m/%Y %H:%i:%s') AS fechahora
        FROM anunciantes
        WHERE idanunciante = $anunciante
    ";

    $resulSelect = mysqli_query($con, $select);

    if (!$resulSelect){
        die(mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($resulSelect);

    return $row['fechahora'];
}
/*    
public static function obtenerLocalizacion(){
    $ip = $_SERVER['REMOTE_ADDR'];
    if ($ip <> "::1"){
        $ipdat = @json_decode(file_get_contents("https://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_countryName;
    } else {
        return "Argentina";
    }
}
*/
public static function obtenerLocalizacion() {

    // 1. Obtener IP real del usuario
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR'
    ];

    $ip = '0.0.0.0';

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            // Si viene una lista (proxy), tomar la primera
            $ipList = explode(',', $_SERVER[$key]);
            $ip = trim($ipList[0]);
            break;
        }
    }

    // 2. Consultar API gratuita ipwho.is
    $json = @file_get_contents("https://ipwho.is/" . $ip);
    if ($json === false) {
        return "Desconocido";
    }

    $data = json_decode($json);

    // 3. Validar respuesta
    if (!isset($data->success) || $data->success !== true) {
        return "Desconocido";
    }

    // 4. Devolver país
    return $data->country;
}

public static function borrarBanner($pais,$anunciante,$orden,$con){
    $sql_GetBanner=mysqli_query($con,
    "select urlbanner from banners where nroanunciante=".$anunciante." and secanunciante=".$orden." and pais=".$pais);
    $imagen=mysqli_fetch_array($sql_GetBanner);
    $bannerImgDelete = "banners/".$imagen['urlbanner'];

    $sqlDelBanner=mysqli_query($con,
        "delete from banners where nroanunciante=".$anunciante." and secanunciante=".$orden." and pais=".$pais);

    unlink($bannerImgDelete);

    return;
}

public static function obtenerDatosContacto($anunciante, $con){
    $salida[] = '';
    
    $sqlBanner=mysqli_query($con,
    "select urlbanner from banners where nroanunciante=".$anunciante." and pais=".$_SESSION['codPais']." and secanunciante=1");
//    "select urlbanner from banners where nroanunciante=".$anunciante." and secanunciante=1 and pais=".$_SESSION['codPais']);
    $imagen=mysqli_fetch_array($sqlBanner);
    if ($imagen){
        $salida[0] = "B";
        $salida[1] = "banners/".$imagen['urlbanner'];
        return $salida;
    }

    $sqlDatos="select nombrerz, telmovil, emaillaboral, contactonombre, contactotel, contactoemail from fichas where anunciante = $anunciante";
	$ficha = mysqli_query($con,$sqlDatos);
    
	if ($ficha->num_rows == 1) {
        $ficha = mysqli_query($con,$sqlDatos);
		$anunFicha = $ficha->fetch_assoc();
        if ($anunFicha['contactonombre'] <> ""){
            $salida[0] = 'T';
            $salida[1] = $anunFicha['contactonombre'];
            $salida[2] = $anunFicha['contactotel'];
            $salida[3] = $anunFicha['contactoemail'];
            return $salida;
        } else {
            $salida[0] = 'T';
            $salida[1] = $anunFicha['nombrerz'];
            $salida[2] = $anunFicha['telmovil'];
            $salida[3] = $anunFicha['emaillaboral'];
            return $salida;
        }
    }
}
    
public static function OTPEnvioCodSMS($nroMovil, $test){
    global $auth_basic;

    $curl = curl_init();
    $env = "verifMovilProd";
    $sender = "Plusdata";
    $phone_number = $nroMovil;
    $message = "Tu codigo de verificacion es: %CODE% ";

    $url = 'https://api.labsmobile.com/otp/sendCode?';
                $url .= 'env='.  urlencode($env) .'&';
                $url .= 'sender='.  urlencode($sender) .'&';
                $url .= 'phone_number=' .  urlencode($phone_number) .'&';
                $url .= 'message=' .  urlencode($message) .'&';
                $url .= 'test=' . urlencode($test);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$auth_basic
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

public static function OTPControlCodSMS($nroMovil, $codigoVerif){

    global $auth_basic;

    $curl = curl_init();

    $env = "verifMovilProd";
    $phone_number = $nroMovil;
    $code = $codigoVerif;

    $url = 'https://api.labsmobile.com/otp/validateCode?';
                $url .= 'env='.  urlencode($env) .'&';
                $url .= 'phone_number=' .  urlencode($phone_number) .'&';
                $url .= 'code=' .  urlencode($code);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$auth_basic
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    /////////////////////
/*
    $curl = curl_init();

    $env = "verifMovilProd";
    $phone_number = $nroMovil;

    $url = 'https://api.labsmobile.com/otp/checkCode?';
                $url .= 'env='.  urlencode($env) .'&';
                $url .= 'phone_number=' .  urlencode($phone_number) .'&';

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$auth_basic
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
*/
    return $response;
    }

public static function controlarTelMovil($telmovil, $con){
    $sql="select telmovil from fichas where telmovil = $telmovil";
	$ficha = mysqli_query($con,$sql);

	if ($ficha->num_rows == 1) {
        return 1;
		} else {
        return 0;
        }
	}
	
public static function controlarEmailLaboral($emaillaboral, $con){
    $auxiliar="<script>alert('El mail es: '$emaillaboral);</script>";

    $sql="select emaillaboral from fichas where emaillaboral = $emaillaboral";
	$ficha = mysqli_query($con,$sql);

	if ($ficha->num_rows == 1) {
        return 1;
		} else {
        return 0;
        }
	}
	
public static function usarMapas(){
   $navegador=Funciones::get_browser_name($_SERVER['HTTP_USER_AGENT']);
   $conMapas = true;
   if ($navegador === "Firefox"){
       $conMapas = false;
   }
   return $conMapas;
}

public static function obtenerEstadisticasAnuncio($anuncio, $con){
    $select = "SELECT * FROM datosanuncios WHERE nroanuncio = ".$anuncio." and (cntseleccionado > 0 or cntvercontacto > 0)";
    $resulSelect = mysqli_query($con, $select);
    if ($resulSelect){
        $row = mysqli_fetch_array($resulSelect);
        return $row;
    } else {
        return false;
    }
}

public static function obtenerUbicacion($tabla, $clave, $valor, $con){
	$query = mysqli_query($con,"SELECT DESCRIPCION FROM $tabla where $clave=$valor");	
	$row = mysqli_fetch_array($query);
	return $row['DESCRIPCION'];		
}

public static function SVGObtenerCodigo($tabla,$descripcion,$con){
    $descripcion = str_replace(' de ', '%', $descripcion);

    switch ($tabla) {
        case "regiones":
            $select = "SELECT COD_REGION FROM $tabla where COD_PAIS=".$_SESSION['codPais']." and DESCRIPCION like '%".$descripcion."%'";
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codRegion'] = $row['COD_REGION'];
            return $row['COD_REGION'];

        case "provincias":
            $select = "SELECT COD_PROVINCIA FROM $tabla where COD_REGION=".$_SESSION['codRegion']." and DESCRIPCION like '%".$descripcion."%'";
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codProvincia'] = $row['COD_PROVINCIA'];
            return $row['COD_PROVINCIA'];

        case "comarcas":
            $descripcion = str_replace(' ', '%', $descripcion);
            $select = "SELECT COD_COMARCA FROM $tabla where COD_PROVINCIA=".$_SESSION['codProvincia']." and DESCRIPCION like '%".$descripcion."'";
            error_log("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! SELECT: ".$select,0);
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codComarca'] = $row['COD_COMARCA'];
            return $row['COD_COMARCA'];
            
        case "municipiosxcomarca":
            $select = "SELECT COD_MUNICIPIO FROM $tabla where COD_COMARCA=".$_SESSION['codComarca']." and DESCRIPCION like '%".$descripcion."%'";
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codMunicipio'] = $row['COD_MUNICIPIO'];
            return $row['COD_MUNICIPIO'];

        case "localidades":
            $select = "SELECT COD_LOCALIDAD FROM $tabla where COD_MUNICIPIO=".$_SESSION['codMunicipio']." and DESCRIPCION like '%".$descripcion."%'";
//            $select = "SELECT COD_LOCALIDAD FROM $tabla where COD_MUNICIPIO=".$_SESSION['codMunicipio']." and DESCRIPCION like '%".$descripcion."'";
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codLocalidad'] = $row['COD_LOCALIDAD'];
            return $row['COD_LOCALIDAD'];

        case "barrios":
            $select = "SELECT COD_BARRIO FROM $tabla where COD_LOCALIDAD=".$_SESSION['codLocalidad']." and DESCRIPCION like '%".$descripcion."%'";
            $query = mysqli_query($con,$select);	
            $row = mysqli_fetch_array($query);
            $_SESSION['codBarrio'] = $row['COD_BARRIO'];
            return $row['COD_BARRIO'];
    }
}

    public static function enviarMailTabla($para, $mensaje){
//        $mensaje = iconv('utf-8', 'cp1252', html_entity_decode($mensaje));
//        error_log("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Mensaje: ".$mensaje,0);
        $mail = new PHPMailer(true);
        
        try {
            // ConfiguraciÃ³n bÃ¡sica
            $mail->setFrom('info@plusdata.info', 'Plusdata');
            $mail->addAddress($para);
            $mail->Subject = 'Consulta de aviso publicado de su búqueda guardada';

            // Contenido HTML
            $htmlEmail = "
            <table width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:600px; background-color:#f4f4f4;'>
             <tbody>
              <tr>
                <td align='left' width='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:600px; width:100%;'>
                    <img src='https://plusdata.info/imagenes/logoHomePlusdata.png' alt='Logo' width='30%'>
                </td>
              </tr>
             </tbody>
            </table>
            <table width='600px' height='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:600px;'>
             <tbody>
              <tr>
                <td align='left' width='100px' style='padding-top: 20px;'>
                    ".$mensaje."
                </td>
              </tr>
             </tbody>
            </table>
            <table width='100%' height='100%' cellpadding='0' cellspacing='0' border='0' style='max-width:600px;'>
             <tbody>
              <tr>
                <td align='center' style='padding-bottom: 20px; font-size:16px; color:#888888;'>
                 <p style='margin:0;'>Este mensaje fue enviado por Plusdata.info</p>
                 <p style='margin:0;'><a href='https://plusdata.info' style='color:#0066cc;'>Visita nuestro sitio</a></p>
                </td>
              </tr>
             </tbody>
            </table>
            ";
            
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = $htmlEmail;

            // Enviar
            $mail->send();
            echo 'Mensaje enviado correctamente';
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
        }        
    }
/*
    public static function enviarMail($para, $mensaje, $subject, $file_path){
        $mail = new PHPMailer(true);
//        $para="mtroncellito@yahoo.com";
        
        try {
            // ConfiguraciÃ³n bÃ¡sica
            if (gethostname() === "DESKTOP-6LCGB06"){
                // Entorno local
//                error_log("EMISOR: mtroncellito",0);
                $mail->setFrom('mtroncellito@gmail.com', 'Marchello');
            } else {
                // Entorno de producciÃ³n
//                error_log("EMISOR: info",0);
                $mail->setFrom('info@plusdata.info', 'Plusdata');
            }
            $mail->addAddress($para);
            $mail->Subject = $subject;

            // Contenido HTML
            $htmlEmail = "
            <html>
             <body>
                <div class='contenedor' align='center' width='100%' style='max-width:600px;'>
                 <div class='contenido' width: 600%; align='center'>
                    <div style='background-color: #BABABA; padding-top: 10px; padding-left: 10px; padding-bottom: 0;'>
                        <img src='https://plusdata.info/imagenes/logoHomePlusdata.png' style='width: 50%; max-width: 600px;'></img>
                    </div>
                    <div align='center' style='width: 100%; background-color: white; padding-bottom: 0px;'>
                      $mensaje
                    </div>
                    <div align='center' style='background-color: #BABABA; padding-top: 10px;'>
                      <p align='center' style='margin: 0; font-family: arial,sans-serif,serif; font-size: 15px; color:#003781'>¡Gracias por utilizar nuestros servicios!</p>
                      <p align='center' style='margin: 0;font-family: arial,sans-serif,serif; font-size: 14px; color:#003781'><a href='https://plusdata.info' style='color: #007BFF;'>
                      Visítenos en www.plusdata.info</a></p>
                      <p align='center' style='margin: 0; font-size: 10px; padding-bottom: 2px;'>Este mensaje fue enviado por Plusdata.info</p>
                    </div>
                 </div>
                </div>
             </body>
            </html>
            ";

//            error_log("ENVIAR MAIL completo: ".$htmlEmail." - mensaje a insertar: ".$mensaje,0);
            
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = $htmlEmail;
            if ($file_path <> ""){
                $mail->addAttachment($file_path);
            }
            // Enviar
            //$estadoEnvio=$mail->send();
            //$mail->send();
            return $mail->send();
//            echo 'Mensaje enviado correctamente';
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
        }        
    }
*/
public static function enviarMail($para, $mensaje, $subject, $file_path = "")
{
    $mail = new PHPMailer(true);
    // ACTIVAR DEBUG SMTP
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str) {
        error_log("SMTP: " . $str);
    };

    try {

        /* ============================================================
           CONFIGURACIÓN SMTP
           ============================================================ */
        $usarSMTP = true;   // Cambiar a false si querés probar sin SMTP

        if ($usarSMTP) {
            $mail->isSMTP();
            $mail->Host = 'bold-feistel.194-164-173-87.plesk.page';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@plusdata.info';
            $mail->Password = 'e&49ar9V0';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // IDENTIDAD EXACTA
            $mail->setFrom('admin@plusdata.info', 'Plusdata');
            $mail->Sender = 'admin@plusdata.info';

            // HTML LIMPIO
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';        
        }

        /* ============================================================
           REMITENTE SEGÚN ENTORNO
           ============================================================ */
        if (gethostname() === "DESKTOP-6LCGB06") {
            $mail->setFrom('mtroncellito@gmail.com', 'Marchello');
        } else {
            $mail->setFrom('admin@plusdata.info', 'Plusdata');
        }

        /* ============================================================
           DESTINATARIO + ASUNTO
           ============================================================ */
        $mail->addAddress($para);
        $mail->Subject = $subject;

        /* ============================================================
           CUERPO HTML LIMPIO Y COMPATIBLE
           ============================================================ */
        $mail->isHTML(true);
//        $mail->Body = $htmlEmail;

        $htmlEmail = "
        <html>
         <body>
            <div align='center' style='max-width:600px;margin:auto;'>
                <div style='background-color:#BABABA;padding:10px;'>
                    <img src='https://plusdata.info/imagenes/logoHomePlusdata.png' 
                         style='width:50%;max-width:600px;' />
                </div>

                <div style='background-color:white;padding:20px;font-family:Arial;'>
                    $mensaje
                </div>

                <div style='background-color:#BABABA;padding:10px;font-family:Arial;'>
                    <p style='margin:0;font-size:15px;color:#003781'>
                        ¡Gracias por utilizar nuestros servicios!
                    </p>
                    <p style='margin:0;font-size:14px;color:#003781'>
                        <a href='https://plusdata.info' style='color:#007BFF'>
                            Visítenos en www.plusdata.info
                        </a>
                    </p>
                    <p style='margin:0;font-size:10px;padding-bottom:2px;'>
                        Este mensaje fue enviado por Plusdata.info
                    </p>
                </div>
            </div>
         </body>
        </html>
        ";
        
        $mail->Body = $htmlEmail;

        /* ============================================================
           ADJUNTO (si existe)
           ============================================================ */
        if (!empty($file_path)) {
            $mail->addAttachment($file_path);
        }

        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str) {
            error_log("SMTP: " . $str);
        };
        /* ============================================================
           ENVÍO
           ============================================================ */
        return $mail->send();

    } catch (Exception $e) {
        error_log("Error al enviar el mensaje: " . $mail->ErrorInfo);
        return false;
    }
}
    
public static function enviarMailVerif($para, $mensaje, $subject, $file_path = "")
{
    $mail = new PHPMailer(true);
    // ACTIVAR DEBUG SMTP
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str) {
        error_log("SMTP: " . $str);
    };

    try {

        /* ============================================================
           CONFIGURACIÓN SMTP
           ============================================================ */
        $usarSMTP = true;   // Cambiar a false si querés probar sin SMTP

        if ($usarSMTP) {
            $mail->isSMTP();
            $mail->Host = 'bold-feistel.194-164-173-87.plesk.page';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@plusdata.info';
            $mail->Password = 'e&49ar9V0';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // IDENTIDAD EXACTA
            $mail->setFrom('admin@plusdata.info', 'Plusdata');
            $mail->Sender = 'admin@plusdata.info';
            $mail->ReturnPath = 'admin@plusdata.info';

            // HTML LIMPIO
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';        
        }

        /* ============================================================
           DESTINATARIO + ASUNTO
           ============================================================ */
        $mail->addAddress($para);
        $mail->Subject = $subject;

        /* ============================================================
           CUERPO HTML LIMPIO Y COMPATIBLE
           ============================================================ */
        $mail->isHTML(true);
        $htmlEmail = "
        <html>
         <body>
            <div align='center' style='max-width:600px;margin:auto;'>
                <div style='background-color:#BABABA;padding:10px;'>
                    <img src='https://plusdata.info/imagenes/logoHomePlusdata.png' 
                         style='width:50%;max-width:600px;' />
                </div>

                <div style='background-color:white;padding:20px;font-family:Arial;'>
                    $mensaje
                </div>

                <div style='background-color:#BABABA;padding:10px;font-family:Arial;'>
                    <p style='margin:0;font-size:15px;color:#003781'>
                        ¡Gracias por utilizar nuestros servicios!
                    </p>
                    <p style='margin:0;font-size:14px;color:#003781'>
                        <a href='https://plusdata.info' style='color:#007BFF'>
                            Visítenos en www.plusdata.info
                        </a>
                    </p>
                    <p style='margin:0;font-size:10px;padding-bottom:2px;'>
                        Este mensaje fue enviado por Plusdata.info
                    </p>
                </div>
            </div>
         </body>
        </html>
        ";
        $mail->Body = $htmlEmail;

        /* ============================================================
           ADJUNTO (si existe)
           ============================================================ */
        if (!empty($file_path)) {
            $mail->addAttachment($file_path);
        }
        /* ============================================================
           ENVÍO
           ============================================================ */
        return $mail->send();

    } catch (Exception $e) {
        error_log("Error al enviar el mensaje: " . $mail->ErrorInfo);
        return false;
    }
}
    
    public static function enviarMailLetrero($to, $file_name){
        //$to = "mtroncellito@gmail.com";
        $from = "info@plusdata.info";
        $subject = "Envio letrero Última publicación";

        //$file_path = "http://localhost/HMIPrueba/informes/letreros/"; // Ruta al archivo PDF
        //$file_name = "Letrero_8_258_20-07-2025.pdf"; // Nombre del archivo adjunto
        if (gethostname() == "DESKTOP-6LCGB06"){
            // Entorno local
            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file_name;
        } else {
            // Entorno de producciÃ³n
            $file_path = "informes/letreros/" . $file_name;
        }
        // Leer el contenido del archivo PDF
/*
        $file_content = file_get_contents($file_path);
        $file_content_encoded = base64_encode($file_content);
        $file_size = strlen($file_content);

        // Generar un boundary Ãºnico
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
        $message .= "Adjuntamos el cartel en formato PDF correspondiente al anuncio que ha publicado.\r\n"; // Mensaje de texto
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
            return true;
        } else {
            return false;
        }
*/
        $cuerpoMail="
        <div align='center' width='50%'>
        <p style='font-family: Helvetica; font-size: 30px; color:#003781;'><strong>Adjuntamos el cartel en formato PDF correspondiente al anuncio que ha publicado.</strong></p>
        </div>";
        $subject = "Envio letrero Última publicación";
        Funciones::enviarMail($to,$cuerpoMail,$subject,$file_path);
    }
    
    public static function enviarMailPlantillaSMTP($to, $file){
        $mail = new PHPMailer(true);
        
        try {
            // ConfiguraciÃ³n bÃ¡sica
            $mail->setFrom('info@plusdata.info', 'Plusdata');
            $mail->addAddress($to);
            $mail->Subject = '¡Envio de prueba con plantilla!';

            // Adjuntar archivo
            if (gethostname() == "DESKTOP-6LCGB06"){
                // Entorno local
                $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file;
            } else {
                // Entorno de producciÃ³n
                $file_path = "informes/letreros/" . $file;
            }

            $mail->addAttachment($file_path); // puede ser .jpg, .docx, etc.

            // Contenido HTML
            $htmlEmail = "
            <html>
             <body style='font-family: Arial;'>
                <div class='col col12' align='center' >
                    <img src='https://plusdata.info/imagenes/logoHomePlusdata.png' style='width: 30%; height: auto; padding-bottom: 30px;'></img>
                </div>
                
                <div class='contenedor'>
                 <div class='contenido'>
                    <h2>Hola Marcelo ðŸ‘‹</h2>
                    <p>Gracias por contactarnos. Te adjuntamos el archivo solicitado.</p>
                    <p><a href='https://plusdata.info' style='color: #007BFF;'>VisitÃ¡ nuestro sitio</a></p>
                    <hr>
                    <small>Este mensaje fue enviado por Plusdata.info</small>
                 </div>
                </div>
             </body>
            </html>
            ";

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = $htmlEmail;

            // Enviar
            $mail->send();
            echo 'Mensaje enviado correctamente';
        } catch (Exception $e) {
            echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
        }        
    }

    public static function enviarMailPlantilla($to, $file){
        //$to = "mtroncellito@gmail.com";
        $from = "info@plusdata.info";
        $subject = "Envio letrero Ãºltima publicaciÃ³n";

        //$file_path = "http://localhost/HMIPrueba/informes/letreros/"; // Ruta al archivo PDF
        //$file_name = "Letrero_8_258_20-07-2025.pdf"; // Nombre del archivo adjunto
        if (gethostname() == "DESKTOP-6LCGB06"){
            // Entorno local
            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file;
        } else {
            // Entorno de producciÃ³n
            $file_path = "informes/letreros/" . $file;
        }
        // Leer el contenido del archivo PDF
        $file_content = file_get_contents($file_path);
        $file_content_encoded = base64_encode($file_content);
        $file_size = strlen($file_content);

        // Generar un boundary Ãºnico
        $boundary = md5(time());

        // Cabeceras del correo
        $headers = "From: $from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
        
        // Cuerpo del correo
    
        $message  = "--".$boundary."\r\n";
        $message .= '
        <html>
        <body>
         <div class="col col-12") align="center">
          <img src="https://plusdata.info/imagenes/logoHomePlusdata.png" style="width: 30%; height: auto;"></img>
         </div>
        </body>
        </html>
        ';
        $message .= 'Content-type: text/html; charset=UTF-8\r\n';
        $message .= 'X-Mailer: PHP/' . phpversion();
        $message .= "Content-Transfer-Encoding: quoted-printable\r\n";
        $message .= "\r\n";
//        $message .= $mensLogo;
        $message .= "Adjunto encontrarÃ¡ el archivo PDF con el letrero de la publicaciÃ³n que recientemente ingreso.\r\n"; // Mensaje de texto
        $message .= "\r\n";
        $message .= "--".$boundary."\r\n";
        $message .= "Content-Type: application/octet-stream; name=\"".$file."\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-Disposition: attachment; filename=\"".$file."\"\r\n";
        $message .= "\r\n";
        $message .= $file_content_encoded."\r\n";
        $message .= "--".$boundary."--";
        // Enviar el correo
        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public static function buscarIdUbicacion($clave, $valor, $con){
	if(strpos($clave, 'pais') != false){
		return Funciones::obtenerUbicacion('paises', 'COD_NUM', $valor, $con);
	}
	if(strpos($clave, 'region') != false){
		return Funciones::obtenerUbicacion('regiones', 'COD_REGION', $valor, $con);
	}
	if(strpos($clave, 'provincia') != false){
		return Funciones::obtenerUbicacion('provincias', 'COD_PROVINCIA', $valor, $con);
	}
	if(strpos($clave, 'comarca') != false){
		return Funciones::obtenerUbicacion('comarcas', 'COD_COMARCA', $valor, $con);
	}
	if(strpos($clave, 'municipio') != false){
		return Funciones::obtenerUbicacion('municipiosxcomarca', 'COD_MUNICIPIO', $valor, $con);
	}
	if(strpos($clave, 'localidad') != false){
		return Funciones::obtenerUbicacion('localidades', 'COD_LOCALIDAD', $valor, $con);
	}
	if(strpos($clave, 'barrio') != false){
		return Funciones::obtenerUbicacion('barrios', 'COD_BARRIO', $valor, $con);
	}
	if(strpos($clave, 'nucleo') != false){
		return Funciones::obtenerUbicacion('nucleos', 'COD_NUCLEO', $valor, $con);
	}
	if(strpos($clave, 'manzana') != false){
		return Funciones::obtenerUbicacion('manzanas', 'COD_MANZANA', $valor, $con);
	}
}

public static function  armarDatosAnuncio($datos, $POST, $con){
    $items = explode("|",$datos);
    $datosMostrar='';
    $coma='';
    $sector='';
    $pos_ubic_ant=0;
    $pos_01_ant=0;
    $pos_02_ant=0;
    $pos_02c_ant=0;
    $pos_03_ant=0;
    $pos_04_ant=0;
    $pos_05_ant=0;
    $pos_06_ant=0;
    $pos_07_ant=0;
    $pos_06A_ant=0;
    $pos_08_ant=0;
    $pos_09_ant=0;
    $pos_10_ant=0;
    $pos_11_ant=0;
    $pos_11_ant=0;
    $pos_13_ant=0;
    $pos_09=0;
    
    $secLab=0;
    
    $no_mostrar_precio = Funciones::buscarItemEnFormulario("checkbox_02A_precio_oculto",$datos);

    foreach($items as $item){
        $claveValor=explode(",",$item);

        $pos_01 = strpos($claveValor[0], "_01_");
        if ($pos_01 <> 0){
            if ($pos_01_ant == 0){
                $datosMostrar.="<b><span data-trad="."Operación".">Operación".":</span></b>  ";
                $pos_01_ant = $pos_01;
            }
        }

        $pos_ubic = strpos($claveValor[0], "_02_");
        if ($pos_ubic <> 0){
            if ($pos_ubic_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Ubicación".">Ubicación:</span></b> ";
                $pos_ubic_ant = $pos_ubic;
                $coma="";
            }
        }

        $pos_03 = strpos($claveValor[0], "_03_");
        if ($pos_03 <> 0){
            if ($pos_03_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Estar".">Estar:</span></b> ";
                $pos_03_ant = $pos_03;
                $coma="";
            }
        }

        $pos_04 = strpos($claveValor[0], "_04_");
        if ($pos_04 <> 0){
            if ($pos_04_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Baño y Cocina"."'>Baño y Cocina:</span></b> ";
                $pos_04_ant = $pos_04;
                $coma="";
            }
        }

        $pos_05 = strpos($claveValor[0], "_05_");
        if ($pos_05 <> 0){
            if ($pos_05_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Otras dependencias"."'>Otras dependencias:</span></b> ";
                $pos_05_ant = $pos_05;
                $coma="";
            }
        }

        $pos_06 = strpos($claveValor[0], "_06_");
        if ($pos_06 <> 0){
            if ($pos_06_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Detalles".">Detalles:</span></b> ";
                $pos_06_ant = $pos_06;
                $coma="";
            }
        }

        $pos_06A = strpos($claveValor[0], "_06A_");
        if ($pos_06A <> 0){
            if ($pos_06A_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Orientación".">Orientación:</span></b> ";
                $pos_06A_ant = $pos_06A;
                $coma="";
            }
        }

        $pos_07 = strpos($claveValor[0], "_07_");
        if ($pos_07 <> 0){
            if ($pos_07_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Servicios".">Servicios:</span></b> ";
                $pos_07_ant = $pos_07;
                $coma="";
            }
        }

        $pos_08 = strpos($claveValor[0], "_08_");
        if ($pos_08 <> 0){
            if ($pos_08_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Estado del inmueble"."'>Estado del inmueble:</span></b> ";
                $pos_08_ant = $pos_08;
                $coma="";
            }
        }

        $pos_09 = strpos($claveValor[0], "_09_");
        if ($pos_09 <> 0){
            if ($pos_09_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Servicios regionales"."'>Servicios regionales:</span></b> ";
                $pos_09_ant = $pos_09;
                $coma="";
            }
        }

        $pos_10 = strpos($claveValor[0], "_10_");
        if ($pos_10 <> 0){
            if ($pos_10_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Situación legal"."'>Situación legal:</span></b> ";
                $pos_10_ant = $pos_10;
                $coma="";
            }
        }

        $pos_11 = strpos($claveValor[0], "_11_");
        if ($pos_11 <> 0){
            if ($pos_11_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Equipamiento rural"."'>Equipamiento rural:</span></b> ";
                $pos_11_ant = $pos_11;
                $coma="";
            }
        }
        
        $pos_13 = strpos($claveValor[0], "_13_");
        if ($pos_13 <> 0){
            if ($pos_13_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Datos adicionales"."'>Datos adicionales:</span></b> ";
                $pos_13_ant = $pos_13;
                $coma="";
            }
        }

        if ($claveValor[1] === 'v'){
            if ($claveValor[0] <> 'valor_checkbox_09_32'){
                if (strpos($claveValor[2],".") === false){
                    $claveValor[2] = number_format($claveValor[2],0);
                } else {
                    $claveValor[2] = number_format($claveValor[2],2,',');
                }
            }
        }
        
        if ($claveValor[0] === 'ubicd_calle_y_numero_id'){
            $datosMostrar.=$coma."<br><span data-trad="."Calle".">Calle:</span> ".$claveValor[2];
            $coma=' - ';
        } else {
            if ($claveValor[0] === 'ubicd_planta_id') {
                $datosMostrar.=$coma." "."<span data-trad=Planta".">Planta:</span> ".$claveValor[2];
                $coma=' - ';
            } else {
                if ($claveValor[0] === 'ubicd_numero_id') {
                } else {
                if ($claveValor[0] === 'valor_checkbox_02A_precio' || $claveValor[0] === 'valor_checkbox_02A_1'){
                    if($no_mostrar_precio === 0){
                        $datosMostrar.=": ".$claveValor[2];
                        $coma=' - ';
                    } else {
                        $datosMostrar.=$coma."<b><span data-trad=Consultar>"."Consultar</span></b>";
                    }
                    } else {
                        if ($no_mostrar_precio === 1 && $claveValor[2] === "Publicar con precio oculto"){
                        } else {
                            if ($claveValor[0] === "ubic_pais_id" || $claveValor[0] === "ubic_region_id" || $claveValor[0] ===   "ubic_provincia_id" || $claveValor[0] === "ubic_comarca_id" || $claveValor[0] === "ubic_municipio_id") {
                            } else {

                                if ($claveValor[1] === 'v'){
//                                    $coma=": ";
                                    $coma=" ";
                                }

                                if ($POST <> "" && $claveValor[1] <> 'v' && Funciones::buscarItemEnPOST($claveValor[0], $POST)){
                                    $secLab=$secLab+1;
                                    $label="lab_".$secLab;
                                    $texto=$claveValor[2];
                                    $datosMostrar .= $coma . "<span style='background-color: yellow;' data-trad='" . htmlspecialchars($texto) . "'>" . htmlspecialchars($texto) . "</span>";
                                    $coma=' - ';
                                } else {
                                    if ($claveValor[1] === 'v' && strpos($claveValor[2], ".") !== false){
                                        $texto = str_replace(".",",",$claveValor[2]);
                                        $coma=": ";
//                                        $texto = number_format($claveValor[2], 2, ',', '.');
                                        $datosMostrar .= $coma . "<span>" . $texto . "</span>";
                                    } else {
                                        if ($claveValor[1] === 'v'){
                                            $texto=$claveValor[2];
                                            $datosMostrar .= $coma . "<span>" . $texto . "</span>";
                                        } else {
                                            $texto=$claveValor[2];
                                            $datosMostrar .= $coma . "<span data-trad='" . htmlspecialchars($texto) . "'>" . htmlspecialchars($texto) . "</span>";
                                            $coma=' - ';
                                    }
                                }
                            }
                        }
                    }
            }}
            }}
        }// Fin foreach
    return $datosMostrar;
}

public static function  armarDatosAnuncioMail($datos, $POST){
    $items = explode("|",$datos);
    $datosMostrar='';
    $ini_parrafo='';
    $fin_parrafo='';
    $coma='';
    $sector='';
    $pos_ubic_ant=0;
    $pos_01_ant=0;
    $pos_02_ant=0;
    $pos_02c_ant=0;
    $pos_03_ant=0;
    $pos_04_ant=0;
    $pos_05_ant=0;
    $pos_06_ant=0;
    $pos_07_ant=0;
    $pos_06A_ant=0;
    $pos_08_ant=0;
    $pos_09_ant=0;
    $pos_10_ant=0;
    $pos_11_ant=0;
    $pos_09=0;
    
    $secLab=0;
    
    $no_mostrar_precio = Funciones::buscarItemEnFormulario("checkbox_02A_precio_oculto",$datos);


    foreach($items as $item){
        $claveValor=explode(",",$item);

        $pos_01 = strpos($claveValor[0], "_01_");
        if ($pos_01 <> 0){
            if ($pos_01_ant == 0){
                $datosMostrar.="<b>Operación:</b> ";
                $pos_01_ant = $pos_01;
            }
        }

        $pos_ubic = strpos($claveValor[0], "bic_");
        if ($pos_ubic <> 0){
            if ($pos_ubic_ant == 0){
                $datosMostrar.="<br><b>Ubicación:</b> ";
                $pos_ubic_ant = $pos_ubic;
                $coma="";
            }
        }

        $pos_03 = strpos($claveValor[0], "_03_");
        if ($pos_03 <> 0){
            if ($pos_03_ant == 0){
                $datosMostrar.="<br><b>Estar:</b> ";
                $pos_03_ant = $pos_03;
                $coma="";
            }
        }

        $pos_04 = strpos($claveValor[0], "_04_");
        if ($pos_04 <> 0){
            if ($pos_04_ant == 0){
                $datosMostrar.="<br><b>Baño y Cocina:</b> ";
                $pos_04_ant = $pos_04;
                $coma="";
            }
        }

        $pos_05 = strpos($claveValor[0], "_05_");
        if ($pos_05 <> 0){
            if ($pos_05_ant == 0){
                $datosMostrar.="<br><b>Otras dependencias:</b> ";
                $pos_05_ant = $pos_05;
                $coma="";
            }
        }

        $pos_06 = strpos($claveValor[0], "_06_");
        if ($pos_06 <> 0){
            if ($pos_06_ant == 0){
                $datosMostrar.="<br><b>Detalles:</b> ";
                $pos_06_ant = $pos_06;
                $coma="";
            }
        }

        $pos_06A = strpos($claveValor[0], "_06A_");
        if ($pos_06A <> 0){
            if ($pos_06A_ant == 0){
                $datosMostrar.="<br><b>Orientación:</b> ";
                $pos_06A_ant = $pos_06A;
                $coma="";
            }
        }

        $pos_07 = strpos($claveValor[0], "_07_");
        if ($pos_07 <> 0){
            if ($pos_07_ant == 0){
                $datosMostrar.="<br><b>Servicios:</b> ";
                $pos_07_ant = $pos_07;
                $coma="";
            }
        }

        $pos_08 = strpos($claveValor[0], "_08_");
        if ($pos_08 <> 0){
            if ($pos_08_ant == 0){
                $datosMostrar.="<br><b>Estado del inmueble:</b> ";
                $pos_08_ant = $pos_08;
                $coma="";
            }
        }

        $pos_09 = strpos($claveValor[0], "_09_");
        if ($pos_09 <> 0){
            if ($pos_09_ant == 0){
                $datosMostrar.="<br><b>Servicios regionales:</b> ";
                $pos_09_ant = $pos_09;
                $coma="";
            }
        }

        $pos_10 = strpos($claveValor[0], "_10_");
        if ($pos_10 <> 0){
            if ($pos_10_ant == 0){
                $datosMostrar.="<br><b>Situación legal:</b> ";
                $pos_10_ant = $pos_10;
                $coma="";
            }
        }

        $pos_11 = strpos($claveValor[0], "_11_");
        if ($pos_11 <> 0){
            if ($pos_11_ant == 0){
                $datosMostrar.="<br><b>Equipamiento rural:</b> ";
                $pos_11_ant = $pos_11;
                $coma="";
            }
        }

        if (count($claveValor) > 1){
            if ($claveValor[1] === 'v'){
                if ($claveValor[0] <> 'valor_checkbox_09_32'){
                    $claveValor[2] =  number_format($claveValor[2],0,',','.' );
                }
            }
            if ($claveValor[0] === 'ubicd_calle_y_numero_id'){
                $datosMostrar.=$coma."<br>Calle: ".$claveValor[2];
                $coma=' - ';
            } else {
                if ($claveValor[0] === 'ubicd_planta_id') {
                    $datosMostrar.=$coma.", Planta: ".$claveValor[2];
                    $coma=' - ';
                } else {
                    if ($claveValor[0] === 'ubicd_numero_id') {
                    } else {
                    if ($claveValor[0] === 'valor_checkbox_02A_precio' || $claveValor[0] === 'valor_checkbox_02A_1'){
                        if($no_mostrar_precio === 0){
                            $datosMostrar.=": ".$claveValor[2];
                            $coma=' - ';
                        } else {
                            $datosMostrar.=$coma."Consultar";
                        }
                        } else {
                            if ($no_mostrar_precio === 1 && $claveValor[2] === "Publicar con precio oculto"){
                            } else {
                                if ($claveValor[0] === "ubic_pais_id" || $claveValor[0] === "ubic_region_id" || $claveValor[0] ===   "ubic_provincia_id" || $claveValor[0] === "ubic_comarca_id" || $claveValor[0] === "ubic_municipio_id") {
                                } else {
                                    if ($claveValor[1] === 'v'){
                                        $coma=": ";
                                    }
                                    if ($POST <> "" && $claveValor[1] <> 'v' && Funciones::buscarItemEnPOST($claveValor[0], $POST)){
                                        $secLab=$secLab+1;
                                        $label="lab_".$secLab;
                                        $texto=$claveValor[2];
                                        $datosMostrar.=$coma.Funciones::armarTextoColor($label, $texto);
                                        $coma=' - ';
                                    } else {
                                    $datosMostrar.=$coma.$claveValor[2];
                                    $coma=' - ';
                                    }
                                }
                            }
                        }
                }}}
        }
    }// Fin foreach
    return $datosMostrar;
}

    
    public static function armarTextoColor($label, $texto){
        return "<label class=".$label." for=".$label." id=".$label." style='background-color: yellow;'><small>".$texto."</small></label>"; 
    }

    public static function verTipoPropiedad($con,$tipoPropiedad){
    if ($tipoPropiedad === 'Casa' ||
        $tipoPropiedad === 'Chalet' ||
        $tipoPropiedad === 'Cabañaa' ||
        $tipoPropiedad === 'Ático' ||
        $tipoPropiedad === 'Suelos' ||
        $tipoPropiedad === 'Naves' ||
        $tipoPropiedad === 'Trasteros') {
        
        return 0;
    }
    
    return 1;
    
}

public static function buscarItemEnFormulario($clave,$datosasociados){
    $items = explode("|",$datosasociados);
    
    foreach($items as $item){
        $claveValor=explode(",",$item);
        if ($clave === $claveValor[0]){
            return 1;
        }
    }
    return 0;
}

public static function obtenerValorDeItemEnFormulario($clave,$datosasociados){
    $items = explode("|",$datosasociados);
    
    foreach($items as $item){
        $claveValor = explode(",",$item);
            if (count($claveValor) > 1){
                if (($clave === $claveValor[0]) || ($clave === "checkbox_02A_moneda" &&
                    ($claveValor[0] === "checkbox_02A_euro" ||
                     $claveValor[0] === "checkbox_02A_dolar" ||
                     $claveValor[0] === "checkbox_02A_argentino" ||
                     $claveValor[0] === "checkbox_02A_uruguay" ||
                     $claveValor[0] === "checkbox_02A_real")))
                {
                    return $claveValor[2];
                }
            }
    }
    return "";
}

public static function cargarHistoricoInforme($con,$tipoInforme,$anunciante, $archivoCargar){
    $insert = "insert into historicoinformes (tipoinforme,anunciante,nombreinforme) values (".$tipoInforme.",".$anunciante.",'".$archivoCargar."')";
    $aux=Funciones::actualizarAnuncioBD($insert, "N", $con);
    return $aux;
}
    
public static function buscarMensajeAnuncio($con, $anuncio, $idMensaje){
    $select = "SELECT mensajedelanunciante FROM datosformulario WHERE nroanuncio = ".$anuncio." and campo = '".$idMensaje."'";
    $resulSelect = mysqli_query($con, $select);
    $row = mysqli_fetch_array($resulSelect);
    return $row['mensajedelanunciante'];
}

public static function buscarOperacionInmob($con, $nroanuncio){
    $idMensaje = "radio_01_0";
    $select = "SELECT valor FROM datosformulario WHERE nroanuncio = ".$nroanuncio." and campo = '".$idMensaje."'";
    $resulSelect = mysqli_query($con, $select);
    $row = mysqli_fetch_array($resulSelect);
    return $row['valor'];
}

public static function obtenerCantAvisosActivos($anunciante, $con){
    $select = "SELECT count(*) cantidad FROM ctacteavisos WHERE estado = 1 and nroanunciante = ".$anunciante;
    $resulSelect = mysqli_query($con, $select);
    $row = mysqli_fetch_array($resulSelect);
    return $row['cantidad'];
}
    
public static function obtenerEstadoAviso($con, $nroanuncio){
    $select = "select estado from datosbusquedasformulario where nroanuncio = $nroanuncio";
    $resulSelect = mysqli_query($con, $select);
    $estado = mysqli_fetch_array($resulSelect);
    return $estado['estado'];
}
    
public static function validarInmobiliaria($con, $anunciante){
    $alertStr="<script>alert('La inmobiliaria es: '$anunciante);</script>";
    echo $alertStr;
}
    
public static function obtenerValorParametro($con, $parametro){
    $busCostoDiario = "select * from parametros where nombreparam ='".$parametro."';";
    $resulCostoDiario = mysqli_query($con, $busCostoDiario);
    $valor = mysqli_fetch_array($resulCostoDiario);
    return $valor['valorparam'];
}

public static function mostrarTipoCliente($con,$anunciante){
    $select = "SELECT clientetipo FROM fichas WHERE anunciante=".$anunciante;
    $resulFicha = mysqli_query($con, $select);
    $ficha = mysqli_fetch_array($resulFicha);

    if ($ficha['clientetipo'] == "R"){
        return "Inmobiliaria";
    }
    
    if ($ficha['clientetipo'] == "O"){
        return "Sucursal";
    }
    
    if ($ficha['clientetipo'] == "P"){
        return "Particular";
    }
    
    return "Error";

}

public static function consultarClienteTipo($con,$anunciante){
    $select = "SELECT clientetipo FROM fichas WHERE anunciante=".$anunciante;
    $resulFicha = mysqli_query($con, $select);
    $ficha = mysqli_fetch_array($resulFicha);
    return $ficha['clientetipo'];
}

public static function transferirAnunciante($anuncianteTransferir){
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//    $mysqli = new mysqli("localhost", "plusdataAdmDB", "6Xe#4p4o7", "plusdataDB");

        
    if ($mysqli->connect_error) {
        die("Error de conexiÃ³n: " . $mysqli->connect_error);
    }

    try {
        $mysqli->autocommit(false); // Desactiva autocommit

        $mysqli->begin_transaction();

        $transferencias = "SELECT f.anunciante, f.anuncianteinmob, f.idficha FROM fichas f WHERE (f.anunciante = ".$_SESSION['anunciante']." or f.anuncianteinmob=".$_SESSION['anunciante']." ) and f.clientetipo='O' "." and f.anunciante = ".$anuncianteTransferir." and f.anuncianteinmob >  0";
        $transfQuery = $mysqli->query($transferencias);

        while($row = $transfQuery->fetch_array(MYSQLI_ASSOC)){

            $idfica = $row['idficha'];
            $emisor = $row['anunciante'];
            $receptor = $row['anuncianteinmob'];
            
//            $updateFicha="update fichas set anuncianteinmob = 0, anuncianteinmobant = ".$receptor." where idficha = ".$idfica.";";
//            $mysqli->query($updateFicha);
            
            $transferirDBF="update datosbusquedasformulario db set db.nroanunciante = ".$receptor.
            ", db.sucursalanterior = ".$emisor." where db.nroanunciante = ".$emisor.";";
            $mysqli->query($transferirDBF);    

            $transferirDF="update datosformulario df set df.nroanunciante = ".$receptor.
            ", df.sucursalanterior = ".$emisor." where df.nroanunciante = ".$emisor.";";
            $mysqli->query($transferirDF);

            $transferirCC="update ctacteavisos cc set cc.nroanunciante = ".$receptor.
            ", cc.sucursalanterior = ".$emisor." where cc.nroanunciante = ".$emisor.";";
            $mysqli->query($transferirCC);   
/*
            $transferirDFA="update detallefacturacion df set df.nroanunciante = ".$receptor.
            ", df.sucursalanterior = ".$emisor." where df.nroanunciante = ".$emisor.";";
            error_log("==================== transferirDFA: ".$transferirDFA,0);
            $mysqli->query($transferirDFA);   
*/
            $transferirBG="update busquedasguardadas bg set bg.anunciante = ".$receptor.
            ", bg.sucursalanterior = ".$emisor." where bg.anunciante = ".$emisor.";";
            $mysqli->query($transferirBG);   

            $transferirBA="update banners ba set ba.nroanunciante = ".$receptor.
            ", ba.sucursalanterior = ".$emisor." where ba.nroanunciante = ".$emisor.";";
            $mysqli->query($transferirBA);   

            $transferirHI="update historicoinformes hi set hi.anunciante = ".$receptor.
            ", hi.sucursalanterior = ".$emisor." where hi.anunciante = ".$emisor.";";
            $mysqli->query($transferirHI);

            $transferirCM="update ctactemonedero cc set cc.nroanunciante = ".$receptor.
            ", cc.sucursalanterior = ".$emisor." where cc.nroanunciante = ".$emisor.";";
            $mysqli->query($transferirCM);
            
            $mysqli->commit(); // Todo saliÃ³ bien
            // TransacciÃ³n exitosa...
        } // Fin while...
    } catch (Exception $e) { // Fin del try...
        $mysqli->rollback(); // Algo fallÃ³
        echo "Error en la transacciÃ³n: " . $e->getMessage();
        error_log("####### ERROR EN TRANSACCIONES DE TRANSFERENCIA. SE HIZO ROLLBACK #######");
        $mysqli->close();
        return false;
    } finally {
        $mysqli->close();
    }
    return true;
} // Fin funcion...

public static function obtenerCodigoOficina($con,$anunciante){
    $busAnuncianteOfi = "select anuncianteinmob from fichas where anunciante = ".$anunciante.";";
    $resulAnuncianteOfi = mysqli_query($con, $busAnuncianteOfi);
    $fila = mysqli_fetch_array($resulAnuncianteOfi);
return $fila['anuncianteinmob'];
}

    public static function obtenerEstadoSucursal($con,$anunciante){
        $busEstadoSucursal = "select f.estadosucursal, f.clientetipo from fichas f, anunciantes a where a.idanunciante = ".$anunciante." and f.idficha=a.ficha".";";
        $resulEstadoSucursal = mysqli_query($con, $busEstadoSucursal);
        $fila = mysqli_fetch_array($resulEstadoSucursal);
        if($fila['clientetipo'] === "O"){
            return $fila['estadosucursal'];
        }
        return true;
}

public static function darFormatoCP($cp){
    if($cp < 10000){
        return "0".$cp;
    } else {
        return $cp;
    }
}

public static function dias_entre_fechas($fechaMenor, $fechaMayor){
    $date1 = new DateTime($fechaMayor);
    $date2 = new DateTime($fechaMenor);
    $diff = $date1->diff($date2);
    $dias = $diff->days;
    $dias =  $dias + 1;
    return $dias;
}

public static function marcarMovAplicado($id,$con){
    $movimiento = "update ctacteavisos set aplicado = 1 where id =$id";
    $resulMovimiento = mysqli_query($con, $movimiento);
    return $resulMovimiento;
}

public static function obtenerCantConsumosCteAvisos($cod_pais,$nroanunciante,$fechaHoy,$con){
    $cantConsumos=0;
//    $select = "SELECT cod_pais,nroanunciante,nroanuncio, date(fechaactivacion) fechaactivacion, estado, fechainactivo from ctacteavisos where (nroanunciante = ".$nroanunciante." or nroanuncianteinmob= ".$nroanunciante.") and estado in (1, 2) 
//    group by nroanunciante,nroanuncio,date(fechainactivo), estado
//    order by nroanunciante,nroanuncio";
    $select = "SELECT cod_pais,nroanunciante,nroanuncio, fechaactivacion, estado, fechainactivo from ctacteavisos where (nroanunciante = ".$nroanunciante." or nroanuncianteinmob= ".$nroanunciante.") and estado in (1, 2) 
    group by cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo, estado
    order by nroanunciante,nroanuncio,fechaactivacion";
    
    $result = $con->query($select);
    $dias=0;
    $diasDesdeActivacion=0;
    while($fila = mysqli_fetch_array($result)){
        if ($fila['estado'] == 1 || $fila['estado'] == 2){
            $primerDiaMes = strtotime(date("01-m-Y",time()));
            $ultimoDiaMes = strtotime(date("t-m-Y",time()));
            if (is_null($fila['fechainactivo'])){
                $activacion = strtotime(date($fila['fechaactivacion']));
                if ($activacion >= $primerDiaMes && $activacion <= $ultimoDiaMes){
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('Y-m-d'));
//                    echo "Anuncio1: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                } else {
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('d-m-Y'));
//                    echo "Anuncio2: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            } else {
                $inactivacion = strtotime(date($fila['fechainactivo']));
                if ($inactivacion >= $primerDiaMes && $inactivacion <= $ultimoDiaMes){
    //                $primerDiaDeUnMesCualquiera=date('Y-m-01',strtotime($fila['fechainactivo']));
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date($fila['fechainactivo']));
//                    echo "Anuncio3: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            }
        }
    }//FIN WHILE
    return $dias;		
}

public static function obtenerCantConsumosCteAvisoscron($cod_pais,$nroanunciante,$primerDia,$ultimoDia,$con){
    $cantConsumos=0;
//    $select = "SELECT cod_pais,nroanunciante,nroanuncio, date(fechaactivacion) fechaactivacion, estado, fechainactivo from ctacteavisos where (nroanunciante = ".$nroanunciante." or nroanuncianteinmob= ".$nroanunciante.") and estado in (1, 2) 
//    group by nroanunciante,nroanuncio,date(fechainactivo), estado
//    order by nroanunciante,nroanuncio";
    $select = "SELECT cod_pais,nroanunciante,nroanuncio, fechaactivacion, estado, fechainactivo from ctacteavisos where (nroanunciante = ".$nroanunciante." or nroanuncianteinmob= ".$nroanunciante.") and estado in (1, 2) 
    group by cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo, estado
    order by nroanunciante,nroanuncio,fechaactivacion";
    
    $result = $con->query($select);
    $dias=0;
    $diasDesdeActivacion=0;
    while($fila = mysqli_fetch_array($result)){
        if ($fila['estado'] == 1 || $fila['estado'] == 2){
            $primerDiaMes = strtotime(date("01-m-Y",$primerDia));
            $ultimoDiaMes = strtotime(date("t-m-Y",$ultimoDia));
            if (is_null($fila['fechainactivo'])){
                $activacion = strtotime(date($fila['fechaactivacion']));
                if ($activacion >= $primerDiaMes && $activacion <= $ultimoDiaMes){
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('Y-m-d'));
//                    echo "Anuncio1: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                } else {
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('d-m-Y'));
//                    echo "Anuncio2: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            } else {
                $inactivacion = strtotime(date($fila['fechainactivo']));
                if ($inactivacion >= $primerDiaMes && $inactivacion <= $ultimoDiaMes){
    //                $primerDiaDeUnMesCualquiera=date('Y-m-01',strtotime($fila['fechainactivo']));
                    $dias += Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date($fila['fechainactivo']));
//                    echo "Anuncio3: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            }
        }
    }//FIN WHILE
    return $dias;		
}

public static function obtenerSaldoInicialCtaCteMonedero($cod_pais,$nroanunciante,$primerDiaMes,$con){
    $saldoInicial=0;
    $saldoInicial=Funciones::obtenerRestoAvisos($con,$nroanunciante);

    $select = "SELECT * FROM ctactemonedero WHERE nroanunciante = ".$nroanunciante." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
    
    $result = $con->query($select);
	while($row = mysqli_fetch_array($result)){
        if ($row['cantidaddias'] >= 0){
            $saldoInicial=$saldoInicial - $row['cantidaddias'];
        } else {
            $saldoInicial=$saldoInicial + $row['cantidaddias'] * -1;
        }
    }
	return $saldoInicial;		
}

public static function obtenerSaldoInicialCtaCteMonederocron($cod_pais,$nroanunciante,$primerDiaMes,$ultimoDiaMes,$con){
    $saldoInicial=0;
    $saldoInicial=Funciones::obtenerRestoAvisos($con,$nroanunciante);

//    $select = "SELECT * FROM ctactemonedero WHERE nroanunciante = ".$nroanunciante." and date(fechamov) BETWEEN 
//    CAST(".date('d-m-Y',$primerDiaMes)." AS DATE) and CAST(".date('d-m-Y',$ultimoDiaMes)." as DATE)";
    $select = "SELECT * FROM ctactemonedero WHERE nroanunciante = ".$nroanunciante." and fechamov >= '".date('Y-m-d',$primerDiaMes)."' and  fechamov <= '".date('Y-m-d',$ultimoDiaMes)."'";
    $result = $con->query($select);
	while($row = mysqli_fetch_array($result)){
        if ($row['cantidaddias'] >= 0){
            $saldoInicial=$saldoInicial - $row['cantidaddias'];
        } else {
            $saldoInicial=$saldoInicial + $row['cantidaddias'] * -1;
        }
    }
	return $saldoInicial;		
}

public static function obtenerCodigoMovCtaCte($con,$codigo){
    $busCodigo = "select * from codigosmovimientoctacte where codigo=".$codigo.";";
    $resulCodigo = mysqli_query($con, $busCodigo);
    $fila = mysqli_fetch_array($resulCodigo);
	$datos[] = '';
    $datos[0]=$fila['descripcion'];
    $datos[1]=$fila['operacion'];
    return $datos;    
}

public static function cargarMovimientoMonederocron($con,$codMov,$cod_pais,$nroanunciante,$cantDias,$primerDia,$ultimoDia){
	$log="N";
	$aux=false;
//    $datosCodigo[] = '';
    $cantDiasSigno = 0;
    $datosCodigo=Funciones::obtenerCodigoMovCtaCte($con,$codMov);
    if ($datosCodigo[1] == '-'){
        $cantDias = $cantDias * -1;
    }
    if ($codMov == 1){
//        $select="select * from ctactemonedero WHERE cod_pais=".$cod_pais." and nroanunciante=".$nroanunciante." and codigomov=1 and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
//        $select="select * from ctactemonedero WHERE nroanunciante=".$nroanunciante." and codigomov=1 and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
        
        $select="select * from ctactemonedero WHERE nroanunciante=".$nroanunciante." and codigomov=1 and fechamov >= '".date('Y-m-1', $primerDia)."' and fechamov <= '".date('Y-m-t',$ultimoDia)."'";
        error_log('SELECT MOVIMIENTOS CRON: '.$select,0);

        $saldoInicial=Funciones::obtenerRestoAvisos($con,$nroanunciante);
        $resulSelect = mysqli_query($con, $select);
        $fila = mysqli_fetch_array($resulSelect);

        $delete="DELETE from ctactemonedero WHERE nroanunciante=".$nroanunciante." and codigomov=1 and fechamov >= '".date('Y-m-1', $primerDia)."' and fechamov <= '".date('Y-m-t',$ultimoDia)."'";
        $resulDelete = mysqli_query($con, $delete);
        if ($resulDelete){
            if (isset($fila['cantidaddias'])){
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante) + ($fila['cantidaddias'] * -1);
            } else {
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante);
            }
            $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$nroanunciante,$restanAvisos);
        }
    }
    $insert = "insert into ctactemonedero (cod_pais,nroanunciante,fechamov,codigomov,cantidaddias) values (".$cod_pais.','.$nroanunciante.",CURRENT_DATE,".$codMov.",".$cantDias.")";
    $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
    return $aux;    
}
    
public static function cargarMovimientoMonedero($con,$codMov,$cod_pais,$nroanunciante,$cantDias){
	$log="N";
	$aux=false;
//    $datosCodigo[] = '';
    $cantDiasSigno = 0;
    $datosCodigo=Funciones::obtenerCodigoMovCtaCte($con,$codMov);
    if ($datosCodigo[1] == '-'){
        $cantDias = $cantDias * -1;
    }
    if ($codMov == 1){
//        $select="select * from ctactemonedero WHERE cod_pais=".$cod_pais." and nroanunciante=".$nroanunciante." and codigomov=1 and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
        $select="select * from ctactemonedero WHERE nroanunciante=".$nroanunciante." and codigomov=1 and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
        $saldoInicial=Funciones::obtenerRestoAvisos($con,$nroanunciante);
        $resulSelect = mysqli_query($con, $select);
        $fila = mysqli_fetch_array($resulSelect);

        $delete="DELETE from ctactemonedero WHERE nroanunciante=".$nroanunciante." and codigomov=1 and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW())";
        $resulDelete = mysqli_query($con, $delete);
        if ($resulDelete){
            $restanAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante) + ($fila['cantidaddias'] * -1);
            $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$nroanunciante,$restanAvisos);
        }
    }
    $insert = "insert into ctactemonedero (cod_pais,nroanunciante,fechamov,codigomov,cantidaddias) values (".$cod_pais.','.$nroanunciante.",CURRENT_DATE,".$codMov.",".$cantDias.")";
    $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
    return $aux;    
}
    
public static function fecha_AMD_to_DMA($fecha) {
    setlocale (LC_TIME, "es_ES");
    $originalDate = $fecha;
    $newDate = date('d-m-Y', strtotime($originalDate));
//    $newDate = date("d l Y", strtotime($originalDate));
    return $newDate;
}

public static function fecha_AMD_to_DMA_Cron($fecha) {
    setlocale (LC_TIME, "es_ES");
    $originalDate = $fecha;
    $newDate = date("%B %Y", strtotime($originalDate));
    return $newDate;
}

public static function fecha_AMD_to_DMA_ddmmaaaa($fecha) {
    setlocale (LC_TIME, "es_ES");
    $originalDate = $fecha;
//    $newDate = strftime("%e %B %Y", strtotime($originalDate));
    $newDate = date("d/m/Y", strtotime($originalDate));
    return $newDate;
}

public static function formato_numero($numero, $lngNumero, $cantDigSep){
    $stringAnun = substr(str_repeat(0, $lngNumero).$numero, - $lngNumero);
    $stringAnun = substr($stringAnun,0,$cantDigSep)." ".substr($stringAnun, -$cantDigSep);
    return $stringAnun;
}
    
public static function obtenerFormatoCPPais($con,$codPais){
    $busPais = "select * from paises where cod_num='".$codPais."';";
    $resulPais = mysqli_query($con, $busPais);
    $fila = mysqli_fetch_array($resulPais);
    $salida[]='';
    $salida[0] = $fila['mascaracp'];
    $salida[1] = $fila['comentariomascaracp'];
    return $salida;
}
    
public static function obtenerDescPais($con,$codPais){
    $busPais = "select DESCRIPCION from paises where cod_num='".$codPais."';";
    $resulPais = mysqli_query($con, $busPais);
    $fila = mysqli_fetch_array($resulPais);
    $aux = iconv('utf-8', 'cp1252', html_entity_decode($fila['DESCRIPCION']));
    return $aux;    
}
                 
public static function obtenerCodPais($con,$descipcion){
    $busPais = "select COD_NUM from paises where DESCRIPCION like '%".$descipcion."%';";
    $resulDescPais = mysqli_query($con, $busPais);
    $fila = mysqli_fetch_array($resulDescPais);
    return $fila['COD_NUM'];
}
                 
public static function obtenerMapaPais($con,$codPais){
    $busPais = "select mapa from paises where cod_num='".$codPais."';";
    $resulPais = mysqli_query($con, $busPais);
    $fila = mysqli_fetch_array($resulPais);
    $aux = iconv('utf-8', 'cp1252', html_entity_decode($fila['mapa']));
    return $aux;    
}
                 
public static function actualizaRestoAvisos($con,$anunciante,$restanAvisos){
    $busAnunciante = "update fichas set avisosdisponibles=$restanAvisos where anunciante =$anunciante";
    $resulAnunciante = mysqli_query($con, $busAnunciante);
    return $resulAnunciante;
}

public static function obtenerRestoAvisos($con,$anunciante){
    $busAnunciante = "select avisosdisponibles, anuncianteinmob from fichas where anunciante = ".$anunciante.";";
    $resulAnunciante = mysqli_query($con, $busAnunciante);
    $fila = mysqli_fetch_array($resulAnunciante);
    if ($fila['anuncianteinmob'] == 0){
        return $fila['avisosdisponibles'];    
    } else {
        $anuncianteInmob=$fila['anuncianteinmob'];
        $busAnuncianteInmob = "select avisosdisponibles from fichas where anunciante = ".$anuncianteInmob.";";
        $resulAnuncianteInmob = mysqli_query($con, $busAnuncianteInmob);
        $fila = mysqli_fetch_array($resulAnuncianteInmob);
        return $fila['avisosdisponibles'];    
    }
}
    
public static function obtenerImgBanner($pais,$anunciante,$orden,$con){
    $imagen = false;
    $salida[] = "";
    
    if (is_numeric($anunciante)){
        $sql_banner=mysqli_query($con,
        "select urlbanner from banners where nroanunciante=".$anunciante." and secanunciante=".$orden." and pais=".$pais);
        $imagen=mysqli_fetch_array($sql_banner);
    }
    if ($imagen){
        $urlimagen = "banners/".$imagen['urlbanner'];
        $salida[0] = 1;
        $salida[1] = $urlimagen;
    } else {
        $urlimagen = "fotosPublicaciones/SinImagenDisponible_con_texto.png";
        $salida[0] = 0;
        $salida[1] = $urlimagen;
    }
    return $salida;
 }

public static function obtenerImgAnuncio($pais,$anuncio,$orden,$con){
    $imagen = false;
    if (is_numeric($anuncio)){
        $sql_slider=mysqli_query($con,
//        "select urlimagen from slider where pais=".$_SESSION['codPais']." and nroanuncio=".$anuncio." and      <br>
        "select urlimagen from slider where nroanuncio=".$anuncio." order by secanuncio asc");
        $imagen=mysqli_fetch_array($sql_slider);
    }
    if ($imagen){
        $urlimagen = "fotosPublicaciones/".$imagen['urlimagen'];
    } else {
        $urlimagen = "fotosPublicaciones/SinImagenDisponible.jpg";
//        $urlimagen = "fotosPublicaciones/SinImagenDisponible_con_texto.png";
    }
    return $urlimagen;
 }

public static function buscarItemEnPOST($clave, $POST){
	foreach ($POST as $clavePost=>$valor)
	{if ($clavePost === $clave)
        {
            return 1;
        }
    }
    return 0;
}
    
public static function buscarItemEnPOSTLista($clave, $POST){
    $items = explode("|",$POST);
    
    foreach($items as $item){
        $claveValor=explode(",",$item);
        if ($clave === $claveValor[0]){
            return 1;
        }
    }
    return 0;
}
    
public static function actualizarCP_POST($cp, $cod_localidad, $POST, $con){
	$log="N";
	$aux=false;
	
	foreach ($POST as $clave=>$valor)
	{
	if ($valor)
		{
		$prefijo=substr($clave,0,4);

		//Obtengo el numero de ubicacion en el formulario 09
		if (substr($prefijo,0,1) !== "v") {
			$nro_en_form=substr($clave,strpos($clave, "_")+1,2);
		} else {
			$nro_en_form=substr($clave,strpos($clave, "_")+10,2);			
		}

		//Solo proceso los componentes del formulario 09
		if ($nro_en_form == "09"){
			$insert = "insert into codigospostales (cp,campo,tipocampo,valor,cod_localidad) values ('".$cp."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$cod_localidad.")";
			//echo($insert.'<br>');
			$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
			}
		}
	}
	return $aux;
}
	
public static function obtenerCaminoLocalidad($locBuscar, $locBuscarCP, $porciento, $con){
    $codPais = $_SESSION['codPais'];
    global $locAgrupada;
    $i = 0;
    $pos_apos = strpos($locBuscar,"'");
    if ($porciento === "%"){
        $buscar = "'".$porciento.mb_strtolower($locBuscar,'UTF-8').$porciento."'";
        $buscar = str_replace(' ', $porciento, $buscar);
    } else {
        if ($pos_apos){
            $buscar = '"'.mb_strtolower($locBuscar,'UTF-8').'"';
        } else {
            $buscar = "'".mb_strtolower($locBuscar,'UTF-8')."'";
        }
    }
    
    if ($locBuscar <> 'LOC' && $locBuscar <> 'CP'){
        $locBuscar = mb_strtolower($locBuscar,'UTF-8');
    }
    
//    $buscar='"'.$buscar.'"';
//    echo ("locbuscar  : ".$locBuscar).'<br>';
//    echo ("locbuscarCP: ".$locBuscarCP).'<br>';
//    echo ("A buscar   : ".$buscar).'<br>';
//    echo ("porciento  : ".$porciento).'<br>';
//    return;
//    if (!is_numeric($locBuscar) && $locBuscar <> "CP"){
//    if (Funciones::esAlfabetico($locBuscar) && $locBuscar <> "CP" && $locBuscar <> "LOC"){
    if (Funciones::esAlfabetico(substr($locBuscar,0,5)) && $locBuscar <> "LOC" && $locBuscar <> "CP"){
        $camino = mysqli_query($con,"SELECT
            codpos.cp as 'CP',
            l.COD_LOCALIDAD,
            l.DESCRIPCION as 'LOC_DESCRIP',
            m.COD_MUNICIPIO,
            m.DESCRIPCION as 'MUN_DESCRIP',
            c.COD_COMARCA,
            c.DESCRIPCION as 'COM_DESCRIP',
            p.COD_PROVINCIA,
            p.DESCRIPCION as 'PRO_DESCRIP',
            r.COD_REGION,
            r.DESCRIPCION as 'REG_DESCRIP',
            pa.COD_NUM as 'COD_PAIS',
            pa.DESCRIPCION as 'PAI_DESCRIP'
            FROM localidades l
            JOIN codigospostales codpos
            ON codpos.cod_localidad = l.COD_LOCALIDAD
            JOIN municipiosxcomarca m
            ON m.COD_MUNICIPIO = l.COD_MUNICIPIO and l.DESCRIPCION like ".$buscar." and codpos.cp like '$locBuscarCP'
            JOIN comarcas c
            on c.COD_COMARCA = m.COD_COMARCA
            JOIN provincias p
            on p.COD_PROVINCIA = c.COD_PROVINCIA
            JOIN regiones r
            on r.COD_REGION = p.COD_REGION
            JOIN paises pa
            on pa.COD_NUM = r.COD_PAIS and pa.COD_NUM = $codPais
            GROUP by codpos.cp, l.COD_LOCALIDAD, l.DESCRIPCION, m.COD_MUNICIPIO, m.DESCRIPCION, c.COD_COMARCA, c.DESCRIPCION,
            p.COD_PROVINCIA, p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION
            order by p.descripcion, l.DESCRIPCION, codpos.cp");
            if ($camino) {
                $row_cnt = mysqli_num_rows($camino);
                if ($row_cnt > 0){
                    $i = 0;

//                    $clave_counts = [];
                    while ($row = mysqli_fetch_array($camino)){
                        $clave = $row['LOC_DESCRIP'];
                        if (!isset($clave_counts[$clave])) {
                            $clave_counts[$clave] = 0;
                        }
                        $clave_counts[$clave]++;
                    }
                    mysqli_data_seek($camino,0);
//                    var_dump($clave_counts);

                    $actual_clave = null;
                    
                    $locAnterior = "";
                    $_SESSION['locAgrupada'] = "";
                    $_SESSION['locAnterior'] = "";
                    
                    while ($row = mysqli_fetch_array($camino)){
                        if ($clave_counts[$row['LOC_DESCRIP']] > 1 && $row['LOC_DESCRIP'] !== $actual_clave && $_SESSION['operacion'] === 'B' ){
                            $actual_clave = $row['LOC_DESCRIP'];
                            $caminoLocalidad[$i]["COD_LOCALIDAD"]=$actual_clave;
                            $caminoLocalidad[$i]['CP']="";
                            $caminoLocalidad[$i]['PRO_DESCRIP']="Toda";
                            $caminoLocalidad[$i]['LOC_DESCRIP']="LOC";
                            $caminoLocalidad[$i]['CP']=$actual_clave;
                            $i++;
                        } elseif ($clave_counts[$clave] === 1) {
//                            $actual_clave = null;
                        }
                        $caminoLocalidad[$i] = $row;
                        $i++;
                    }
                    return $caminoLocalidad;
                } else {
                    $caminoSinCP = mysqli_query($con,"SELECT
                        l.COD_LOCALIDAD,
                        l.DESCRIPCION as 'LOC_DESCRIP',
                        m.COD_MUNICIPIO,
                        m.DESCRIPCION as 'MUN_DESCRIP',
                        c.COD_COMARCA,
                        c.DESCRIPCION as 'COM_DESCRIP',
                        p.COD_PROVINCIA,
                        p.DESCRIPCION as 'PRO_DESCRIP',
                        r.COD_REGION,
                        r.DESCRIPCION as 'REG_DESCRIP',
                        pa.COD_NUM as 'COD_PAIS',
                        pa.DESCRIPCION as 'PAI_DESCRIP'
                        FROM localidades l
                        JOIN municipiosxcomarca m
                        ON m.COD_MUNICIPIO = l.COD_MUNICIPIO and l.DESCRIPCION like ".$buscar." 
                        JOIN comarcas c
                        on c.COD_COMARCA = m.COD_COMARCA
                        JOIN provincias p
                        on p.COD_PROVINCIA = c.COD_PROVINCIA
                        JOIN regiones r
                        on r.COD_REGION = p.COD_REGION
                        JOIN paises pa
                        on pa.COD_NUM = r.COD_PAIS and pa.COD_NUM = $codPais
                        GROUP by pa.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, p.COD_PROVINCIA, p.DESCRIPCION,
                            c.COD_COMARCA, c.DESCRIPCION, m.COD_MUNICIPIO, m.DESCRIPCION, l.COD_LOCALIDAD, l.DESCRIPCION
                        order by l.DESCRIPCION");
                    if ($caminoSinCP) {
                        $row_cnt = mysqli_num_rows($caminoSinCP);
    //					echo "LineasSinCP: ".$row_cnt.'<br>';
                        if ($row_cnt > 0){

                            $locAnterior = "";
                            $locAgrupada = "";
                            $ultimo="";
                            while ($row = mysqli_fetch_array($caminoSinCP)){
                                if (($row['LOC_DESCRIP'] != $locAnterior)){
                                    $locAnterior = $row['LOC_DESCRIP'];
                                    if ($locAgrupada != "" && $_SESSION['operacion'] == 'B') {
                                        $caminoLocalidad[$i]["COD_LOCALIDAD"]="$locAgrupada";
                                        $caminoLocalidad[$i]['CP']="";
                                        $caminoLocalidad[$i]['PRO_DESCRIP']="Toda";
                                        $caminoLocalidad[$i]['LOC_DESCRIP']="LOC";
                                        $caminoLocalidad[$i]['CP']=$locAgrupada;
                                        $locAgrupada="";
                                    }
                                } else {
                                    $locAgrupada = $row['LOC_DESCRIP'];
                                }
                                $caminoLocalidad[$i] = $row;
                                $ultimo = $row;
                                $i++;
                            }
                            if ($locAgrupada == "" && $_SESSION['operacion'] == 'B') {
                                $caminoLocalidad[$i]["COD_LOCALIDAD"]="$locAgrupada";
                                $caminoLocalidad[$i]['CP']="";
                                $caminoLocalidad[$i]['PRO_DESCRIP']="Toda";
                                $caminoLocalidad[$i]['LOC_DESCRIP']="LOC";
                                $caminoLocalidad[$i]['CP']=$locAgrupada;
                                $locAgrupada="";
                                $caminoLocalidad[$i] = $ultimo;
                            }
                            return $caminoLocalidad;
                        } else {
                            return $caminoLocalidad="";
                        }
                    }
                }
            } else {
                return "Vacio";
            }
        } else {
            if ($locBuscar <> "LOC"){
                $camino = mysqli_query($con,"SELECT	
                        codpos.cp as 'CP',
                        l.COD_LOCALIDAD,
                        l.DESCRIPCION as 'LOC_DESCRIP',
                        m.COD_MUNICIPIO,
                        m.DESCRIPCION as 'MUN_DESCRIP',
                        c.COD_COMARCA,
                        c.DESCRIPCION as 'COM_DESCRIP',
                        p.COD_PROVINCIA,
                        p.DESCRIPCION as 'PRO_DESCRIP',
                        r.COD_REGION,
                        r.DESCRIPCION as 'REG_DESCRIP',
                        pa.COD_NUM as 'COD_PAIS',
                        pa.DESCRIPCION as 'PAI_DESCRIP'
                        FROM localidades l
                        JOIN codigospostales codpos
                        ON codpos.cod_localidad = l.COD_LOCALIDAD
                        JOIN municipiosxcomarca m
                        ON m.COD_MUNICIPIO = l.COD_MUNICIPIO and codpos.cp = '$locBuscar' and codpos.campo='valor_checkbox_09_32'
                        JOIN comarcas c
                        on c.COD_COMARCA = m.COD_COMARCA
                        JOIN provincias p
                        on p.COD_PROVINCIA = c.COD_PROVINCIA
                        JOIN regiones r
                        on r.COD_REGION = p.COD_REGION
                        JOIN paises pa
                        on pa.COD_NUM = r.COD_PAIS and pa.COD_NUM = $codPais
                        GROUP by l.COD_LOCALIDAD, l.DESCRIPCION, m.COD_MUNICIPIO, m.DESCRIPCION, c.COD_COMARCA, c.DESCRIPCION,
                             p.COD_PROVINCIA, p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION
                        order by l.DESCRIPCION");
                if ($camino) {
                    $row_cnt = mysqli_num_rows($camino);
                    if ($row_cnt > 0){
                        $i=0;
                        if ($_SESSION['operacion'] == 'B'){
                            $caminoLocalidad[$i]["COD_LOCALIDAD"]="";
//                            $caminoLocalidad[$i]['CP']=$locBuscar;
                            $caminoLocalidad[$i]['CP']=strtoupper( $locBuscar );
                            $caminoLocalidad[$i]['PRO_DESCRIP']="Todo";
                            $caminoLocalidad[$i]['LOC_DESCRIP']="CP";
//                            $caminoLocalidad[$i]['CP']=$locBuscar;
                            $caminoLocalidad[$i]['CP']=strtoupper( $locBuscar );
                            $i++;
                        }
                        while ($row = mysqli_fetch_array($camino)){
                            $caminoLocalidad[$i] = $row;
                            $i++;
                                }
//                      var_dump($caminoLocalidad[1]).'<br>';
                        return $caminoLocalidad;
                    } else {
                        return $caminoLocalidad="";
                    }
                }
//            }
    } else {
            $locBuscar=$locBuscarCP;
            $locBuscarCP="";
            $buscar = "'".mb_strtolower($locBuscar,'UTF-8')."'";
//            echo "<br>buscar: ".$buscar.'<br>';
//            return;
            $caminoSinCP = mysqli_query($con,"SELECT	
                    codpos.cp as 'CP',
                    l.COD_LOCALIDAD,
                    l.DESCRIPCION as 'LOC_DESCRIP',
                    m.COD_MUNICIPIO,
                    m.DESCRIPCION as 'MUN_DESCRIP',
                    c.COD_COMARCA,
                    c.DESCRIPCION as 'COM_DESCRIP',
                    p.COD_PROVINCIA,
                    p.DESCRIPCION as 'PRO_DESCRIP',
                    r.COD_REGION,
                    r.DESCRIPCION as 'REG_DESCRIP',
                    pa.COD_NUM as 'COD_PAIS',
                    pa.DESCRIPCION as 'PAI_DESCRIP'
                    FROM localidades l
                    JOIN codigospostales codpos
                    ON codpos.cod_localidad = l.COD_LOCALIDAD and l.DESCRIPCION like $buscar
                    JOIN municipiosxcomarca m
                    ON m.COD_MUNICIPIO = l.COD_MUNICIPIO
                    JOIN comarcas c
                    on c.COD_COMARCA = m.COD_COMARCA
                    JOIN provincias p
                    on p.COD_PROVINCIA = c.COD_PROVINCIA
                    JOIN regiones r
                    on r.COD_REGION = p.COD_REGION
                    JOIN paises pa
                    on pa.COD_NUM = r.COD_PAIS and pa.COD_NUM = $codPais
                    GROUP by l.COD_LOCALIDAD, l.DESCRIPCION, m.COD_MUNICIPIO, m.DESCRIPCION, c.COD_COMARCA, c.DESCRIPCION,
                             p.COD_PROVINCIA, p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION
                    order by l.DESCRIPCION");
            if ($caminoSinCP) {
                $row_cnt = mysqli_num_rows($caminoSinCP);
//					echo "LineasSinCP: ".$row_cnt.'<br>';
                if ($row_cnt > 0){
                    $i=0;
                    $caminoLocalidad[$i] = "-1";
//                    echo ("Primer registro: ".$caminoLocalidad[$i].", i: ".$i."<br>");
                    $i=1;

                    while ($row = mysqli_fetch_array($caminoSinCP)){
                        $caminoLocalidad[$i] = $row;
//                        echo ("Loc: ".$caminoLocalidad[$i]['LOC_DESCRIP'].", i: ".$i."<br>");
//                        var_dump( $caminoLocalidad[$i] );
                        $i++;
                            }
                    return $caminoLocalidad;
                } else {
                    return $caminoLocalidad="";
                }
            }
        }
    }
}
    
public static function eliminar_acentos($cadena){
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Ã', 'Ã€', 'Ã‚', 'Ã„', 'Ã¡', 'Ã ', 'Ã¤', 'Ã¢', 'Âª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('Ã‰', 'Ãˆ', 'ÃŠ', 'Ã‹', 'Ã©', 'Ã¨', 'Ã«', 'Ãª'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Ã', 'ÃŒ', 'Ã', 'ÃŽ', 'Ã­', 'Ã¬', 'Ã¯', 'Ã®'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ã“', 'Ã’', 'Ã–', 'Ã”', 'Ã³', 'Ã²', 'Ã¶', 'Ã´'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ãš', 'Ã™', 'Ã›', 'Ãœ', 'Ãº', 'Ã¹', 'Ã¼', 'Ã»'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ã‘', 'Ã±', 'Ã‡', 'Ã§'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
    
    public static function borrarAnuncio($nroAnuncio, $con){
        $result = $con->query("delete FROM datosformulario where nroanuncio='$nroAnuncio'");
        return $result;
    }
    
    public static function borrarAnuncioReservado($nroAnuncio, $con){
        $result = $con->query("delete FROM datosbusquedasformulario where nroanuncio = ".$nroAnuncio." and cod_pais = 0");
        return true;
    }

public static function obtenerContenidoCP($cp_code, $localidad, $con){
	$result = $con->query("SELECT * FROM codigospostales where cp='$cp_code' and cod_localidad='$localidad' order by id");
	$lineaCP = '';
    $coma="";
    
	while ($row = mysqli_fetch_array($result)){
		$lineaCP .= $coma.$row['valor'];
        if (empty($coma)){
            $coma=", ";
        }
    }
	return $lineaCP;
}

public static function obtenerContenidoAnuncio($anuncioCode, $con){
	$result = $con->query("SELECT * FROM datosformulario where nroanuncio='$anuncioCode'");
	$lineaAnuncio = '';
    $coma="";
    
	while ($row = mysqli_fetch_array($result)){
        if ($row['tipocampo'] <> 'v'){
            $lineaAnuncio .= $coma.$row['valor'];
        } else {

            if ($row['valor' <> ""]){
//                $alertStr="<script>alert('Valor: ".$row['valor']."');</script>";
//                echo $alertStr;
                if ($row['campo'] <> "valor_checkbox_09_32"){
                    $lineaAnuncio .= $coma.number_format($row['valor'],0,',','.');
                } else {
                    $lineaAnuncio .= $coma.$row['valor'];
                }
            } else {
                $lineaAnuncio .= $row['valor'];
            }
        }
        if (empty($coma)){
            $coma=", ";
        }        
	}
	return $lineaAnuncio;
}

public static function obtenerContenidoBusqueda($busquedaCode, $con){
	$result = $con->query("SELECT * FROM datosbusquedas where nrobusqueda=$busquedaCode");
	$lineaAnuncio = '';
    $coma="";
    
	while ($row = mysqli_fetch_array($result)){
        if ($row['tipocampo'] <> 'v'/* && $row['tipocampo'] <> 'u'*/){
            $lineaAnuncio .= $coma."<span data-trad='".$row['valor']."'>'".$row['valor']."'</span>";
        } else {
            if ($row['valor'] <> ""){
                if ($row['campo'] <> "valor_checkbox_09_32"){
                    $lineaAnuncio .= $coma.number_format($row['valor'],0,',','.');
                } else {
                    $lineaAnuncio .= $coma.$row['valor'];
                }
            }
        }
        if (empty($coma)){
            $coma=", ";
        }        
	}
	return $lineaAnuncio;
}

public static function obtenerContenidoAnuncioReducido($datos,$anuncioCode, $con){
	$operacion="radio_01_0";
	$propiedad="radio_02_0";
	$valor="valor_checkbox_02A_precio";
	$moneda="checkbox_02A_moneda";
    $oculto="checkbox_02A_precio_oculto";
	$calle="ubicd_calle_y_numero_id";
	$numero="ubicd_numero_id";
	$localidad="ubic_localidad_id";
    $lineaAnuncio = "";
    $coma="";

    $lineaAnuncio  = Funciones::obtenerValorDeItemEnFormulario($operacion,$datos).",";    
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($propiedad,$datos).",";    
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($valor,$datos).",";    
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($moneda,$datos).",";    

    $aux=Funciones::obtenerValorDeItemEnFormulario($oculto,$datos);
    if ($aux <> ""){
        $lineaAnuncio .= $aux."," ;
    }
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($calle,$datos).",";    
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($numero,$datos).",";
    $lineaAnuncio .= Funciones::obtenerValorDeItemEnFormulario($localidad,$datos);

	return $lineaAnuncio;
}

public static function ejecutarBusquedaAnuncios($select, $con){
	$encontrados=$con->query($select);
	$datos [] = '';
	$i=0;
	while($row = mysqli_fetch_array($encontrados)){
		$datos[$i]=$row;
		$i++;
	}
	return $datos;
}
	
public static function cargarDatosAnuncio($idAnuncio, $con){
	$nroAnuncio=$_SESSION['anuncio'];
	$result = $con->query("SELECT * FROM datosformulario where nroanuncio='$nroAnuncio'");
	$datos [] = "";
	$i=0;
	
	if (!isset($_SESSION['busquedaPublica'])){
		while($row = mysqli_fetch_array($result)){
			if ($row['nroanunciante'] == $_SESSION['anunciante']){
				$datos[$i]=$row;
				$i++;
			} else {
				return $datos;
			}
		}
		return $datos;
	} else {
		while($row = mysqli_fetch_array($result)){
			$datos[$i]=$row;
			$i++;
		}
		return $datos;		
	}
}

public static function cargarDatosCP($cp, $loccp, $con){
    $select="SELECT * FROM codigospostales where cp='$cp' and cod_localidad = '$loccp'";
	$result = $con->query("SELECT * FROM codigospostales where cp='$cp' and cod_localidad = '$loccp'");
//	$result = $con->query("SELECT * FROM codigospostales where cp='$cp'");
	$datos [] = "";
	$i=0;
	
	while($row = mysqli_fetch_array($result)){
		$datos[$i]=$row;
//        error_log("Dato CP: ".print_r($datos[$i]).", i: ".$i,0);
		$i++;
	}
	return $datos;		
}

public static function buscarDatoDeAnuncio($datos, $valor, $decimales, $cpoBuscar){
	$tope = count($datos);
	if ($cpoBuscar == "V"){
		for ($i = 0; $i < $tope; ++$i){
	//		echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valor']." Tipo: ".$datos[$i]['tipocampo']."<br>");
			if ($datos[$i]['valor'] == $valor){
                if ($datos[$i]['tipocampo'] == 't') {
//                    echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valor']." Tipo: ".$datos[$i]['tipocampo']."<br>");
                    $respuesta['valor']=$datos[$i]['valor'];
                } else {
                    if ($datos[$i]['tipocampo'] == 'v'){
                        if ($datos[$i]['campo'] <> 'valor_checkbox_09_32'){
                            if ($decimales <> 0){
                                $respuesta['valor'] = number_format($datos[$i]['valor'],$decimales,',','.');
                            } else {
                                $respuesta['valor']=$datos[$i]['valor'];
                            }
                        } else {
                            $respuesta['valor']=$datos[$i]['valor'];
                        }
                    } else {
                        $respuesta['valor']=$datos[$i]['valor'];
                    }
                }
//                $respuesta['valor']=$datos[$i]['valor'];
				$respuesta['campo']=$datos[$i]['campo'];
				$respuesta['tipocampo']=$datos[$i]['tipocampo'];
				return $respuesta;
			}
		}
		return false;
		}else{
			for ($i = 0; $i < $tope; ++$i){
//				echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valor']." Tipo: ".$datos[$i]['tipocampo']."<br>");
				if ($datos[$i]['campo'] == $valor){
                    if ($datos[$i]['tipocampo'] == 't') {
//                        echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valortxtarea']." Tipo: ".$datos[$i]['tipocampo']."<br>");
                        $respuesta['valor']=$datos[$i]['valor'];
                    } else {
                    if ($datos[$i]['tipocampo'] == 'v'){
                        if ($datos[$i]['campo'] <> 'valor_checkbox_09_32'){
                            $respuesta['valor']=number_format($datos[$i]['valor'],$decimales,',','.');
                        } else {
                            $respuesta['valor']=$datos[$i]['valor'];
                        }
                    } else {
                        $respuesta['valor']=$datos[$i]['valor'];
                    }
                }
					$respuesta['campo']=$datos[$i]['campo'];
					$respuesta['tipocampo']=$datos[$i]['tipocampo'];
					$respuesta['codigoubicacion']=$datos[$i]['codigoubicacion'];
					return $respuesta;
					}
				}
			return false;
			}
		}

public static function buscarDatoDeCP($datos, $valor, $cpoBuscar){
	$tope = count($datos);
	if ($tope == 1){
		return false;
	}
    $respuesta = [];
	if ($cpoBuscar === "V"){
		for ($i = 0; $i < $tope; ++$i){
	//		echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valor']." Tipo: ".$datos[$i]['tipocampo']."<br>");
			if ($datos[$i]['valor'] === $valor){
				$respuesta['valor']=$datos[$i]['valor'];
				$respuesta['campo']=$datos[$i]['campo'];
				$respuesta['tipocampo']=$datos[$i]['tipocampo'];
				return $respuesta;
			}
		}
		return false;
		}else{
			for ($i = 0; $i < $tope; ++$i){
		//		echo ("Campo: ".$datos[$i]['campo']." Valor: ".$datos[$i]['valor']." Tipo: ".$datos[$i]['tipocampo']."<br>");
				if ($datos[$i]['campo'] === $valor){
					$respuesta['valor']=$datos[$i]['valor'];
					$respuesta['campo']=$datos[$i]['campo'];
					$respuesta['tipocampo']=$datos[$i]['tipocampo'];
					return $respuesta;
					}
				}
			return false;
			}
		}

	public static function obtenerVentaAlquiler($datos){
	$tope = count($datos);
	for ($i = 0; $i < $tope; ++$i){
		if ($datos[$i]['campo'] == "radio_01_0"){
			$respuesta=$datos[$i]['valor'];
			return $respuesta;
			}
		}
	return false;
	}

	public static function obtenerTipoPropiedad($datos){
	$tope = count($datos);
	for ($i = 0; $i < $tope; ++$i){
		if ($datos[$i]['campo'] == "radio_02_0"){
			$respuesta=$datos[$i]['valor'];
			return $respuesta;
			}
		}
	return false;
	}

	public static function cargarUbicacionesSelect($tabla,$indice,$origen,$clave,$seleccionado, $con){
		$html='';

        if (!is_numeric($clave)){
            echo $html;
            return;
        }
		
        $result = $con->query(
			"SELECT * FROM $tabla WHERE $indice = '".$clave."' ORDER BY DESCRIPCION ASC");
		if ($result->num_rows > 0) {
			$html ="<option value=''>Elija</option>";
			while ($row = $result->fetch_assoc()) {
				$descripcion = $row['DESCRIPCION'];
				$selected="";
                
				if ($row['DESCRIPCION'] == "NULL") {
					$descripcion = "N/Aplica";
					$selected=" selected";
				}else{
					if ($descripcion == $seleccionado){
						$selected=" selected";
					}
				}
				$html .= '<option value="'.$row[$origen].'"'.$selected.">".$descripcion.'</option>';
				if ($tabla == "localidades" && !empty($selected)){
					$_SESSION['cod_localidad']=$row[$origen];
//					echo("LocalidadSel: ".$_SESSION['cod_localidad']);
				}
			}
		}
		echo $html;
		}
	
		public static function cargarUbicacionesSelectCP($tabla,$indice,$origen,$clave,$seleccionado, $con){
			$html='';

			$result = $con->query(
				"SELECT * FROM $tabla WHERE $indice = ".$clave." ORDER BY DESCRIPCION ASC");
			if ($result->num_rows > 0) {
				$html ="<option value=''>Elija</option>";
				while ($row = $result->fetch_assoc()) {
					$descripcion = $row['DESCRIPCION'];
					$selected="";
					if ($row['DESCRIPCION'] == "NULL") {
						$descripcion = "N/Aplica";
						$selected=" selected";
					}else{
//						if ($descripcion == $seleccionado && $tabla <> "localidades"){
						if ($descripcion == $seleccionado){
							$selected=" selected";
						}
					}
					$html .= '<option value="'.$row[$origen].'"'.$selected.">".$descripcion.'</option>';
					if ($tabla == "localidades" && !empty($selected)){
						$_SESSION['cod_localidad']=$row[$origen];
//						echo("LocalidadCP: ".$_SESSION['cod_localidad']);
					}
				}
			}
			return $html;
			}

    public static function actualizarAnuncioBD($sql, $log, $con){
    //	$result = mysqli_query($con,$sql);
        $result = $con->query($sql);

        if (!$result) {
            echo (mysqli_error($con));
            echo ("Error... $sql"."<br>");
            return false;
            } else {
            if ($log == "S"){
                echo ("OK... $sql"."<br>");
            }
            return true;
            }
        }


    public static function actualizarFichaBD($sql, $log, $con){
    //	$result = mysqli_query($con,$sql);
        $result = $con->query($sql);

        if (!$result) {
            echo (mysqli_error($con));
            echo ("Error... $sql"."<br>");
            return false;
            } else {
            if ($log == "S"){
                echo ("OK... $sql"."<br>");
            }
            return true;
            }
        }

public static function obtenerNombreAnunciante($llamador,$anunciante, $con){
    $sql="select nombrerz, contactonombre from fichas where anunciante = $anunciante";
	$ficha = mysqli_query($con,$sql);

	if ($ficha->num_rows > 0) {
        $ficha = mysqli_query($con,$sql);
		$anuncianteFicha = $ficha->fetch_assoc();
        if ($llamador === "C"){
            if ($_SESSION['clientetipo'] === 'P'){
                return "DUEÑO";
            } else {
                if ($anuncianteFicha['contactonombre'] <> ""){
                    return $anuncianteFicha['contactonombre'];
                } else {
                    return $anuncianteFicha['nombrerz'];
                }
            }
        } else {
            return $anuncianteFicha['nombrerz'];
        }
	} else {
        return false;
    }
}
	
public static function obtenerTelefonoAnunciante($anunciante, $con){
    $sql="select telfijo, telmovil from fichas where anunciante = $anunciante";
	$ficha = mysqli_query($con,$sql);

	if ($ficha->num_rows == 1) {
        $ficha = mysqli_query($con,$sql);
		$anuncianteFicha = $ficha->fetch_assoc();
        if ($anuncianteFicha['telfijo'] <> ""){
            return $anuncianteFicha['telfijo'];
        }
        if ($anuncianteFicha['telmovil'] <> ""){
            return $anuncianteFicha['telmovil'];
        }
		} else {
        return $anunciante;
        }
	}
	
public static function obtenerEmailAnunciante($anunciante, $con){
    $sql="select emaillaboral from fichas where anunciante = $anunciante";
	$ficha = mysqli_query($con,$sql);

	if ($ficha->num_rows == 1) {
        $ficha = mysqli_query($con,$sql);
		$anuncianteFicha = $ficha->fetch_assoc();
        return $anuncianteFicha['emaillaboral'];
		} else {
        return "No pude obtener el E-Mail del anunciante.";
        }
	}
	
public static function obtenerNroAnuncio($con){
//	$result = mysqli_query($con,$sql);
	return Funciones::obtenerNumerador('ANUNCIO',$con);
/*
	$sqlMax = "select max(nroanuncio) as maxanuncio from datosbusquedasformulario";
	$maxNroAnuncio = mysqli_query($con,$sqlMax);
	if ($maxNroAnuncio->num_rows == 1) {
		$maxNroAnuncioStr = $maxNroAnuncio->fetch_assoc();
		++$maxNroAnuncioStr['maxanuncio'];

        $insert = "insert into datosbusquedasformulario (nroanuncio) values (".$maxNroAnuncioStr['maxanuncio'].")";
        $aux=Funciones::actualizarAnuncioBD($insert, "N", $con);

		return $maxNroAnuncioStr['maxanuncio'];
		}
*/
	}

public static function obtenerNroBusqueda($con){
	$maxNroBusquedaStr=0;
	$sqlMax = "select max(nrobusqueda) as maxbusqueda from busquedasguardadas";
	$maxNroBusqueda = mysqli_query($con,$sqlMax);
//	if ($maxNroBusqueda->num_rows == 1) {
		$maxNroBusquedaStr = $maxNroBusqueda->fetch_assoc();
        if ($maxNroBusquedaStr['maxbusqueda'] === ""){$maxNroBusquedaStr['maxbusqueda']=0;}
        ++$maxNroBusquedaStr['maxbusqueda'];
		return $maxNroBusquedaStr['maxbusqueda'];
//		}
	}

public static function actualizarBusquedaPlana($nroanuncio_par, $con){
    $log="N";
    $select="select * from datosformulario where nroanuncio = $nroanuncio_par order by nroanuncio, id";
    $and="";
    $aux=false;
    $query = mysqli_query($con,$select);
    $nroanuncioant=0;

    $cod_pais=0;
    $cod_region=0;
    $cod_provincia=0;
    $cod_comarca=0;
    $cod_municipio=0;
    $cod_localidad=0;
    $cod_barrio=0;
    $cod_nucleo=0;
    $cod_manzana=0;

    $datosplanosanuncio='';
    $cp='';
    $nroanuncioant='';
    $nroanuncianteNT='';

    while($row = mysqli_fetch_array($query)){
        if ($row['nroanuncio'] <> $nroanuncioant){
            if ($nroanuncioant <> ''){
                // Grabar en nueva tabla...
/*              echo 'ANUNCIO   : '.$nroanuncioant.'<br>';
                echo 'PAIS      : '.$cod_pais.'<br>';
                echo 'LOCALIDAD : '.$cod_localidad.'<br>';
                echo 'CP        : '.$cp.'<br>';
                echo 'ANUNCIANTE: '.$nroanuncianteNT.'<br>';
                echo 'DATOS     : '.$datosplanosanuncio.'<br>'.'<br>';
*/              $delete = "delete from datosbusquedasformulario where nroanuncio = $nroanuncioant";
                $insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_localidad,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados,estado) values (".$nroanuncioant.",".$cod_pais.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_localidad.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.", '".$cp."',".$nroanuncianteNT.",".'"'.$datosplanosanuncio.'"'.",'1')";
                $aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
                $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);

                $datosplanosanuncio='';
                $cod_pais=0;
                $cod_region=0;
                $cod_provincia=0;
                $cod_comarca=0;
                $cod_municipio=0;
                $cod_localidad=0;
                $cod_barrio=0;
                $cod_nucleo=0;
                $cod_manzana=0;
                $cp='';
                $nroanuncioant=$row['nroanuncio'];
                $nroanuncianteNT=$row['nroanunciante'];
            } else {
                $nroanuncioant=$row['nroanuncio'];
                $nroanuncianteNT=$row['nroanunciante'];
    //            echo $row['nroanuncio'].' '.$row['campo'].' '.$row['valor'].' '.$row['codigoubicacion'].'<br>';

                $prefijo=substr($row['campo'],0,4);

                if (substr($row['campo'],0,5) === 'ubic_'){
                    if ($row['campo'] === 'ubic_pais_id'){
                        $cod_pais = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_region_id'){
                        $cod_region = $row['codigoubicacion'];
                    }
                    
                    if ($row['campo'] === 'ubic_provincia_id'){
                        $cod_provincia = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_comarca_id'){
                        $cod_comarca = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_municipio_id'){
                        $cod_municipio = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_localidad_id'){
                        $cod_localidad = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_barrio_id'){
                        $cod_barrio = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_nucleo_id'){
                        $cod_nucleo = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_manzana_id'){
                        $cod_manzana = $row['codigoubicacion'];
                    }

                    $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor'].",".$row['codigoubicacion']."|";
                } else {
                    $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor']."|";
                }
            }
        } else {
            $prefijo=substr($row['campo'],0,4);

            if (substr($row['campo'],0,5) === 'ubic_'){
                    if ($row['campo'] === 'ubic_pais_id'){
                        $cod_pais = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_region_id'){
                        $cod_region = $row['codigoubicacion'];
                    }
                    
                    if ($row['campo'] === 'ubic_provincia_id'){
                        $cod_provincia = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_comarca_id'){
                        $cod_comarca = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_municipio_id'){
                        $cod_municipio = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_localidad_id'){
                        $cod_localidad = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_barrio_id'){
                        $cod_barrio = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_nucleo_id'){
                        $cod_nucleo = $row['codigoubicacion'];
                    }

                    if ($row['campo'] === 'ubic_manzana_id'){
                        $cod_manzana = $row['codigoubicacion'];
                    }

                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor'].",".$row['codigoubicacion']."|";
            } else {
                if ($row['campo'] === 'valor_checkbox_09_32'){
                    $cp = $row['valor'];
                }
                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor']."|";
            }
//            echo $row['nroanuncio'].' '.$row['campo'].' '.$row['valor'].' '.$row['codigoubicacion'].'<br>';
        }
    }
    // Grabar en nueva tabla...
/*  echo 'ANUNCIO   : '.$nroanuncioant.'<br>';
    echo 'PAIS      : '.$cod_pais.'<br>';
    echo 'LOCALIDAD : '.$cod_localidad.'<br>';
    echo 'CP        : '.$cp.'<br>';
    echo 'ANUNCIANTE: '.$nroanuncianteNT.'<br>';
    echo 'DATOS     : '.$datosplanosanuncio.'<br>';
*/
    $delete = "delete from datosbusquedasformulario where nroanuncio = $nroanuncioant";
    $insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_localidad,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados,estado) values (".$nroanuncioant.",".$cod_pais.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_localidad.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.", '".$cp."',".$nroanuncianteNT.",".'"'.$datosplanosanuncio.'"'.",'1')";
    //echo 'Delete: '.$delete.'<br>';
    //echo 'Insert: '.$insert.'<br>';
    $aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
    $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
}

public static function esAlfabetico($texto){
   //compruebo que los caracteres sean los permitidos
//   $permitidos = "0123456789AaÃ¡ÃÃ Ã€Ã¢Ã‚Ã¤Ã„Ã¥Ã…Ã¦Ã†BbCcÃ§DdEeÃ©Ã‰Ã¨ÃªÃ«FfGgHhIiÃ­ÃÃ¬Ã®ÃŽÃ¯ÃJjKkLlMmNnÃ±Ã‘OoÃ³Ã²Ã´Ã¶Ã–Ã¸PpQqRrSsTtUuÃºÃ¹Ã»Ã¼ÃœVvWwXxYyÃ¿Zz ";
//   $permitidos = "AaÃ¡ÃÃ Ã€Ã¢Ã‚Ã¤Ã„Ã¥Ã…Ã¦Ã†BbCcÃ§DdEeÃ©Ã‰Ã¨ÃªÃ«FfGgHhIiÃ­ÃÃ¬Ã®ÃŽÃ¯ÃJjKkLlMmNnÃ±Ã‘OoÃ³Ã²Ã´Ã¶Ã–Ã¸PpQqRrSsTtUuÃºÃ¹Ã»Ã¼ÃœVvWwXxYyÃ¿Zz()-.'Â´ ";
   $permitidos = "AaÃ¡ÃÃ Ã€Ã¢Ã‚Ã¤Ã„Ã¥Ã…Ã¦Ã†BbCcÃ§DdEeÃ©Ã‰Ã¨ÃªÃ«FfGgHhIiÃ­ÃÃ¬Ã®ÃŽÃ¯ÃJjKkLlMmNnÃ±Ã‘OoÃ³Ã²Ã´Ã¶Ã–Ã¸PpQqRrSsTtUuÃºÃ¹Ã»Ã¼ÃœVvWwXxYyÃ¿Zz()-.ÃˆÃ€ÃŒÃ’Ã„Ã‹Ã„Ã‹Ã' ";
   for ($i=0; $i < strlen($texto); $i++){
      if (strpos($permitidos, substr($texto,$i,1)) === false){
         return false;
      }
   }
   return true;
   }

public static function esAlfaNumerico($texto){
   $permitidos = "ABCDEFGHIJKLMNÃ‘OPQRSTUVWXYZabcdefghijklmnÃ±opqrstuvwxyz1234567890";
   for ($i=0; $i < strlen($texto); $i++){
      if (strpos($permitidos, substr($texto,$i,1)) === false){
         return false;
      }
   }
   return true;
   }

public static function get_browser_name($user_agent)
    {
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
    }
}
?>
