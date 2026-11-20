<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

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
    
    echo "Anunciante: ".$anuncianteCorriente."<br>";
    
    $pdf = new FPDF("P", "mm", "A4");
    $pdf->AddPage();

    $pdf->Cell(11,11, $pdf->Image('httpdocs/imagenes/logoPlusdataSinFondo.png', 10, 7, 50, 0, 'png', "indexHistInformesEstado.php"));

    $archivoCreado="httpdocs/informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_".$anuncianteCorriente."_".date('d-m-Y')."_".date("H-i-s").".pdf";
    $pdf->Output($archivoCreado,'F');

}
?>
