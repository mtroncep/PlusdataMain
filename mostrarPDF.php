<?php
$archivoCreado=$_GET['pdf'];
//echo "Archivo: ".$archivoCreado."<br>";
//return;
header('content-type: application/pdf');
readfile($archivoCreado);
?>