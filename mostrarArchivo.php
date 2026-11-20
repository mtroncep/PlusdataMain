<!doctype html>
<?php
session_start();

require_once "fpdf/fpdf.php";
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<?php
$archivo = $_GET['path'];
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=documento.pdf");
readfile($archivo);
?>
</html>