<?php
 session_start();

$html = '';
include("mysql.php");

$id_municipio = $_POST['id_municipio'];
if ($_SESSION['operacion'] == "B"){
    /*
    $select = "SELECT lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION, count(lo.cod_localidad) as cant_anuncios FROM localidades lo, 
    datosbusquedasformulario db
                where lo.COD_MUNICIPIO=$id_municipio and db.cod_localidad = lo.cod_localidad
                group by lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION
                order by lo.DESCRIPCION";
                */
    $select = "SELECT lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION FROM localidades lo
                where lo.COD_MUNICIPIO=$id_municipio
                group by lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION
                order by lo.DESCRIPCION";
}else{
    $select = "SELECT lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION, count(lo.cod_localidad) as cant_anuncios FROM localidades lo, 
    datosbusquedasformulario db
                where lo.COD_MUNICIPIO=$id_municipio
                group by lo.COD_MUNICIPIO, lo.COD_LOCALIDAD, lo.DESCRIPCION
                order by lo.DESCRIPCION";
}
$result = $mysqli->query($select);

if ($result->num_rows > 0) {
    $html = "<option value=''>Elija</option>";

    while ($row = $result->fetch_assoc()) {
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="'.$row['COD_LOCALIDAD'].'">'.$descripcion.'</option>';
    }
}
echo $html;

?>