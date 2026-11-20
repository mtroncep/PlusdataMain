<?php
 session_start();

$html = '';
include("mysql.php");

$id_provincia = $_POST['id_provincia'];
/*
$select = "SELECT co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION, count(d.nroanuncio) as cant_anuncios
            FROM comarcas co
            join datosformulario d 
            on d.campo='ubic_comarca_id' and d.codigoubicacion=co.COD_COMARCA and co.COD_PROVINCIA=".$id_provincia."
            group by co.COD_COMARCA
            order by co.DESCRIPCION";

$select = "SELECT co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION FROM comarcas co
            where co.COD_PROVINCIA=".$id_provincia."
            group by co.COD_COMARCA
            order by co.DESCRIPCION";
*/

if ($_SESSION['operacion'] == "B"){
/*
    $select = "SELECT co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION, count(co.COD_COMARCA) as cant_anuncios FROM comarcas co, datosbusquedasformulario db
            where co.cod_provincia=$id_provincia and db.cod_comarca=co.cod_comarca
            group by co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION
            order by co.DESCRIPCION
            ";
            */
    $select = "SELECT co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION FROM comarcas co 
            where co.cod_provincia=$id_provincia
            group by co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION
            order by co.DESCRIPCION
            ";
}else{
    $select = "SELECT co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION, count(co.COD_COMARCA) as cant_anuncios FROM comarcas co, datosbusquedasformulario db
            where co.cod_provincia=$id_provincia
            group by co.COD_PROVINCIA, co.COD_COMARCA, co.DESCRIPCION
            order by co.DESCRIPCION
            ";
    
}
$result = $mysqli->query($select);
$html = "<option value=''>Elija</option>";
//if ($result->num_rows > 0) {
//    echo ("<option value=''>Elija</option>");

    while ($row = $result->fetch_assoc()) {           
		$descripcion = $row['DESCRIPCION'];
		if ($row['DESCRIPCION'] == "NULL") {
			$descripcion = "Siguiente";
		}
        $html .= '<option value="'.$row['COD_COMARCA'].'">'.$descripcion.'</option>';
//        $html .= '<option value="'.$row['COD_COMARCA'].'">'.$descripcion.' '.$row['cant_anuncios'].'</option>';
    }
//}
echo $html;
?>