<?php
session_start();

require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

$mes  = isset($_POST['mes'])  ? (int)$_POST['mes']  : null;
$anio = isset($_POST['anio']) ? (int)$_POST['anio'] : null;

if (!$mes || !$anio) {
    die("Falta mes o año");
    exit;
}

$fechaProceso = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-01', $anio, $mes));

if ($fechaProceso === false) {
    die("Fecha inválida");
    exit;
}

if (isset($_POST['anunciante'])){
    if ($_POST['anunciante'] <> ""){
        $sqlAnunciantes = "select DISTINCT nroanunciante from ctacteavisos
                            where nroanuncianteinmob = 0
                            and nroanunciante in (".$_POST['anunciante'].")
                            group by nroanunciante
                            order by nroanunciante;";
        $anunciantesAvisos = $con->query($sqlAnunciantes);
    } else {
        $sqlAnunciantes = "select DISTINCT nroanunciante from ctacteavisos
                            where nroanuncianteinmob = 0
                            group by nroanunciante
                            order by nroanunciante;";
        $anunciantesAvisos = $con->query($sqlAnunciantes);
    }
}
$actualizar=1;
$anuncianteIndividual=0;

$carpetaTop = __DIR__;
$nombreCarpeta = basename($carpetaTop);

setlocale (LC_TIME, "es_ES");


/**
 * Convertimos cualquier warning/notice en excepción,
 * así el try/catch puede atraparlo y no se corta todo el proceso.
 */

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new Exception("$errstr en $errfile:$errline", $errno);
});

/* Lista de errores para el resumen final */
$errores = [];

$formateador = new IntlDateFormatter(
    'es_ES', // Idioma español
    IntlDateFormatter::LONG, 
    IntlDateFormatter::NONE
);

$cantLeidos = 0;
$cantError = 0;

$cantAnunciantes = $anunciantesAvisos->num_rows;

