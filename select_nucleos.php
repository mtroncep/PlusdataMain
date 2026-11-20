<?php
session_start();

//echo ("Prueba...");

$html = '';

include("mysql.php");

$id_barrio = $_POST['id_barrio'];

if ($_SESSION['operacion'] == "B"){
    /*
    $select = "SELECT ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION, count(ba.COD_NUCLEO) as cant_anuncios FROM nucleos ba, 
    datosbusquedasformulario db
                where ba.COD_BARRIO=$id_barrio and db.COD_NUCLEO = ba.COD_NUCLEO
                group by ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION
                order by ba.DESCRIPCION";
                */
    $select = "SELECT ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION FROM nucleos ba
                where ba.COD_BARRIO=$id_barrio
                group by ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION
                order by ba.DESCRIPCION";
    }else{
    $select = "SELECT ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION, count(ba.COD_NUCLEO) as cant_anuncios FROM nucleos ba, 
    datosbusquedasformulario db
                where ba.COD_BARRIO=$id_barrio
                group by ba.COD_BARRIO, ba.COD_NUCLEO, ba.DESCRIPCION
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
        $html .= '<option value="'.$row['COD_NUCLEO'].'">'.$descripcion.'</option>';
    }
}

echo $html;

?>
