<?php
 session_start();

$html = '';
include("mysql.php");

$id_region = $_POST['id_region'];

if ($_SESSION['operacion'] == "B"){
/*
    $select = "SELECT pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION, count(pr.COD_PROVINCIA) as cant_anuncios FROM provincias pr, datosbusquedasformulario db
        where pr.cod_region=$id_region and db.cod_provincia=pr.cod_provincia
        group by pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION
        order by pr.DESCRIPCION
        ";
        */
    $select = "SELECT pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION FROM provincias pr, datosbusquedasformulario db
        where pr.cod_region=$id_region
        group by pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION
        order by pr.DESCRIPCION
        ";
} else {
    $select = "SELECT pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION, count(pr.COD_PROVINCIA) as cant_anuncios FROM provincias pr, datosbusquedasformulario db
        where pr.cod_region=$id_region
        group by pr.COD_REGION, pr.COD_PROVINCIA, pr.DESCRIPCION
        order by pr.DESCRIPCION
        ";
    
}

$result = $mysqli->query($select);

//if ($result->num_rows > 0) {
    echo ("<option value=''>Elija</option>");

    while ($row = $result->fetch_assoc()) {                
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
//        $html .= '<option value="'.$row['COD_PROVINCIA'].'">'.$descripcion.' '.$row['cant_anuncios'].'</option>';
        $html .= '<option value="'.$row['COD_PROVINCIA'].'">'.$descripcion.'</option>';
    }
//}
echo $html;
?>