<?php
session_start();

$html = '';
include("mysql.php");

$id_pais = $_POST['id_pais'];

if ($_SESSION['operacion'] == "B"){
/*
    $aux = "SELECT re.COD_PAIS, re.COD_REGION, re.DESCRIPCION, count(db.COD_REGION) as cant_anuncios FROM regiones re, 
            datosbusquedasformulario db
            where re.cod_pais=$id_pais and db.COD_REGION=re.COD_REGION
            group by re.COD_PAIS, re.COD_REGION, re.DESCRIPCION
            order by re.DESCRIPCION
            ";
            */
    $aux = "SELECT re.COD_PAIS, re.COD_REGION, re.DESCRIPCION FROM regiones re
            where re.cod_pais=$id_pais
            group by re.COD_PAIS, re.COD_REGION, re.DESCRIPCION
            order by re.DESCRIPCION
            ";
}else {
    $aux = "SELECT re.COD_PAIS, re.COD_REGION, re.DESCRIPCION, count(re.COD_REGION) as cant_anuncios FROM regiones re, 
            datosbusquedasformulario db
            where re.cod_pais=$id_pais
            group by re.COD_PAIS, re.COD_REGION, re.DESCRIPCION
            order by re.DESCRIPCION
            ";
}

$result = $mysqli->query($aux);

$html = "<option value=''>Elija</option>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="' . $row['COD_REGION'] . '">' . $descripcion . '</option>';
    }
}
echo $html;
?>