<?php
 session_start();

$html = '';
include("mysql.php");

$id_comarca = $_POST['id_comarca'];

if ($_SESSION['operacion'] == "B"){
    /*
    $select = "SELECT mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION, count(mu.COD_MUNICIPIO) as cant_anuncios FROM municipiosxcomarca mu,      datosbusquedasformulario db
                where mu.COD_COMARCA=$id_comarca and db.cod_municipio=mu.cod_municipio
                group by mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION
                order by mu.DESCRIPCION";
                */
    $select = "SELECT mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION FROM municipiosxcomarca mu
                where mu.COD_COMARCA=$id_comarca
                group by mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION
                order by mu.DESCRIPCION";
    } else {
    $select = "SELECT mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION, count(mu.COD_MUNICIPIO) as cant_anuncios FROM municipiosxcomarca mu,      datosbusquedasformulario db
                where mu.COD_COMARCA=$id_comarca 
                group by mu.COD_COMARCA, mu.COD_MUNICIPIO, mu.DESCRIPCION
                order by mu.DESCRIPCION";
    }
$result = $mysqli->query($select);
$html = "<option value=''>Elija</option>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="'.$row['COD_MUNICIPIO'].'">'.$descripcion.' '.'</option>';
    }
}
echo $html;

?>