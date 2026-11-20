<?php

$html = '';
include("mysql.php");

$id_region = $_POST['id_region'];

$result = $mysqli->query(
    "SELECT * FROM provincias WHERE COD_REGION = ".$id_region." ORDER BY DESCRIPCION ASC"
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<option value="'.$row['COD_PROVINCIA'].'">'.$row['DESCRIPCION'].'</option>';
    }
}
echo $html;
?>