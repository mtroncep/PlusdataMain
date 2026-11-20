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

$actualizar=1;
$anuncianteIndividual=0;

setlocale (LC_TIME, "es_ES");

if (isset($_GET['anunciante'])){
    $nroanunciante = $_SESSION['anunciante'];
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
//$sql = "SELECT cod_pais,if(nroanuncianteinmob <> 0,nroanuncianteinmob,nroanunciante) nroanunciante,nroanunciante as nroanuncianteOfi,nroanuncio, date(fechaactivacion) 'fechaactivacion', if(fechainactivo is NULL, date(now()), fechainactivo) 'Hoy', if( fechainactivo is NULL,(DATEDIFF(date(now()), date(fechaactivacion))+1),DATEDIFF(date(fechainactivo), date(fechaactivacion))+1) 'Dias', estado, fechainactivo from ctacteavisos where (nroanunciante =".$anu['nroanunciante']." or nroanuncianteinmob = ".$anu['nroanunciante'].") and estado in (1, 2) group by nroanunciante, nroanuncio, date(fechainactivo), estado order by nroanunciante, nroanuncio, fechaactivacion";
//$sql="SELECT cod_pais,nroanunciante, nroanuncianteinmob as nroanuncianteOfi, nroanuncio,date(fechaactivacion) fechaactivacion, date(fechainactivo) fechainactivo, date(if(date(fechainactivo) is NULL, date(now()), date(fechainactivo))) Hoy, if( date(fechainactivo) is NULL,(DATEDIFF(date(now()), date(fechaactivacion))+1),DATEDIFF(date(fechainactivo), date(fechaactivacion))+1) 'Dias', estado 
//from ctacteavisos where (nroanunciante =".$anu['nroanunciante']." or nroanuncianteinmob =".$anu['nroanunciante'].") and estado in (1, 2) 
//group by cod_pais,nroanunciante,nroanuncianteOfi, nroanuncio, fechaactivacion, fechainactivo, estado, date(Hoy), Dias
//order by nroanunciante, nroanuncio, fechaactivacion, fechainactivo";
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
        $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Total País")), 1, 0, "R",1);
        $pdf->Cell(35, 5, $totPais, 1, 1, "C",1);
        $totGral=$totGral+$totPais;
    }

    $text="";
    $pdf->Cell(15, 5, $text, 0, 0, "C");
    $pdf->Cell(55, 5, "", 0, 0, "C");
    $pdf->Cell(25, 5, "", 0, 0, "C");
    $pdf->Cell(20, 5, "", 0, 0, "C");
    $pdf->SetFillColor(4,118,17);
    $pdf->SetTextColor(255);
    $pdf->Cell(35, 5, iconv('utf-8', 'cp1252', html_entity_decode("Días publicacion ".strftime('%B'))), 0, 0, "R",1);
    $pdf->Cell(35, 5, $totMes, 0, 0, "C",1);

    if (!$anuncianteIndividual){
        $archivoCreado="informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y').".pdf";
        $pdf->Output($archivoCreado,'F');
        if ($actualizar == 1 && $totMes <> 0){
            $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMes);
            $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMes;
            $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
        }
    } else {
        if (!isset($soloCalculo)){
            $archivoCreado="informes/estadoAnuncios/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y').".pdf";
            $pdf->Output($archivoCreado,'F');
            
            if ($actualizar == 1 && $totMes <> 0){
                $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMes);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMes;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }
            header('content-type: application/pdf');
            readfile($archivoCreado);
            unlink($archivoCreado);
        } else {
//            $archivoCreado="informes/estadoAnuncios/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y').".pdf";
//            $pdf->Output($archivoCreado,'F');

            if ($actualizar == 1 && $totMes <> 0){
                $aux = Funciones::cargarMovimientoMonedero($con,1,$paisCorriente,$anuncianteCorriente,$totMes);
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteCorriente) - $totMes;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteCorriente,$restanAvisos);
            }            
        }
    }
}

if ($anuncianteIndividual <> 1){
    echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
}
?>