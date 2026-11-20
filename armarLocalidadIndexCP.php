<!DOCTYPE html>
<script type="text/javascript">
    function ShowSelLOC()
    {
    /* Para obtener el valor */
    var cod = document.getElementById("selLOC").value;
    window.location.href = cod;

    /* Para obtener el texto */
//    var combo = document.getElementById("selLOC");
//    var selected = combo.options[combo.selectedIndex].text;
//    alert(selected);
    }
</script>

<?php
	require_once("conexion.php");

	$locBuscar = $_REQUEST['locBuscar'];

	$buscar = '%'.mb_strtolower($locBuscar,'UTF-8').'%';
	$buscar = str_replace(' ', '%', $buscar);

    if ((is_numeric($locBuscar)) || (!ctype_alpha($locBuscar))){
        $locDescrip = mysqli_query($con,"SELECT l.COD_LOCALIDAD, l.DESCRIPCION FROM codigospostales c, localidades l WHERE c.cp = '".$locBuscar."' and c.campo = 'valor_checkbox_09_32' and l.COD_LOCALIDAD = c.cod_localidad");
        $row_loc = mysqli_num_rows($locDescrip);
        if ($row_loc == 1){
            $row = mysqli_fetch_array($locDescrip);
//            $locBuscar = $row['DESCRIPCION'];
            $locbuscar=str_replace("'","",$row['DESCRIPCION']);

            procesarLocalidades($con, $locBuscar);
            return;
        }

        $buscarCP="SELECT l.COD_LOCALIDAD,l.DESCRIPCION LOC_DESCRIP, m.COD_MUNICIPIO,m.DESCRIPCION, co.COD_COMARCA,co.DESCRIPCION, p.COD_PROVINCIA,p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION PAI_DESCRIP
        FROM localidades l, municipiosxcomarca m, comarcas co, provincias p, regiones r, paises pa
        where m.COD_MUNICIPIO = l.COD_MUNICIPIO AND
            co.COD_COMARCA = m.COD_COMARCA AND
            p.COD_PROVINCIA = co.COD_PROVINCIA AND
            r.COD_REGION = p.COD_REGION AND
            pa.COD_NUM = r.COD_PAIS AND (";

        $or = "";
        while ($row = mysqli_fetch_array($locDescrip)){
            $buscarCP .= $or."COD_LOCALIDAD = ".$row['COD_LOCALIDAD']." ";
            $or = " OR ";
        }
        $buscarCP .= ") group by l.COD_LOCALIDAD,l.DESCRIPCION,m.COD_MUNICIPIO,m.DESCRIPCION,co.COD_COMARCA,co.DESCRIPCION,p.COD_PROVINCIA,p.DESCRIPCION,r.COD_REGION,r.DESCRIPCION,pa.COD_NUM,pa.DESCRIPCION 
        order by l.COD_LOCALIDAD";

        $caminoCP = mysqli_query($con, $buscarCP);
        
        $urlIndexCP = "indexCP.php?";

        if ($caminoCP) {
            $row_cnt = mysqli_num_rows($caminoCP);
            if ($row_cnt > 0){
                if ($row_cnt == 1){
                    while ($row = mysqli_fetch_array($caminoCP)){
                        $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                        $urlIndexCP.='&cod_descripcion='.$row['LOC_DESCRIP'];
                        $urlIndexCP.='&pais='.$row['COD_NUM']."&";
                        $urlIndexCP.="region=".$row['COD_REGION'];
                        $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                        $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                        $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                        echo ('<script type="text/javascript">'."window.location.href = '$urlIndexCP'</script>");
                    }
                } else {
                    // Armamos el desplegable con las localidades obtenidas...
                    $html  = '<div class="col border form-check-inline">';
                    $html .= '<select class="form-select form-select-sm" id="selLOC" name="selLOC" onchange="ShowSelLOC();">';
                    $html .= '<option value="" selected>Seleccione Localidad</option>';

                    while($row = mysqli_fetch_array($caminoCP)){	
                        $urlIndexCP = "indexCP.php?";
                        $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                        $urlIndexCP.="&cod_descripcion=".urlencode($row['LOC_DESCRIP']);
                        $urlIndexCP.='&pais='.$row['COD_NUM'];
                        $urlIndexCP.="&region=".$row['COD_REGION'];
                        $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                        $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                        $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                        $html .= '<option class="fs-6" value='.$urlIndexCP.'>'.$row["LOC_DESCRIP"]."-".$row['PAI_DESCRIP'].'</option>';
                    }
                    $html .= '</select>'; 
                    $html .= '</div>';
                    echo $html;
                }
            } else {
                echo ("<script>alert('CODIGO POSTAL ".$locBuscar." NO EXISTE. Intenta de nuevo.');</script>");
                echo ('<script type="text/javascript">'."window.location.href = 'indexCP.php'</script>");
            }
        } else {
            echo ("<script>alert('CODIGO POSTAL ".$locBuscar." NO EXISTE. Intenta de nuevo.');</script>");
            echo ('<script type="text/javascript">'."window.location.href = 'indexCP.php'</script>");
        }
    } else {
        $locBuscar = "%".$locBuscar."%";
        procesarLocalidades($con,$locBuscar);
    }
    
function procesarLocalidades($con, $locBuscar){
    $camino = mysqli_query($con,"SELECT l.COD_LOCALIDAD,l.DESCRIPCION LOC_DESCRIP, m.COD_MUNICIPIO,m.DESCRIPCION, co.COD_COMARCA,co.DESCRIPCION, p.COD_PROVINCIA,p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION PAI_DESCRIP
    FROM localidades l, municipiosxcomarca m, comarcas co, provincias p, regiones r, paises pa
    where m.COD_MUNICIPIO = l.COD_MUNICIPIO AND
        co.COD_COMARCA = m.COD_COMARCA AND
        p.COD_PROVINCIA = co.COD_PROVINCIA AND
        r.COD_REGION = p.COD_REGION AND
        pa.COD_NUM = r.COD_PAIS AND
        l.DESCRIPCION like '$locBuscar'
    group by l.COD_LOCALIDAD,l.DESCRIPCION,m.COD_MUNICIPIO,m.DESCRIPCION,co.COD_COMARCA,co.DESCRIPCION,p.COD_PROVINCIA,p.DESCRIPCION,r.COD_REGION,r.DESCRIPCION,pa.COD_NUM,pa.DESCRIPCION 
    order by l.COD_LOCALIDAD
    ");

	if ($camino) {
		$row_cnt = mysqli_num_rows($camino);
		if ($row_cnt == 0){
			$camino = mysqli_query($con,"SELECT l.COD_LOCALIDAD,l.DESCRIPCION LOC_DESCRIP, m.COD_MUNICIPIO,m.DESCRIPCION, co.COD_COMARCA,co.DESCRIPCION, p.COD_PROVINCIA,p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION PAI_DESCRIP
			FROM localidades l, municipiosxcomarca m, comarcas co, provincias p, regiones r, paises pa
			where m.COD_MUNICIPIO = l.COD_MUNICIPIO AND
				co.COD_COMARCA = m.COD_COMARCA AND
				p.COD_PROVINCIA = co.COD_PROVINCIA AND
				r.COD_REGION = p.COD_REGION AND
				pa.COD_NUM = r.COD_PAIS AND
				l.DESCRIPCION like '$locBuscar'
			group by l.COD_LOCALIDAD,l.DESCRIPCION,m.COD_MUNICIPIO,m.DESCRIPCION,co.COD_COMARCA,co.DESCRIPCION,p.COD_PROVINCIA,p.DESCRIPCION,r.COD_REGION,r.DESCRIPCION,pa.COD_NUM,pa.DESCRIPCION 
			order by l.COD_LOCALIDAD
			");
		}
	}

	$urlIndexCP = "indexCP.php?";

	if ($camino) {
		$row_cnt = mysqli_num_rows($camino);
		if ($row_cnt > 0){
            if ($row_cnt == 1){
                while ($row = mysqli_fetch_array($camino)){
                    $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                    $urlIndexCP.='&cod_descripcion='.$row['LOC_DESCRIP'];
                    $urlIndexCP.='&pais='.$row['COD_NUM']."&";
                    $urlIndexCP.="region=".$row['COD_REGION'];
                    $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                    $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                    $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                    echo ('<script type="text/javascript">'."window.location.href = '$urlIndexCP'</script>");
                }
            } else {
                // Armamos el desplegable con las localidades obtenidas...
                $html  = '<div class="col border form-check-inline">';
                $html .= '<select class="form-select form-select-sm" id="selLOC" name="selLOC" onchange="ShowSelLOC();">';
                $html .= '<option value="" selected>Seleccione Localidad</option>';

				while($row = mysqli_fetch_array($camino)){	
                    $urlIndexCP = "indexCP.php?";
                    $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                    $urlIndexCP.="&cod_descripcion=".urlencode($row['LOC_DESCRIP']);
                    $urlIndexCP.='&pais='.$row['COD_NUM'];
                    $urlIndexCP.="&region=".$row['COD_REGION'];
                    $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                    $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                    $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                    $html .= '<option class="fs-6" value='.$urlIndexCP.'>'.$row["LOC_DESCRIP"]."-".$row['PAI_DESCRIP'].'</option>';
                }
                $html .= '</select>'; 
                $html .= '</div>';
                echo $html;
            }
		} else {
            $localidad=str_replace("%", "", $locBuscar);
            echo ("<script>alert('LOCALIDAD ".$locBuscar." NO EXISTE. Intenta de nuevo.');</script>");
			echo ('<script type="text/javascript">'."window.location.href = 'indexCP.php'</script>");
		}
	}
}

function procesarCP ($con, $locBuscar){
        $locDescrip = mysqli_query($con,"SELECT l.COD_LOCALIDAD, l.DESCRIPCION FROM codigospostales c, localidades l WHERE c.cp = '$locBuscar' and c.campo = 'valor_checkbox_09_32' and l.COD_LOCALIDAD = c.cod_localidad");
        $row_loc = mysqli_num_rows($locDescrip);
        if ($row_loc == 1){
            $row = mysqli_fetch_array($locDescrip);
            $locBuscar = $row['DESCRIPCION'];
            procesarLocalidades($con, $locBuscar);
            return;
        }

        $buscarCP="SELECT l.COD_LOCALIDAD,l.DESCRIPCION LOC_DESCRIP, m.COD_MUNICIPIO,m.DESCRIPCION, co.COD_COMARCA,co.DESCRIPCION, p.COD_PROVINCIA,p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION PAI_DESCRIP
        FROM localidades l, municipiosxcomarca m, comarcas co, provincias p, regiones r, paises pa
        where m.COD_MUNICIPIO = l.COD_MUNICIPIO AND
            co.COD_COMARCA = m.COD_COMARCA AND
            p.COD_PROVINCIA = co.COD_PROVINCIA AND
            r.COD_REGION = p.COD_REGION AND
            pa.COD_NUM = r.COD_PAIS AND (";

        $or = "";
        while ($row = mysqli_fetch_array($locDescrip)){
            $buscarCP .= $or."COD_LOCALIDAD = ".$row['COD_LOCALIDAD']." ";
            $or = " OR ";
        }
        $buscarCP .= ") group by l.COD_LOCALIDAD,l.DESCRIPCION LOC_DESCRIP, m.COD_MUNICIPIO,m.DESCRIPCION, co.COD_COMARCA,co.DESCRIPCION, p.COD_PROVINCIA,p.DESCRIPCION, r.COD_REGION, r.DESCRIPCION, pa.COD_NUM, pa.DESCRIPCION 
                        order by l.COD_LOCALIDAD";

        $caminoCP = mysqli_query($con, $buscarCP);
        
        $urlIndexCP = "indexCP.php?";

        if ($caminoCP) {
            $row_cnt = mysqli_num_rows($caminoCP);
            if ($row_cnt > 0){
                if ($row_cnt == 1){
                    while ($row = mysqli_fetch_array($caminoCP)){
                        $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                        $urlIndexCP.='&cod_descripcion='.$row['LOC_DESCRIP'];
                        $urlIndexCP.='&pais='.$row['COD_NUM']."&";
                        $urlIndexCP.="region=".$row['COD_REGION'];
                        $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                        $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                        $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                        echo ('<script type="text/javascript">'."window.location.href = '$urlIndexCP'</script>");
                    }
                } else {
                    // Armamos el desplegable con las localidades obtenidas...
                    $html  = '<div class="col border form-check-inline">';
                    $html .= '<select class="form-select form-select-sm" id="selLOC" name="selLOC" onchange="ShowSelLOC();">';
                    $html .= '<option value="" selected>Seleccione Localidad</option>';

                    while($row = mysqli_fetch_array($caminoCP)){	
                        $urlIndexCP = "indexCP.php?";
                        $urlIndexCP.='cod_localidad='.$row['COD_LOCALIDAD'];
                        $urlIndexCP.="&cod_descripcion=".urlencode($row['LOC_DESCRIP']);
                        $urlIndexCP.='&pais='.$row['COD_NUM'];
                        $urlIndexCP.="&region=".$row['COD_REGION'];
                        $urlIndexCP.='&provincia='.$row['COD_PROVINCIA'];
                        $urlIndexCP.='&comarca='.$row['COD_COMARCA'];
                        $urlIndexCP.='&municipio='.$row['COD_MUNICIPIO'];
                        $html .= '<option class="fs-6" value='.$urlIndexCP.'>'.$row["LOC_DESCRIP"]."-".$row['PAI_DESCRIP'].'</option>';
                    }
                    $html .= '</select>'; 
                    $html .= '</div>';
                    echo $html;
                }
            } else {
                echo ("<script>alert('CODIGO POSTAL ".$locBuscar." NO EXISTE. Intenta de nuevo.');</script>");
                echo ('<script type="text/javascript">'."window.location.href = 'indexCP.php'</script>");
            }
        } else {
            echo ("<script>alert('CODIGO POSTAL ".$locBuscar." NO EXISTE. Intenta de nuevo.');</script>");
            echo ('<script type="text/javascript">'."window.location.href = 'indexCP.php'</script>");
        }
    }
