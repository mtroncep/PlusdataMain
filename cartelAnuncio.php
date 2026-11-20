<!DOCTYPE html>
<?php

session_start();

require_once "conexion.php";
require_once("cargarDatosFormulario.php");

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


$actualizar=1;
$anuncianteIndividual=0;

setlocale (LC_TIME, "es_ES");
$sql="SELECT * from fichas where anunciante = ".$_SESSION['anunciante'];

$resultado = $con->query($sql);

$anuncianteCorriente = $_SESSION['anunciante'];
    
$pdf = new PDF("L", "mm", "A4");
$pdf->AddPage();

$fila = $resultado->fetch_assoc();
    $pdf->SetMargins(20, 20, 20);

    $nombre = Funciones::obtenerNombreAnunciante("C",$fila['anunciante'], $con);
    $pdf->CentrarTexto(utf8_decode($nombre), 'Times', 90, 35);

    $operacionInmob = mb_strtoupper(Funciones::buscarOperacionInmob($con, $_SESSION['anuncio']));
    $pdf->CentrarTexto(utf8_decode($operacionInmob), 'Arial', 230, 90);

    $nroAnuncio = Funciones::formato_numero($_SESSION['anuncio'], 8, 4);
    $pdf->CentrarTexto(utf8_decode("Anuncio N°: ".$nroAnuncio), 'Arial', 75, 140);    

    $pdf->CentrarTexto('www.plusdata.info', 'Times', 95, 170);    

    $file_name = "Letrero_".$anuncianteCorriente."_".$_SESSION['anuncio']."_".date('d-m-Y').".pdf";
	if (gethostname() == "DESKTOP-6LCGB06"){
        // Entorno local
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/HMIPrueba/informes/letreros/" . $file_name;
    } else {
        // Entorno de producción
        $file_path = "informes/letreros/" . $file_name;
        error_log("Letrero generado: ".$file_path);
    }
    $letrero = "Letrero_".$anuncianteCorriente."_".$_SESSION['anuncio']."_".date('d-m-Y').".pdf";
//    $archivoCreado="informes/letreros/Letrero_".$anuncianteCorriente."_".$_SESSION['anuncio']."_".date('d-m-Y').".pdf";
    $pdf->Output($file_path, 'F');
//    header('content-type: application/pdf');
//    readfile($archivoCreado);
//    unlink($archivoCreado);
    $to=Funciones::obtenerEmailAnunciante($_SESSION['anunciante'], $con);
    $envioLetrero = Funciones::enviarMailLetrero($to, $letrero);
    echo ('<script> window.location.href = "indexAnuncios.php"; </script>');
?>
