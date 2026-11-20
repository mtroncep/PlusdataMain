<?php

$html = '';

include("mysql.php");

$id_nucleo = $_POST['id_nucleo'];

$result = $mysqli->query(
    /*
    "SELECT ba.COD_NUCLEO, ba.COD_MANZANA, ba.DESCRIPCION, count(ba.COD_MANZANA) as cant_anuncios FROM manzanas ba, 
datosbusquedasformulario db
            where ba.COD_NUCLEO=$id_nucleo
            group by ba.COD_NUCLEO, ba.COD_MANZANA, ba.DESCRIPCION
            order by ba.DESCRIPCION"
            */
    "SELECT ba.COD_NUCLEO, ba.COD_MANZANA, ba.DESCRIPCION FROM manzanas ba
            where ba.COD_NUCLEO=$id_nucleo
            group by ba.COD_NUCLEO, ba.COD_MANZANA, ba.DESCRIPCION
            order by ba.DESCRIPCION"
	);

if ($result->num_rows > 0) {
    $html = "<option value=''>Elija</option>";

    while ($row = $result->fetch_assoc()) {                
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="'.$row['COD_MANZANA'].'">'.$descripcion.'</option>';
    }
}

echo $html;

?>
