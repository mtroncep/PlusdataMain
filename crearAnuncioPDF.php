<!DOCTYPE html>
<?php

session_start();

if (!isset($_SESSION['codPais'])){
    echo ("<script>alert('Falta seleccionar el país.');</script>");
    echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
}

require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

setlocale (LC_TIME, "es_ES");

$contenido = $_SESSION['contenido'];
//var_dump($contenido);
//return;

$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();

$encabezado=0;

$pdf->SetY(0);
$pdf->SetX(0);
$pdf->Cell(10,10, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 20, 'png', $_SESSION['menuinicio']));

$pdf->SetY(5);
$pdf->SetX(20);
$pdf->Cell(60,50, $pdf->Image($contenido[0],50,30, 100, 70));

$pdf->SetFont("Arial", "", 9);
$pdf->SetY(130);
$pdf->SetX(40);
$contenidoTexto=str_replace("</b><br><b>"," ",$contenido[1]);
$contenidoTexto=str_replace("<br><b>"," ",$contenidoTexto);
$contenidoTexto=str_replace("<b>"," ",$contenidoTexto);
$contenidoTexto=str_replace("<br>"," ",$contenidoTexto);
$contenidoTexto=str_replace("</b>"," ",$contenidoTexto);
$pdf->MultiCell(80, 10, utf8_decode( $contenidoTexto ), 0, "L");

$archivoCreado="informes/avisosPDF/Aviso_PDF_Marcelo"."_".date('d-m-Y')."_".date("H-i-s").".pdf";
$pdf->Output($archivoCreado,'F');

return;

while($fila = $resultado->fetch_assoc()){
    if ($encabezado == 0){
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 20, 'png', $_SESSION['menuinicio']));
        $pdf->SetY(19);
        $pdf->SetX(80);
        $pdf->SetFont("Arial", "B", 20);
        $pdf->Cell(80, 5, Funciones::obtenerNombreAnunciante($fila['nroanunciante'], $con), 0, 1, "L");
        $pdf->SetFont("Arial", "B", 12);
/*
        $pdf->SetFont("Arial", "B", 24);
        $pdf->Cell(60, 5, strftime("%B %Y"), 0, 1, "C");
        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetY(25);
*/
        $pdf->Ln(2);
        $pdf->Line(10,$pdf->GetY(),195,$pdf->GetY());
        $pdf->Ln(2);
//        $pdf->Cell(150, 5, "", 0, 1, "C");
        $pdf->SetX(20);
        $pdf->SetFont("Arial", "B", 15);
        $pdf->Cell(180, 5, "INFORME al ".Funciones::fecha_AMD_to_DMA(date('d-m-Y')), 0, 1, "C");
//        $pdf->Cell(50, 5, Funciones::fecha_AMD_to_DMA(date('d-m-Y')), 0, 1, "L");
        $pdf->Ln(1);
        $pdf->SetFont("Arial", "B", 13);
        $pdf->Cell(30, 5, "CARTERA DE ANUNCIOS", 0, 1, "L");
        $pdf->Ln(1);
