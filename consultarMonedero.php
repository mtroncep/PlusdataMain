<!DOCTYPE html>
<?php
session_start();

?>
<script>
     function traducir(texto) {
      if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
      }
      return texto;
    }
</script>

<?php

if (!isset($_SESSION['codPais'])){
    echo '<script type="text/javascript">alert(traducir("Bienvenido"))</script>';
    //echo ("<script>alert('traducir('Falta seleccionar el país.'');</script>");
    echo ('<script type="text/javascript">'."window.close();"."</script>");
}

$soloCalculo = 1;
require_once 'estadoActualAvisos.php';

setlocale (LC_ALL, "es_ES");

//date_default_timezone_set('America/Argentina/Buenos_Aires');

$cod_pais=$_SESSION['codPais'];
$nroanunciante=$_SESSION['anunciante'];
$fechaHoy="";
$saldoInicialAvisos=Funciones::obtenerSaldoInicialCtaCteMonedero($cod_pais,$nroanunciante,$fechaHoy,$con);
$saldoFinalAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante);
$cantConsumos=Funciones::obtenerCantConsumosCteAvisos($cod_pais,$nroanunciante,$fechaHoy,$con);

$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();
$anuncianteAnt=0;
$paisAnt=0;

$totAnunciante=0;
$totPais=0;
$totMes=0;
$totGral=0;
$totInactivos=0;
$totInactivosPais=0;
$totGralInactivos=0;
$primerPais=1;
$encabezado=0;

//$con->beginTransaction;

$sql = "SELECT * FROM ctactemonedero WHERE cod_pais ="."$cod_pais"." and nroanunciante = "."$nroanunciante"." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
//$sql = "SELECT * FROM ctactemonedero WHERE nroanunciante = ".$nroanunciante." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
$resultado = $con->query($sql);

//echo "Estamos por entrar a procesar";
//return;

while($fila = $resultado->fetch_assoc()){
    if ($encabezado == 0){
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "B", 12);
        //$pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 20, 'png', 'http://localhost/HMIPrueba/'.$_SESSION['menuinicio']));
        $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 20, 'png', $_SESSION['menuinicio']));

        $pdf->SetY(19);
        $pdf->SetX(100);
        $pdf->SetFont("Arial", "B", 24);
        $pdf->Cell(60, 5, strftime("%B %Y"), 0, 1, "C");
        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetY(25);

        $pdf->Ln(2);
        $pdf->Line(10,$pdf->GetY(),160,$pdf->GetY());
        $pdf->Ln(2);
        $pdf->Cell(135, 5, "MONEDERO", 0, 1, "C");
        $pdf->Ln(3);
        $pdf->SetX(55);
        $pdf->Cell(10, 5, "al", 0, 0, "R");
        $pdf->Cell(50, 5, Funciones::fecha_AMD_to_DMA(date('d-m-Y')), 0, 1, "L");
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(55);
        $pdf->Cell(10, 5, "Cliente: ", 0, 0, "R");
        $pdf->SetFont("Arial", "U", 10);
        $pdf->Cell(20, 5, Funciones::formato_numero($fila['nroanunciante'], 6, 3), 0, 0, "L");
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(80);
        $pdf->Cell(20, 5, "Nombre: ", 0, 0, "R");
        $pdf->SetFont("Arial", "U", 10);
        $pdf->Cell(40, 5, Funciones::obtenerNombreAnunciante($fila['nroanunciante'], $con), 0, 1, "L");
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Ln(2);
        $pdf->SetX(45);
        $pdf->Cell(25, 5, "Movimiento", 1, 0, "C");
        $pdf->Cell(25, 5, "Comprobante", 1, 0, "C");
        $pdf->Cell(20, 5, "Fecha", 1, 0, "C");
        $pdf->Cell(20, 5, "Dias", 1, 1, "C");
        $pdf->SetFont("Arial", "", 9);
        $encabezado=1;

        $pdf->SetX(45);
        $pdf->Cell(25, 5, "Saldo inicial", 1, 0, "L");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, date('01-m-Y'), 1, 0, "C");
        $pdf->SetFillColor(250,200,200);
        $pdf->Cell(20, 5, $saldoInicialAvisos, 1, 1, "C");
    }
    $codigoMov[]='';
    $codigoMov = Funciones::obtenerCodigoMovCtaCte($con,$fila['codigomov']);
    $pdf->SetX(45);
    $pdf->Cell(25, 5, $codigoMov[0], 1, 0, "L");
    $pdf->Cell(25, 5, "", 1, 0, "C");
    $pdf->Cell(20, 5, "", 1, 0, "C");
    if ($fila['codigomov'] == 1){
        $pdf->Cell(20, 5, $cantConsumos * -1, 1, 1, "C");
    } else {
        $pdf->Cell(20, 5, $fila['cantidaddias'], 1, 1, "C");
    }
}
    $pdf->SetX(45); 
    $pdf->SetFont("Arial", "", 9);
    $pdf->Cell(25, 5, "Saldo final al" , 1, 0, "L");
    $pdf->Cell(25, 5, "", 1, 0, "C");
    $pdf->Cell(20, 5, date('d-m-Y'), 1, 0, "C");
    $pdf->Cell(20, 5, $saldoFinalAvisos, 1, 1, "C");

    $archivoCreado="informes/monederos/Monedero_Anunciante_".$nroanunciante."_".date('d-m-Y').".pdf";
    $pdf->Output($archivoCreado,'F');
//    $archivoCreado='http://localhost/HMIPrueba/'.$archivoCreado;
    header('content-type: application/pdf');
    readfile($archivoCreado);
    unlink($archivoCreado);
?>