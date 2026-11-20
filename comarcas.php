<?php

$html = '';
include("mysql.php");

$id_provincia = $_POST['id_provincia'];

$result = $mysqli->query(
    "SELECT * FROM comarcas WHERE COD_PROVINCIA = ".$id_provincia." ORDER BY DESCRIPCION ASC"
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<option value="'.$row['COD_COMARCA'].'">'.$row['DESCRIPCION'].'</option>';
    }
}
echo $html;
?>