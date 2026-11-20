<?php
if (PHP_SAPI == 'cli'){
    die('Este ejemplo sólo se puede ejecutar desde un navegador Web');
}

session_start();
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

/** Incluye PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
$objSheet = $objPHPExcel->setActiveSheetIndex(0);
// Propiedades del documento
$objPHPExcel->getProperties()->setCreator("Marcelo")
							 ->setLastModifiedBy("Marcelo")
							 ->setTitle("Office 2010 XLSX Documento de prueba")
							 ->setSubject("Office 2010 XLSX Documento de prueba")
							 ->setDescription("Documento de prueba para Office 2010 XLSX.")
							 ->setKeywords("office 2010 openxml php")
							 ->setCategory("Archivo con resultado de una facturacion");
// Combino las celdas desde A1 hasta E1
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'REPORTE DE FACTURACION')
            ->setCellValue('A2', 'PERIODO')
            ->setCellValue('B2', 'PAIS')
            ->setCellValue('C2', 'ANUNCIANTE')
			->setCellValue('D2', 'ANUNCIO')
			->setCellValue('E2', 'DESDE')
			->setCellValue('F2', 'HASTA')
			->setCellValue('G2', 'DIAS')
			->setCellValue('H2', 'ESTADO');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($boldArray);

//Ancho de las columnas

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

/*Extraer datos de MYSQL*/
# conectare la base de datos
$sql="SELECT * FROM detallefacturacion where periodofacturacion='202401';";
$query=mysqli_query($con,$sql);
$cel=3;// de fila donde empezara a crear  el reporte
while ($row=mysqli_fetch_array($query)){
    $periodo=$row['periodofacturacion'];
    $pais=Funciones::obtenerDescPais($con,$row['cod_pais']);
    $anunciante=$row['nroanunciante'];
    $anuncio=$row['nroanuncio'];
    $desde=$row['fechadesde'];
    $hasta=$row['fechahasta'];
    $dias=$row['diaspublicado'];
    $estado=$row['estado'];
    
    $objPHPExcel->getActiveSheet()->getStyle('B'.$cel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $a="A".$cel;
    $b="B".$cel;
    $c="C".$cel;
    $d="D".$cel;
    $e="E".$cel;
    $f="F".$cel;
    $g="G".$cel;
    $h="H".$cel;
    // Agregar datos
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue($a, $periodo)
    ->setCellValue($b, $pais)
    ->setCellValue($c, $anunciante)
    ->setCellValue($d, $anuncio)
    ->setCellValue($e, $desde)
    ->setCellValue($f, $hasta)
    ->setCellValue($g, $dias)
    ->setCellValue($h, $estado);
$cel+=1;
}

$rango="A2:$h";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);

$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);

// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte de facturacion');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

// Redirigir la salida al navegador web de un cliente ( Excel5 )

header("Content-Type: application/vnd.ms-excel; charset=utf-8");

header('Content-Disposition: attachment;filename="reporteFacturacion.xlsx"');

header('Cache-Control: max-age=0');
// Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
header('Cache-Control: max-age=1');

// Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
/*Fin extracion de datos MYSQL*/
?>
