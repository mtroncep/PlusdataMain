<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";
require_once("cargarDatosFormulario.php");

$pdf = new FPDF("P", "mm", "A4");

$pdf->AddPage();
$pdf->SetTextColor(0);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(11,11, $pdf->Image('imagenes/logoPlusdata.png', 10, 7,50,20));
$pdf->Ln(2);
$pdf->SetFont("Arial", "B", 20);
$pdf->SetX(130);
$pdf->Cell(35, 10, "FACTURA", 0, 1, "C");
$pdf->Line(100,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln(2);
$pdf->SetFont("Arial", "", 9);

$pdf->SetX(100);
$texto="Factura N°:";
$pdf->Cell(20, 5, iconv('utf-8', 'cp1252', html_entity_decode($texto)), 0, 0, "R");
$pdf->SetX(120);
$pdf->Cell(15, 5, "000000000", 0, 0, "C");

$pdf->SetX(165);
$pdf->Cell(15, 5, "Fecha:", 0, 0, "R");
$pdf->SetX(180);
$pdf->Cell(15, 5, date('d/m/Y'), 0, 1, "C");
$pdf->Ln(2);

$pdf->Output();

?>