while($anu = $anunciantesAvisos->fetch_assoc()){

    try {

        $sql="SELECT cod_pais,if(nroanuncianteinmob <> 0,nroanuncianteinmob,nroanunciante) nroanunciante,nroanunciante as nroanuncianteOfi,nroanuncio, date(fechaactivacion) 'fechaactivacion', if(fechainactivo is NULL, date(now()), fechainactivo) 'Hoy', if( fechainactivo is NULL,(DATEDIFF(date(now()), date(fechaactivacion))+1),DATEDIFF(date(fechainactivo), date(fechaactivacion))+1) 'Dias', estado, fechainactivo from ctacteavisos where (nroanunciante =".$anu['nroanunciante']." or nroanuncianteinmob = ".$anu['nroanunciante'].") and estado in (1, 2)
        group by cod_pais,nroanunciante,nroanuncianteOfi, nroanuncio, fechaactivacion, fechainactivo, estado, date(Hoy), Dias, nroanuncianteinmob
        order by cod_pais,nroanuncianteOfi, nroanunciante, nroanuncio, fechainactivo";

        $resultado = $con->query($sql);
//        $cantAnunciantes = $resultado->num_rows;

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

        //$primerDiaMes = strtotime(date("01-m-Y",time()));
        $primerDiaMes = $fechaProceso->getTimestamp();

        $ultimoDiaMes = strtotime(date("t-m-Y",$primerDiaMes));
//        $ultimoDiaMes = date("t", $primerDiaMes);
        error_log("Primer/ultimo dia del mes: ".$primerDiaMes." / ".$ultimoDiaMes,0);
        $procesado = 0;

        while($fila = $resultado->fetch_assoc()){
            $procesado = 1;
/*
            $inactivacion = strtotime(date($fila['fechainactivo']));
            $activacion = strtotime(date($fila['fechaactivacion']));
            $hoy = strtotime(date($fila['Hoy']));
*/
            $inactivacion = $fila['fechainactivo'] ? strtotime($fila['fechainactivo']) : null;
            $activacion   = $fila['fechaactivacion'] ? strtotime($fila['fechaactivacion']) : null;
            $hoy          = $fila['Hoy'] ? strtotime($fila['Hoy']) : null;
            
            if ($hoy >= $primerDiaMes && $hoy <= $ultimoDiaMes){
                if ($encabezado == 0){
                    $pdf->SetTextColor(0);
                    $pdf->SetFont("Arial", "B", 12);

                    $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
            
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
                    $fecha_original = strtoupper(date('F Y',$primerDiaMes));
                    $date = DateTime::createFromFormat('F Y', $fecha_original);
                    $formatter = new IntlDateFormatter(
                                'es_ES', 
                                IntlDateFormatter::NONE, 
                                IntlDateFormatter::NONE, 
                                null, 
                                null, 
                                'MMMM yyyy'
                            );
                    $pdf->Cell(180, 5, mb_strtoupper($formatter->format($date)), 0, 1, "C");
                    $pdf->Ln(1);
                    $pdf->SetFont("Arial", "B", 13);
                    $pdf->Cell(30, 5, "CARTERA DE ANUNCIOS", 0, 1, "L");
                    $pdf->Ln(1);
                    $pdf->SetFont("Arial", "B", 9);
                    $pdf->Ln(2);
                    $pdf->Cell(15, 5, iconv('utf-8', 'cp1252', html_entity_decode("País")), 1, 0, "C");
                    $pdf->Cell(55, 5, "Anunciante", 1, 0, "C");
                    $pdf->Cell(20, 5, "Anuncio", 1, 0, "C");
                    $pdf->Cell(25, 5, "Fecha Alta", 1, 0, "C");
                    $pdf->Cell(25, 5, "Fecha Baja", 1, 0, "C");
                    $pdf->Cell(10, 5, "M.Ant.", 1, 0, "C");
                    $pdf->Cell(18, 5, strtoupper(IntlDateFormatter::formatObject($fechaProceso,'MMM', 'es_ES')), 1, 0, "C");
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
                        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total País")), 1, 0, "R",1);
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
                } else {
                    if ($fila['estado'] == 1 || $fila['estado'] == 2){
                        $totPais=$totPais + 0;
                    }
                }

                if ($inactivacion >= $primerDiaMes || is_null($fila['fechainactivo'])){
                    $descPais=Funciones::obtenerDescPais($con,$fila['cod_pais']);
                    $pdf->Cell(15, 5, $descPais, 1, 0, "C");
                    $pdf->Cell(55, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$fila['nroanunciante'], $con))), 1, 0, "C");
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
        } // Fin WHILE interno

        if ($procesado == 0){
            $pdf->SetTextColor(0);
            $pdf->SetFont("Arial", "B", 12);
            $pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));
            $pdf->SetY(19);
            $pdf->SetX(80);
            $pdf->SetFont("Arial", "B", 20);
            $pdf->Cell(80, 5, iconv('utf-8', 'cp1252', html_entity_decode(Funciones::obtenerNombreAnunciante("I",$anuncianteCorriente, $con))), 0, 1, "L");

            $pdf->SetFont("Arial", "B", 12);
            $pdf->Ln(2);
            $pdf->Line(10,$pdf->GetY(),195,$pdf->GetY());
            $pdf->Ln(3);
            $pdf->SetFont("Arial", "B", 13);
            $pdf->Cell(30, 5, "CARTERA DE ANUNCIOS", 0, 1, "L");
            $pdf->Ln(3);
            $pdf->Cell(150, 5, "SIN MOVIMIENTOS", 0, 1, "C");
        }

        if ($totPais <> 0) {
            $text="";
            $pdf->Cell(15, 5, $text, 1, 0, "C");
            $pdf->Cell(55, 5, "", 1, 0, "C");
            $pdf->Cell(20, 5, "", 1, 0, "C");
            $pdf->Cell(25, 5, "", 1, 0, "C");
            $pdf->SetFillColor(200);
            $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total País")), 1, 0, "R",1);
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
            $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Días publicacion ".IntlDateFormatter::formatObject($fechaProceso,'MMM', 'es_ES'))), 0, 0, "R",1);
            $pdf->Cell(18, 5, $totMes, 0, 1, "C",1);
        }
        
        $totMesbck = $totMes;
        if (!$anuncianteIndividual){
            if ($actualizar == 1 && $totMesbck <> 0){
                $aux = Funciones::cargarMovimientoMonederocron($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck,$primerDiaMes,$ultimoDiaMes);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }
        } else {
            if (!isset($soloCalculo)){
                if ($actualizar == 1 && $totMesbck <> 0){
                    $aux = Funciones::cargarMovimientoMonederocron($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck,$primerDiaMes,$ultimoDiaMes);
                    $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                    $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
                }
            } else {
                if ($actualizar == 1 && $totMesbck <> 0){
                    $aux = Funciones::cargarMovimientoMonederocron($con,1,$paisCorriente,$anuncianteCorriente,$totMesbck,$primerDiaMes,$ultimoDiaMes);
                    $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMesbck;
                    $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
                }            
            }
        }

        // Acá comienza el MONEDERO...
        $pdf->Ln(5);
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "B", 13);
        $pdf->Cell(30, 5, "ESTADO DEL MONEDERO", 0, 1, "L");
        $pdf->Ln(2);

        $cod_pais=0;
        $nroanunciante=$anuncianteCorriente;
        $fechaHoy="";
        $saldoInicialAvisos=Funciones::obtenerSaldoInicialCtaCteMonederocron($cod_pais,$nroanunciante,$primerDiaMes,$ultimoDiaMes,$con);
        $saldoFinalAvisos=Funciones::obtenerRestoAvisos($con,$nroanunciante);
        $cantConsumos=Funciones::obtenerCantConsumosCteAvisoscron($cod_pais,$nroanunciante,$primerDiaMes,$ultimoDiaMes,$con);
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
//        $sql = "SELECT * FROM ctactemonedero WHERE nroanunciante = "."$nroanunciante"." and date(fechamov) BETWEEN CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') AS DATE) and LAST_DAY(NOW()) order by cantidaddias desc";
        $sql = "SELECT * FROM ctactemonedero WHERE nroanunciante = ".$nroanunciante." and fechamov >= '".date('Y-m-d',$primerDiaMes)."' and  fechamov <= '".date('Y-m-d',$ultimoDiaMes)."' order by cantidaddias desc";
        error_log("SELECT CRON NUEVO: ".$sql,0);

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
                $pdf->Cell(20, 5, date('01-m-Y',$primerDiaMes), 1, 0, "C");
                $pdf->SetFillColor(250,200,200);
                $pdf->Cell(20, 5, $saldoInicialAvisos, 1, 1, "C");
            }
            if ($fila['fechamov'] >= date('Y-m-d',$primerDiaMes) && $fila['fechamov'] <= date('Y-m-d',$ultimoDiaMes)) {
                $codigoMov[]='';
                $codigoMov = Funciones::obtenerCodigoMovCtaCte($con,$fila['codigomov']);
                $pdf->SetX(45);
                $pdf->Cell(25, 5, $codigoMov[0], 1, 0, "L");
                $pdf->Cell(25, 5, "", 1, 0, "C");
                $pdf->Cell(20, 5, date('d-m-Y',strtotime($fila['fechamov'])), 1, 0, "C");
                if ($fila['codigomov'] == 1){
                    $pdf->Cell(20, 5, $totMesbck * -1, 1, 1, "C");
                } else {
                    $pdf->Cell(20, 5, $fila['cantidaddias'], 1, 1, "C");
                }
            }
        }
        
        $pdf->SetX(45); 
        $pdf->SetFont("Arial", "", 9);
        $pdf->Cell(25, 5, "Saldo final al" , 1, 0, "L");
        $pdf->Cell(25, 5, "", 1, 0, "C");
        $pdf->Cell(20, 5, date('d-m-Y',$ultimoDiaMes), 1, 0, "C");
        $pdf->Cell(20, 5, $saldoFinalAvisos, 1, 1, "C");

        // Fin monedero
        if ($carpetaTop === "testing"){
            $archivoCreado="testing/informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y', $primerDiaMes)."_".date("H-i-s").".pdf";
        } else {
            $archivoCreado="informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y', $primerDiaMes)."_".date("H-i-s").".pdf";
        }
        $pdf->Output($archivoCreado,'F');
        $cantLeidos += 1;
    } catch (Throwable $e) {
        $cantError += 1;
        // Logueamos el error y seguimos con el siguiente anunciante
        file_put_contents(
            "errores_generacion_pdf.log",
            date("Y-m-d H:i:s") . " - Error con anunciante {$anu['nroanunciante']}: " . $e->getMessage() . "\n",
            FILE_APPEND
        );
        /* Guardamos el anunciante fallado */
        $errores[] = $anu['nroanunciante'];

        continue;
    }
} // Fin while!!!

echo "<hr>";

if (count($errores) > 0) {
    echo "<h2>Proceso finalizado con errores</h2>";
    echo "No se pudieron generar los informes de los siguientes anunciantes:<br>";
    echo implode(", ", $errores);
} else {
    echo "<h3>Proceso finalizado correctamente</h3>";
    echo "Todos los informes fueron generados sin errores.";
}

echo "<h3>RESUMEN DEL PROCESO</h3>";
echo "<h4>Anunciantes leidos: ".$cantAnunciantes."</h4>";
echo "<h4>Informes generados: ".$cantLeidos."</h4>";
echo "<h4>Informes erroneos: ".$cantError."</h4>";

?>