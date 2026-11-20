<?php
session_start();

require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

$actualizar=1;
$anuncianteIndividual=0;

error_log("----".iconv('utf-8', 'cp1252', html_entity_decode('Comienzo ejecución')) . date("Y-m-d H:i:s") . " ----\n", 3, __DIR__ . "/estadoMonederoYAvisosCron.log");

setlocale (LC_TIME, "es_ES");

$sqlAnunciantes = "select DISTINCT nroanunciante from ctacteavisos
                    where nroanuncianteinmob = 0
                    group by nroanunciante
                    order by nroanunciante;";

$anunciantesAvisos = $con->query($sqlAnunciantes);

while($anu = $anunciantesAvisos->fetch_assoc()){
    
$sql="SELECT cod_pais,if(nroanuncianteinmob <> 0,nroanuncianteinmob,nroanunciante) nroanunciante,nroanunciante as nroanuncianteOfi,nroanuncio, date(fechaactivacion) 'fechaactivacion', if(fechainactivo is NULL, date(now()), fechainactivo) 'Hoy', if( fechainactivo is NULL,(DATEDIFF(date(now()), date(fechaactivacion))+1),DATEDIFF(date(fechainactivo), date(fechaactivacion))+1) 'Dias', estado, fechainactivo from ctacteavisos where (nroanunciante =".$anu['nroanunciante']." or nroanuncianteinmob = ".$anu['nroanunciante'].") and estado in (1, 2)
group by cod_pais,nroanunciante,nroanuncianteOfi, nroanuncio, fechaactivacion, fechainactivo, estado, date(Hoy), Dias, nroanuncianteinmob
order by cod_pais,nroanuncianteOfi, nroanunciante, nroanuncio, fechainactivo";

$resultado = $con->query($sql);

$anuncianteCorriente = $anu['nroanunciante'];
    
$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();

$anuncianteAnt=0;
$paisAnt=0;

$acumAnuncianteTest=0;
    
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

$primerDiaMes = strtotime(date("01-m-Y",time()));
$ultimoDiaMes = strtotime(date("t-m-Y",time()));
$procesado = 0;

while($fila = $resultado->fetch_assoc()){
    $procesado = 1;
    //    echo "Totpais: ".$totPais."<br>";
    $inactivacion = strtotime(date($fila['fechainactivo']));
    $activacion = strtotime(date($fila['fechaactivacion']));
    $hoy = strtotime(date($fila['Hoy']));

    if ($hoy >= $primerDiaMes){
        if ($encabezado == 0){
            $pdf->SetTextColor(0);
            $pdf->SetFont("Arial", "B", 12);

            $pdf->Cell(11,11, $pdf->Image('httpdocs/imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
    
            $pdf->SetY(19);
            $pdf->SetX(80);
            $pdf->SetFont("Arial", "B", 20);
            $pdf->Cell(80, 5, Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con), 0, 1, "L");
            $pdf->SetFont("Arial", "B", 12);
            $pdf->Ln(2);
            $pdf->Line(10,$pdf->GetY(),195,$pdf->GetY());
            $pdf->Ln(2);
            $pdf->SetX(20);
            $pdf->SetFont("Arial", "B", 15);
            $pdf->Cell(180, 5, strtoupper(strftime("%B").date(' Y')), 0, 1, "C");
            $pdf->Ln(1);
            $pdf->SetFont("Arial", "B", 13);
            $pdf->Cell(30, 5, "CARTERA DE ANUNCIOS", 0, 1, "L");
            $pdf->Ln(1);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Ln(2);
            $pdf->Cell(15, 5, iconv('utf-8', 'cp1252', html_entity_decode("PaÃ­s")), 1, 0, "C");
            $pdf->Cell(55, 5, "Anunciante", 1, 0, "C");
            $pdf->Cell(20, 5, "Anuncio", 1, 0, "C");
            $pdf->Cell(25, 5, "Fecha Alta", 1, 0, "C");
            $pdf->Cell(25, 5, "Fecha Baja", 1, 0, "C");
            $pdf->Cell(10, 5, "M.Ant.", 1, 0, "C");
            $pdf->Cell(18, 5, strtoupper(strftime("%b")), 1, 0, "C");
            $pdf->Cell(10, 5, "Sele", 1, 0, "C");
            $pdf->Cell(10, 5, "Cont", 1, 1, "C");
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
                $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total PaÃ­s")), 1, 0, "R",1);
                $pdf->Cell(18, 5, $totPais, 1, 1, "C",1);
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
              //  $totPais=$fila['Dias'];
             }
        } else {
             if ($fila['estado'] == 1 || $fila['estado'] == 2){
                $totPais=$totPais + 0;
             }
        }

        if ($inactivacion >= $primerDiaMes || is_null($fila['fechainactivo'])){
            $descPais=Funciones::obtenerDescPais($con,$fila['cod_pais']);
            $pdf->Cell(15, 5, $descPais, 1, 0, "C");
            $pdf->Cell(55, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con))), 1, 0, "C");
//            $pdf->Cell(55, 5, Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con), 1, 0, "C");
            $pdf->Cell(20, 5, Funciones::formato_numero($fila['nroanuncio'], 8, 4), 1, 0, "C");
            $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['fechaactivacion']), 1, 0, "C");
            if ($fila['estado'] == 2){
                $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['Hoy']), 1, 0, "C");
            } else {
                $pdf->Cell(25, 5, NULL, 1, 0, "C");
            }
            if ($activacion >= $primerDiaMes){
                $pdf->Cell(10, 5, "", 1, 0, "C");
            } else {
                $dias = Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date("01-m-Y",time())) - 1;
                $pdf->Cell(10, 5, $dias, 1, 0, "C");
            }

            setlocale (LC_TIME, "es_ES");
            if ($fila['estado'] == 1 || $fila['estado'] == 2){
                if (is_null($fila['fechainactivo'])){
                    $activacion = strtotime(date($fila['fechaactivacion']));
                    if ($activacion >= $primerDiaMes && $activacion <= $ultimoDiaMes){
                        $dias = Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date('Y-m-d'));
                        $pdf->Cell(18, 5, $dias, 1, 0, "C");
                    } else {
                        $dias = Funciones::dias_entre_fechas(date('01-m-Y'),date('d-m-Y'));
                        $pdf->Cell(18, 5, $dias, 1, 0, "C");
                    }
                } else {
                    $inactivacion = strtotime(date($fila['fechainactivo']));
                    if ($inactivacion >= $primerDiaMes && $inactivacion <= $ultimoDiaMes){
                        if ($activacion >= $primerDiaMes){
                            $dias = Funciones::dias_entre_fechas(date($fila['fechaactivacion']),date($fila['fechainactivo']));
                            $pdf->Cell(18, 5, $dias, 1, 0, "C");
                        } else {
                            $dias = Funciones::dias_entre_fechas(date('01-m-Y'),date($fila['fechainactivo']));
                            $pdf->Cell(18, 5, $dias, 1, 0, "C");
                        }
                    }
                }

                $estadisticas=Funciones::obtenerEstadisticasAnuncio($fila['nroanuncio'], $con);
                if ($estadisticas){
                    $pdf->Cell(10, 5, $estadisticas['cntseleccionado'], 1, 0, "C");
                    $pdf->Cell(10, 5, $estadisticas['cntvercontacto'], 1, 1, "C");
                } else {
                    $pdf->Cell(10, 5, 0, 1, 0, "C");
                    $pdf->Cell(10, 5, 0, 1, 1, "C");
                }

                $totMes=$totMes + $dias;
                $totPais=$totPais +  $dias;
            }
            }
        }
    } // Fin WHILE

    if ($procesado == 0){
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(11,11, $pdf->Image('httpdocs/imagenes/logoPlusdata.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
        $pdf->SetY(19);
        $pdf->SetX(80);
        $pdf->SetFont("Arial", "B", 20);
//        $pdf->Cell(80, 5, Funciones::obtenerNombreAnunciante("I",$anuncianteCorriente, $con), 0, 1, "L");
        $pdf->Cell(80, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con))), 0, 1, "L");

        $pdf->SetFont("Arial", "B", 12);
        $pdf->Ln(2);
        $pdf->Line(10,$pdf->GetY(),195,$pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "B", 13);
        $pdf->Cell(30, 5, "CARTERA DE ANUNCIOS", 0, 1, "L");
        $pdf->Ln(3);
        $pdf->Cell(150, 5, "SIN MOVIMIENTOS", 0, 1, "C");
    }
    /*
    $pdf->SetFont("Arial", "", 9);
    if ($fila['estado'] == 1 || $fila['estado'] == 2){
        if (strftime("%B") != strftime('%B',strtotime($fila['fechaactivacion']))){
            $primerDiaMes = date('01-m-Y');
            $diaCierre = date('d-m-Y',strtotime($fila['Hoy']));
            $diasDesdeInicio = (strtotime($diaCierre) - strtotime($primerDiaMes))/86400;
            $diasDesdeInicio = abs($diasDesdeInicio); 
            $diasDesdeInicio = floor($diasDesdeInicio) + 1;
            $totMes=$totMes + $diasDesdeInicio;
            } else {
            $totMes=$totMes + $fila['Dias'];
            } 
    } else {
        $totMes=$totMes + $fila['Dias'];
    }
    */
    if ($totPais <> 0) {
        $text="";
        $pdf->Cell(15, 5, $text, 1, 0, "C");
        $pdf->Cell(55, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->SetFillColor(200);
        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total PaÃ­s")), 1, 0, "R",1);
        $pdf->Cell(18, 5, $totPais, 1, 1, "C",1);
        $totGral=$totGral+$totPais;
    }

    if ($totMes <> 0){
        $text="";
        $pdf->Cell(15, 5, $text, 0, 0, "C");
        $pdf->Cell(55, 5, "", 0, 0, "C");
        $pdf->Cell(25, 5, "", 0, 0, "C");
        $pdf->Cell(20, 5, "", 0, 0, "C");
        $pdf->SetFillColor(4,118,17);
        $pdf->SetTextColor(255);
        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("DÃ­as publicacion ".strftime('%b'))), 0, 0, "R",1);
        $pdf->Cell(18, 5, $totMes, 0, 1, "C",1);
    }
    
    $totMesbck = $totMes;
    if (!$anuncianteIndividual){
        if ($actualizar == 1 && $totMesbck <> 0){
            $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck);
            $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
            $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
        }
    } else {
        if (!isset($soloCalculo)){
            if ($actualizar == 1 && $totMesbck <> 0){
                $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }
        } else {
            if ($actualizar == 1 && $totMesbck <> 0){
                $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }            
        }
    }
