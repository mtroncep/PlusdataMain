<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

mysqli_autocommit($con, FALSE);

$PeriodoFact = $_REQUEST['periodofact'];
$PeriodoFactMostrar = substr($PeriodoFact,4,2).'/'.substr($PeriodoFact,0,4);

$test=$_REQUEST['test'];

$sql = "select * from detallefacturacion where periodofacturacion = $PeriodoFact";
$resultado = $con->query($sql);

$pdf = new FPDF("P", "mm", "letter");
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(150, 5, "Informe Mensual de Publicaciones", 0, 1, "C");
$pdf->Cell(150, 5, $PeriodoFactMostrar, 0, 1, "C");

$pdf->Ln(2);

$pdf->SetFont("Arial", "", 9);

$pdf->Cell(15, 5, "Pais", 1, 0, "C");
$pdf->Cell(25, 5, "Anunciante", 1, 0, "C");
$pdf->Cell(20, 5, "Anuncio", 1, 0, "C");
$pdf->Cell(20, 5, "Desde", 1, 0, "C");
$pdf->Cell(20, 5, "Hasta", 1, 0, "C");
$pdf->Cell(15, 5, "Dias", 1, 0, "C");
$pdf->Cell(20, 5, "Estado", 1, 1, "C");

$anuncianteAnt=0;
$paisAnt=0;

$totAnunciante=0;
$totPais=0;
$totGral=0;

while($fila = $resultado->fetch_assoc()){
    if ($anuncianteAnt <> $fila['nroanunciante'] || $paisAnt <> $fila['cod_pais'] ){
        $aux = $anuncianteAnt;
        $anuncianteAnt = $fila['nroanunciante'];
        if ($totAnunciante <> 0) {
            $pdf->Cell(15, 5, "", 1, 0, "C");
            $pdf->Cell(25, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->SetFillColor(200,200,200);
            $pdf->Cell(20, 5, "Anunciante", 1, 0, "R",1);
            $pdf->Cell(15, 5, $totAnunciante, 1, 0, "C",1);
            $pdf->Cell(20, 5, "", 1, 1, "C");

            $restanAvisos=Funciones::obtenerRestoAvisos($con,$aux) - $totAnunciante;
            $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$aux,$restanAvisos);
            $pdf->Cell(15, 5, "", 1, 0, "C");
            $pdf->Cell(25, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->SetFillColor(200,200,200);
            $pdf->Cell(20, 5, "Restan", 1, 0, "R");
            $pdf->Cell(15, 5, $restanAvisos, 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 1, "C");
        }
        $totAnunciante=$fila['diaspublicado'];
    } else {
        $totAnunciante=$totAnunciante + $fila['diaspublicado'];
    }
 
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
 
    $text=Funciones::obtenerDescPais($con,$fila['cod_pais']);
    $text=iconv('utf-8', 'cp1252', html_entity_decode($text));
    $pdf->Cell(15, 5, $text, 1, 0, "C");
    $pdf->Cell(25, 5, $fila['nroanunciante'], 1, 0, "C");
    $pdf->Cell(20, 5, $fila['nroanuncio'], 1, 0, "C");
    $pdf->Cell(20, 5, Funciones::fecha_AMD_to_DMA($fila['fechadesde']), 1, 0, "C");
//    $pdf->Cell(20, 5, substr($fila['fechadesde'],0,10), 1, 0, "C");
    if ($fila['fechahasta'] != NULL){
        $pdf->Cell(20, 5, Funciones::fecha_AMD_to_DMA($fila['fechahasta']), 1, 0, "C");
    } else {
        $pdf->Cell(20, 5, " ", 1, 0, "C");
    }
//    $pdf->Cell(20, 5, substr($fila['fechahasta'],0,10), 1, 0, "C");
    $pdf->Cell(15, 5, $fila['diaspublicado'], 1, 0, "C");
    $pdf->Cell(20, 5, $fila['estado'], 1, 1, "C");    
    }

    if ($totAnunciante <> 0) {
        $pdf->Cell(15, 5, "", 1, 0, "C");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->SetFillColor(200,200,200);
        $pdf->Cell(20, 5, "Anunciante", 1, 0, "C",1);
        $pdf->Cell(15, 5, $totAnunciante, 1, 0, "C",1);
        $pdf->Cell(20, 5, "", 1, 1, "C");

        $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteAnt) - $totAnunciante;
        $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteAnt,$restanAvisos);
        $pdf->Cell(15, 5, "", 1, 0, "C");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->SetFillColor(200,200,200);
        $pdf->Cell(20, 5, "Restan", 1, 0, "R");
        $pdf->Cell(15, 5, $restanAvisos, 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 1, "C");
    } else {
        $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteAnt) - $totAnunciante;
        $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteAnt,$restanAvisos);
        $pdf->Cell(15, 5, "", 1, 0, "C");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->SetFillColor(200,200,200);
        $pdf->Cell(20, 5, "Restan", 1, 0, "R");
        $pdf->Cell(15, 5, $restanAvisos, 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 1, "C");
    }
 
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

    $text="";
    $pdf->Cell(15, 5, $text, 1, 0, "C");
    $pdf->Cell(25, 5, "", 1, 0, "C");
    $pdf->Cell(20, 5, "", 1, 0, "C");
    $pdf->Cell(20, 5, "", 1, 0, "C");
    $pdf->SetFillColor(4,118,17);
    $pdf->SetTextColor(255);
    $pdf->Cell(20, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total Gral.")), 1, 0, "R",1);
    $pdf->Cell(15, 5, $totGral, 1, 0, "C",1);
    $pdf->Cell(20, 5, "", 1, 1, "C");    

    if ($test){
        mysqli_rollback($con);
    } else {
        mysqli_commit($con);
    }
    mysqli_close($con);

    $pdf->Output();

?>
