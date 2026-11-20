<?php
session_start();
//echo ("Prueba...");

$html = '';
include("mysql.php");

$id_localidad = $_POST['id_localidad'];
$_SESSION['cod_localidad']=$id_localidad;

if ($_SESSION['operacion'] == "B"){
/*    
    $select = "SELECT ba.COD_LOCALIDAD, ba.COD_BARRIO, ba.DESCRIPCION, count(ba.COD_BARRIO) as cant_anuncios FROM barrios ba, 
    datosbusquedasformulario db
                where ba.COD_LOCALIDAD=$id_localidad and db.COD_BARRIO = ba.COD_BARRIO
                group by ba.COD_BARRIO, ba.COD_LOCALIDAD, ba.DESCRIPCION
                order by ba.DESCRIPCION";
                */
    $select = "SELECT ba.COD_LOCALIDAD, ba.COD_BARRIO, ba.DESCRIPCION FROM barrios ba
                where ba.COD_LOCALIDAD=$id_localidad
                group by ba.COD_BARRIO, ba.COD_LOCALIDAD, ba.DESCRIPCION
                order by ba.DESCRIPCION";
}else{
    $select = "SELECT ba.COD_LOCALIDAD, ba.COD_BARRIO, ba.DESCRIPCION, count(ba.COD_BARRIO) as cant_anuncios FROM barrios ba, 
    datosbusquedasformulario db
                where ba.COD_LOCALIDAD=$id_localidad
                group by ba.COD_BARRIO, ba.COD_LOCALIDAD, ba.DESCRIPCION
                order by ba.DESCRIPCION";
}

$result = $mysqli->query($select);

if ($result->num_rows > 0) {
    $html = "<option value=''>Elija</option>";

    while ($row = $result->fetch_assoc()) {   
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="'.$row['COD_BARRIO'].'">'.$descripcion.'</option>';
    }
}
echo $html;

?>