// Acá¡ comienza el MONEDERO...
    $pdf->Ln(5);
    $pdf->SetTextColor(0);
    $pdf->SetFont("Arial", "B", 13);
    $pdf->Cell(30, 5, "ESTADO DEL MONEDERO", 0, 1, "L");
    $pdf->Ln(2);

$cod_pais=0;
$nroanunciante=$anuncianteCorriente;
$fechaHoy="";
$saldoInicialAvisos=Funciones::obtenerSaldoInicialCtaCteMonedero($cod_pais,$nroanunciante,$fechaHoy,$con);
$saldoFinalAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante);
$cantConsumos=Funciones::obtenerCantConsumosCteAvisos($cod_pais,$nroanunciante,$fechaHoy,$con);
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
$sql = "SELECT * FROM ctactemonedero WHERE nroanunciante = "."$nroanunciante"." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
$resultado = $con->query($sql);

while($fila = $resultado->fetch_assoc()){
    if ($encabezado == 0){
        $pdf->SetX(45);
        $pdf->SetFont("Arial", "B", 9);
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
    $pdf->Cell(20, 5, date('d-m-Y',strtotime($fila['fechamov'])), 1, 0, "C");
    if ($fila['codigomov'] == 1){
        $pdf->Cell(20, 5, $totMesbck * -1, 1, 1, "C");
//        $pdf->Cell(20, 5, $cantConsumos * -1, 1, 1, "C");
    } else {
        $pdf->Cell(20, 5, $fila['cantidaddias'], 1, 1, "C");
    }
} // Fin while!!!
    
    $pdf->SetX(45); 
    $pdf->SetFont("Arial", "", 9);
    $pdf->Cell(25, 5, "Saldo final al" , 1, 0, "L");
    $pdf->Cell(25, 5, "", 1, 0, "C");
    $pdf->Cell(20, 5, date('d-m-Y'), 1, 0, "C");
    $pdf->Cell(20, 5, $saldoFinalAvisos, 1, 1, "C");

// Fin monedero

    $archivoCreado="informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y', $primerDiaMes)."_".date("H-i-s").".pdf";
    $pdf->Output($archivoCreado,'F');
//    echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
} // Fin del recorrido de todos los anunciantes
//    iconv('utf-8', 'cp1252', html_entity_decode("Final ejecución"));
    error_log("----".iconv('utf-8', 'cp1252', html_entity_decode('Final ejecución')) . date("Y-m-d H:i:s") . " ----\n", 3, __DIR__ . "/estadoMonederoYAvisosCron.log");
?>