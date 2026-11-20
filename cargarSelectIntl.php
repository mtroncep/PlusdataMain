<?php
$html = '';
include("mysql.php");

$aux = "SELECT * FROM codpaisesintl order by intldescpais";

$result = $mysqli->query($aux);

//$html = "<option value=''>Elija</option>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
		$descripcion = $row['intldescpais'];
//        $html .= '<option value="'.$row['COD_REGION'].'">'.$descripcion." ".$row['cant_anuncios'].'</option>';
        $html .= '<option value="'.$row['intlcodpais'].'">'.$descripcion.'</option>';
    }
}
echo $html;
?>