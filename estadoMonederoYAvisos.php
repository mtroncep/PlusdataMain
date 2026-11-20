
<?php
session_start();
require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");
// Cargar traducciones desde la DB (igual que en traduccion.php)
$sql = "SELECT clave, texto FROM traducciones WHERE idioma = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_SESSION['idioma']);
$stmt->execute();
$result = $stmt->get_result();

$traducciones = [];
while ($row = $result->fetch_assoc()) {
    $traducciones[$row['clave']] = $row['texto'];
}

// Función de traducción en PHP
function traducir($clave, $traducciones) {
    if (isset($traducciones[trim($clave)])){
        return $traducciones[trim($clave)];
    } else {
        return $clave;
    }
}

$actualizar=1;
$anuncianteIndividual=0;

setlocale (LC_TIME, "es_ES");

if (isset($_GET['anunciante'])){
    $nroanunciante = $_GET['anunciante'];
    $anuncianteIndividual=1;
    $sqlAnunciantes = "select DISTINCT nroanunciante from ctacteavisos
                        where nroanuncianteinmob = 0 and nroanunciante = ".$nroanunciante." 
                        group by nroanunciante
                        order by nroanunciante;";

} else {
    $sqlAnunciantes = "select DISTINCT nroanunciante from ctacteavisos
                        where nroanuncianteinmob = 0
                        group by nroanunciante
                        order by nroanunciante;";
}

// Seleccionar los anunciantes desde la cta cte de avisos...
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

            if ($anuncianteIndividual){
                $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', $_SESSION['menuinicio']));
            } else {
//                $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
                $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
            }
    
            $pdf->SetY(19);
            $pdf->SetX(80);
            $pdf->SetFont("Arial", "B", 20);
//            $pdf->Cell(80, 5, Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con), 0, 1, "L");
            $pdf->Cell(80, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con))), 0, 1, "L");

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
            $pdf->SetX(21);
            $pdf->SetFont("Arial", "B", 15);
            $pdf->Cell(180, 5, traducir("INFORME al", $traducciones)." ".date('d-m-Y'), 0, 1, "C");
    //        $pdf->Cell(50, 5, Funciones::fecha_AMD_to_DMA(date('d-m-Y')), 0, 1, "L");
            $pdf->Ln(1);
            $pdf->SetFont("Arial", "B", 13);
            $pdf->Cell(30, 5, traducir("CARTERA DE ANUNCIOS",$traducciones), 0, 1, "L");
            $pdf->Ln(1);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Ln(2);
            if ($_SESSION['idioma'] == "es"){
                $pdf->Cell(15, 5, iconv('utf-8', 'cp1252', html_entity_decode("País")), 1, 0, "C");
            } else {
                $pdf->Cell(15, 5, html_entity_decode(traducir("País",$traducciones)), 1, 0, "C");
            }
            $pdf->Cell(55, 5, traducir("Anunciante",$traducciones), 1, 0, "C");
            $pdf->Cell(20, 5, traducir("Anuncio",$traducciones), 1, 0, "C");
            $pdf->Cell(25, 5, traducir("Fecha Alta",$traducciones), 1, 0, "C");
            $pdf->Cell(25, 5, traducir("Fecha Baja",$traducciones), 1, 0, "C");
            $pdf->Cell(10, 5, traducir("M.Ant.",$traducciones), 1, 0, "C");
            $pdf->Cell(18, 5, strtoupper(strftime("%b")), 1, 0, "C");
            $pdf->Cell(10, 5, traducir("Sele",$traducciones), 1, 0, "C");
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
                $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode(traducir("Total País",$traducciones))), 1, 0, "R",1);
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
//                $totPais=$totPais + $fila['Dias'];
             }
        }

        if ($inactivacion >= $primerDiaMes || is_null($fila['fechainactivo'])){
            $descPais=Funciones::obtenerDescPais($con,$fila['cod_pais']);
    //        $pdf->Cell(15, 5, substr(Funciones::mostrarTipoCliente($con,$fila['nroanuncianteOfi']),0,3).": ".$fila['nroanuncianteOfi'] , 1, 0, "C");
            $pdf->Cell(15, 5, $descPais, 1, 0, "C");
            $pdf->Cell(55, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con))), 1, 0, "C");
            $pdf->Cell(20, 5, Funciones::formato_numero($fila['nroanuncio'], 8, 4), 1, 0, "C");
            $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['fechaactivacion']), 1, 0, "C");
            if ($fila['estado'] == 2){
                $pdf->Cell(25, 5, Funciones::fecha_AMD_to_DMA($fila['Hoy']), 1, 0, "C");
            } else {
                $pdf->Cell(25, 5, NULL, 1, 0, "C");
            }
        // Cantidad de días desde que esta activo durante el mes anterior...
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
    //                    echo "Anuncio1: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
                    } else {
                        $dias = Funciones::dias_entre_fechas(date('01-m-Y'),date('d-m-Y'));
                        $pdf->Cell(18, 5, $dias, 1, 0, "C");
    //                  echo "Anuncio2: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
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
    //                echo "Anuncio3: ".$fila['nroanuncio']." dias: ".$dias.' primerDiaMes: '.date('Y-m-01').' activ: '.$fila['fechaactivacion'].' fin mes: '.date('Y-m-t')." fecha inactiv: ".$fila['fechainactivo'].'<br>';
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
//    return;
    if ($procesado == 0){
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 0, 'jpg', $_SESSION['menuinicio']));
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
        $pdf->Cell(30, 5, traducir("CARTERA DE ANUNCIOS",$traducciones), 0, 1, "L");
        $pdf->Ln(3);
        $pdf->Cell(150, 5, traducir("SIN MOVIMIENTOS",$traducciones), 0, 1, "C");
    }

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

