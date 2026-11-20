<?php

$html = '';
include("mysql.php");

$id_pais = $_POST['id_pais'];

$result = $mysqli->query(
    "SELECT * FROM regiones WHERE COD_PAIS = ".$id_pais." ORDER BY DESCRIPCION ASC"
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<option value="'.$row['COD_REGION'].'">'.$row['DESCRIPCION'].'</option>';
    }
}
echo $html;
?>