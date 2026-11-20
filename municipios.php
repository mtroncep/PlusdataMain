<?php

//echo ("Prueba...");

$html = '';
include("mysql.php");

$id_comarca = $_POST['id_comarca'];

$result = $mysqli->query(
    "SELECT * FROM municipiosxcomarca WHERE COD_COMARCA = ".$id_comarca." ORDER BY DESCRIPCION ASC"
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<option value="'.$row['COD_MUNICIPIO'].'">'.$row['DESCRIPCION'].'</option>';
    }
}
echo $html;

?>