//    $totMes = $totMes + $fila['dias'] ;
    if ($totPais <> 0) {
        $text="";
        $pdf->Cell(15, 5, $text, 1, 0, "C");
        $pdf->Cell(55, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, "", 1, 0, "C");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->SetFillColor(200);
        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode(traducir("Total País",$traducciones))), 1, 0, "R",1);
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
        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode(traducir("Días publicación",$traducciones)." ".strftime('%b'))), 0, 0, "R",1);
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
                /*
                echo "TotMes = ".$totMesbck."<br>";
                echo "RestanAvisos = ".$restanAvisos."<br>";
                return;
                */
            }
        } else {
            if ($actualizar == 1 && $totMesbck <> 0){
                $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }            
        }
    }
/*
        $archivoCreado="informes/estadoAnuncios/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y').".pdf";
        $pdf->Output($archivoCreado,'F');
        header('content-type: application/pdf');
        readfile($archivoCreado);
        unlink($archivoCreado);
*/
// Acá comienza el MONEDERO...

    $pdf->Ln(3);
    $pdf->SetTextColor(0);
    $pdf->SetFont("Arial", "B", 13);
    $pdf->Cell(30, 5, traducir("ESTADO DEL MONEDERO",$traducciones), 0, 1, "L");
    $pdf->Ln(2);

$cod_pais=0;
//$cod_pais=$_SESSION['codPais'];
//$nroanunciante=$_SESSION['anunciante'];
$nroanunciante=$anuncianteCorriente;
$fechaHoy="";
$saldoInicialAvisos=Funciones::obtenerSaldoInicialCtaCteMonedero($cod_pais,$nroanunciante,$fechaHoy,$con);
$saldoFinalAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante);
$cantConsumos=Funciones::obtenerCantConsumosCteAvisos($cod_pais,$nroanunciante,$fechaHoy,$con);
/*
echo 'SaldoAvisos: '.$saldoInicialAvisos.'<br>';
echo 'SaldoFinal: '.$saldoFinalAvisos.'<br>';
echo 'CantConsumos: '.$cantConsumos.'<br>';
return;    
*/
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
//$soloCalculo=0;

//$con->beginTransaction;

//$sql = "SELECT * FROM ctactemonedero WHERE cod_pais ="."$cod_pais"." and nroanunciante = "."$nroanunciante"." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
$sql = "SELECT * FROM ctactemonedero WHERE nroanunciante = "."$nroanunciante"." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
$resultado = $con->query($sql);

while($fila = $resultado->fetch_assoc()){
    if ($encabezado == 0){
        $pdf->SetX(45);
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(27, 5, traducir("Movimiento",$traducciones), 1, 0, "C");
        $pdf->Cell(27, 5, traducir("Comprobante",$traducciones), 1, 0, "C");
        $pdf->Cell(22, 5, traducir("Fecha",$traducciones), 1, 0, "C");
        
        $pdf->Cell(22, 5, iconv('utf-8', 'cp1252', traducir("Días",$traducciones)), 1, 1, "C");
        
        $pdf->SetFont("Arial", "", 9);
        $encabezado=1;

//        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetX(45);
        $pdf->Cell(27, 5, traducir("Saldo inicial",$traducciones), 1, 0, "L");
        $pdf->Cell(27, 5, "", 1, 0, "C");
        $pdf->Cell(22, 5, date('01-m-Y'), 1, 0, "C");
        $pdf->SetFillColor(250,200,200);
        $pdf->Cell(22, 5, $saldoInicialAvisos, 1, 1, "C");
    }
    $codigoMov[]='';
    $codigoMov = Funciones::obtenerCodigoMovCtaCte($con,$fila['codigomov']);
    $pdf->SetX(45);
    $pdf->Cell(27, 5, traducir($codigoMov[0],$traducciones), 1, 0, "L");
    $pdf->Cell(27, 5, "", 1, 0, "C");
//    $pdf->Cell(20, 5, "", 1, 0, "C");
    $pdf->Cell(22, 5, date('d-m-Y',strtotime($fila['fechamov'])), 1, 0, "C");
    if ($fila['codigomov'] == 1){
        $pdf->Cell(22, 5, $totMesbck * -1, 1, 1, "C");
//        $pdf->Cell(20, 5, $cantConsumos * -1, 1, 1, "C");
    } else {
        $pdf->Cell(22, 5, $fila['cantidaddias'], 1, 1, "C");
    }
}
    $pdf->SetX(45); 
    $pdf->SetFont("Arial", "", 9);
    $pdf->Cell(27, 5, traducir("Saldo final al",$traducciones) , 1, 0, "L");
    $pdf->Cell(27, 5, "", 1, 0, "C");
    $pdf->Cell(22, 5, date('d-m-Y'), 1, 0, "C");
    $pdf->Cell(22, 5, $saldoFinalAvisos, 1, 1, "C");

// Fin monedero
    
    if (!$anuncianteIndividual){
        $archivoCreado="informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y')."_".date("H-i-s").".pdf";
        $pdf->Output($archivoCreado,'F');
//        $aux = Funciones::cargarHistoricoInforme($con,1,$anuncianteCorriente,$archivoCreado);
        echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
    } else {
        if (!isset($soloCalculo)){
            $archivoCreado="informes/estadoAnuncios/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y').".pdf";
            $pdf->Output($archivoCreado,'F');
            header('content-type: application/pdf');
            readfile($archivoCreado);
            unlink($archivoCreado);
        } else {
        }
    }
}