/*
        $pdf->Cell(10, 5, "Cliente: ", 0, 0, "R");
        $pdf->SetFont("Arial", "U", 10);
        $pdf->Cell(20, 5, Funciones::formato_numero($fila['nroanunciante'], 6, 3), 0, 0, "L");
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(80);
        $pdf->Cell(20, 5, "Nombre: ", 0, 0, "R");
        $pdf->SetFont("Arial", "U", 10);
        $pdf->Cell(40, 5, Funciones::obtenerNombreAnunciante($fila['nroanunciante'], $con), 0, 1, "L");
        $pdf->SetFont("Arial", "B", 9);
        */
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Ln(2);
        $pdf->Cell(15, 5, iconv('utf-8', 'cp1252', html_entity_decode("País")), 1, 0, "C");
        $pdf->Cell(55, 5, "Anunciante", 1, 0, "C");
        $pdf->Cell(20, 5, "Anuncio", 1, 0, "C");
        $pdf->Cell(25, 5, "Fecha Alta", 1, 0, "C");
        $pdf->Cell(25, 5, "Fecha Baja", 1, 0, "C");
        $pdf->Cell(10, 5, "Dias", 1, 0, "C");
        $pdf->Cell(35, 5, strtoupper(strftime("%B")), 1, 1, "C");
        $pdf->SetFont("Arial", "", 9);
        $encabezado=1;
        $totMes=0;
        $saldoInicialAvisos=Funciones::obtenerRestoAvisos($con,$fila['nroanunciante']);
        $paisCorriente=$fila['cod_pais'];
    }

    if ($fila['nroanuncianteOfi'] <> 0){
        if (Funciones::mostrarTipoCliente($con,$fila['nroanuncianteOfi']) == 'Sucursal'){
            $auxanunciante=$fila['nroanunciante'];
            $fila['nroanunciante']=$fila['nroanuncianteOfi'];
            $fila['nroanuncianteOfi']=$auxanunciante;
        }
    }
    
    if ($anuncianteAnt <> $fila['nroanunciante'] || $paisAnt <> $fila['cod_pais'] ){
        $aux = $anuncianteAnt;
        $anuncianteAnt = $fila['nroanunciante'];
        
        if ($totAnunciante <> 0) {
            $totAnunciante=0;
        } else {
            if ($paisAnt <> ""){
                $totAnunciante=0;
                $totPais=0;
            }
        }
            $totAnunciante=$fila['Dias'];
    } else {
            $totAnunciante=$totAnunciante + $fila['Dias'];
    }
 
    if ($paisAnt <> $fila['cod_pais'] ){
        $paisAnt = $fila['cod_pais'];
        
        if ($totPais <> 0) {
            $text="";
            $pdf->Cell(15, 5, $text, 1, 0, "C");
            $pdf->Cell(55, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->Cell(25, 5, "", 1, 0, "C");
            $pdf->SetFillColor(200);
            $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total País")), 1, 0, "R",1);
            $pdf->Cell(35, 5, $totPais, 1, 1, "C",1);
            $totGral=$totGral+$totPais;
            $totPais=0;
        } else {
            if ($primerPais == 0){
                $totGral=$totGral+$totPais;
                $totPais=0;
                $primerPais=0;
            }
        } 
         if ($fila['estado'] == 1 || $fila['estado'] == 2){
            $totPais=$fila['Dias'];
         }
    } else {
         if ($fila['estado'] == 1 || $fila['estado'] == 2){
            $totPais=$totPais + $fila['Dias'];
         }
    }
/*
    if ($paisAnt <> $fila['cod_pais'] ){
        $paisAnt = $fila['cod_pais'];
        if ($totPais <> 0) {
            $text="";
            $pdf->Cell(15, 5, $text, 1, 0, "C");
            $pdf->Cell(25, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->SetFillColor(154,160,235);
            $pdf->Cell(20, 5, iconv('utf-8', 'cp1252', html_entity_decode("País")), 1, 0, "R",1);
            $pdf->Cell(15, 5, $totPais, 1, 0, "C",1);
            $pdf->Cell(20, 5, "", 1, 1, "C");    
            $totGral=$totGral+$totPais;
        }
        $totPais=$fila['diaspublicado'];
    } else {
        $totPais=$totPais + $fila['diaspublicado'];
    }
*/
    $primerDiaMes = strtotime(date("01-m-Y",time()));
    $ultimoDiaMes = strtotime(date("t-m-Y",time()));
    $inactivacion = strtotime(date($fila['fechainactivo']));
    $activacion = strtotime(date($fila['fechaactivacion']));

    if ($inactivacion >= $primerDiaMes || is_null($fila['fechainactivo'])){
        $descPais=Funciones::obtenerDescPais($con,$fila['cod_pais']);
//        $pdf->Cell(15, 5, substr(Funciones::mostrarTipoCliente($con,$fila['nroanuncianteOfi']),0,3).": ".$fila['nroanuncianteOfi'] , 1, 0, "C");
        $pdf->Cell(15, 5, $descPais, 1, 0, "C");
        $pdf->Cell(55, 5, Funciones::obtenerNombreAnunciante($fila['nroanunciante'], $con), 1, 0, "C");
        $pdf->Cell(20, 5, Funciones::formato_numero($fila['nroanuncio'], 8, 4), 1, 0, "C");
        $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['fechaactivacion']), 1, 0, "C");
        if ($fila['estado'] == 2){
            $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['Hoy']), 1, 0, "C");
        } else {
            $pdf->Cell(25, 5, NULL, 1, 0, "C");
        }
    // Cantidad de días desde que esta activo...
        $pdf->Cell(10, 5, $fila['Dias'], 1, 0, "C");

        setlocale (LC_TIME, "es_ES");
        if ($fila['estado'] == 1 || $fila['estado'] == 2){
            $primerDiaMes = strtotime(date("01-m-Y",time()));
            $ultimoDiaMes = strtotime(date("t-m-Y",time()));
            if (is_null($fila['fechainactivo'])){
                $activacion = strtotime(date($fila['fechaactivacion']));
                if ($activacion >= $primerDiaMes && $activacion <= $ultimoDiaMes){
                    $dias = Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('Y-m-d'));
                    $pdf->Cell(35, 5, $dias, 1, 1, "C");
//                    echo "Anuncio1: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                } else {
                    $dias = Funciones::dias_entre_fechas(date('Y-m-01'),date('d-m-Y'));
                    $pdf->Cell(35, 5, $dias, 1, 1, "C");
//                  echo "Anuncio2: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            } else {
                $inactivacion = strtotime(date($fila['fechainactivo']));
                if ($inactivacion >= $primerDiaMes && $inactivacion <= $ultimoDiaMes){
                    $dias = Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date($fila['fechainactivo']));
                    $pdf->Cell(35, 5, $dias, 1, 1, "C");
//                echo "Anuncio3: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                }
            }
            $totMes=$totMes + $dias;
        }
        }
    } // Fin WHILE

if ($anuncianteIndividual <> 1){
    echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
}
?>