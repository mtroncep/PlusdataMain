<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$query=$con->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ $paises[]=$r; }


if (isset($_SESSION['seleccion'])){
	$seleccion = $_SESSION['seleccion'];
}

$operacion="";
if (isset($_SESSION['operacion'])){
	$operacion = $_SESSION['operacion'];
}

$locBuscarCP='%';
if (isset($_REQUEST['locBuscarCP'])){
	$locBuscarCP = $_REQUEST['locBuscarCP'];
}

$lineasSel = "";
if (isset($_REQUEST['lineasSel'])){
	$lineasSel = $_REQUEST['lineasSel'];
}

//$_SESSION['tipoBusqueda']=$_REQUEST['tipoBusqueda'];

$locBuscar = $_REQUEST['locBuscar'];
$soloCP = $_REQUEST['soloCP'];
$porciento = $_REQUEST['porciento'];
$html='';
$camino[]="";

//echo "Ant LocBuscar: ".$locBuscar."<br>";
//echo "Ant LocBuscarCP: ".$locBuscarCP."<br>";
//echo "Ant Porciento: ".$porciento."<br>";
//return;
//error_log("Estamos en buscarLocalidadyCP.php antes de obtener camino.",0);
$camino = Funciones::obtenerCaminoLocalidad($locBuscar, $locBuscarCP, $porciento, $con);
error_log("La devolucion es: ".is_array($camino),0);
if (is_array($camino)){
    $tope = count($camino);
    error_log("La cantidad de elementos es: ".is_array($camino),0);
}
//echo "Camino: ".var_dump($camino)."<br>";
//echo "Des LocBuscar: ".$locBuscar."<br>";
//echo "Des LocBuscarCP: ".$locBuscarCP."<br>";
//echo "Des Porciento: ".$porciento."<br>";
//echo "Tope: ".$tope."<br>";
//return;
//echo "Total camino: ".$tope."<br>";
if (!empty($camino)){
    if ($_SESSION['operacion'] == 'B'){
        $_SESSION['i_desde'] = 0;
    } else {
        $_SESSION['i_desde'] = 0;
    }
	if ($camino[$_SESSION['i_desde']] <> -1){
//        var_dump($camino[$_SESSION['$i_desde']]);
//        return;
// Cargamos la lista de CP y Localidades...
        $html  = '<div class="col border form-check-inline">';
        if ($lineasSel <> ""){
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="selCP" name="selCP" size=10>';
        } else {
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="selCP" name="selCP"';
        }
        if ($porciento == "%"){
            $html .= '<option value="" selected>Elija localidad o CP</option>';
        } else {
            $html .= '<option value="">Elija localidad o CP</option>';
        }
		for ($i = $_SESSION['i_desde']; $i < $tope; ++$i){
            $pos_par = strpos($camino[$i]["LOC_DESCRIP"],"(");
            $locOrigen=funciones::eliminar_acentos($camino[$i]["LOC_DESCRIP"]);
            $locBusco=funciones::eliminar_acentos($locBuscar);
            $pos_loc = stripos($locOrigen,$locBusco);
            $locOK=0;
            if ($pos_par === false || $pos_par === 0){
                $pos_par=0;
                $locOK=1;
            } else {
                if ($pos_loc < $pos_par){
                    $locOK=1;
                } else {
                }
            }
            if($locOK){
                if ($porciento == "%"){
                    $html .= '<option class="fs-6" value='.$camino[$i]["CP"].'+'.$camino[$i]["COD_LOCALIDAD"].'>'.$camino[$i]["PRO_DESCRIP"].'_'.$camino[$i]["LOC_DESCRIP"].'_'.$camino[$i]["CP"].'</option>';
                    } else {
                    $html .= '<option class="fs-6" value='.$camino[$i]["CP"].'+'.$camino[$i]["COD_LOCALIDAD"].' selected>'.$camino[$i]["PRO_DESCRIP"].'_'.$camino[$i]["LOC_DESCRIP"].'_'.$camino[$i]["CP"].'</option>';
                }
            }
        }
    	$html .= '</select>'; 
    	$html .= '</div>';

        if ($soloCP == "S"){
            echo $html;
			return;
			}		

// Armamos las ubicaciones...
//        var_dump($camino);
        $html .= '<div>';
        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">País</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_pais_id" name="ubic_pais_id" disabled>';
        $html .= '<option value="" class="ubicPais">Elija</option>';
//        $desde=1;
        $desde=0;
        foreach($paises as $c):
            if($c->COD_NUM == $camino[$desde]['COD_PAIS'] ){
                $html .= '<option selected class="fs-6" value="'.$c->COD_NUM.'">'.$c->DESCRIPCION.'</option>';
            } else {
                $html .= '<option class="fs-6" value="'.$c->COD_NUM.'">'.$c->DESCRIPCION.'</option>';
            }
        endforeach;
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">Región</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id" 
        disabled>';
        $html .= Funciones::cargarUbicacionesSelectCP("regiones","COD_PAIS","COD_REGION",$camino[$desde]['COD_PAIS'],$camino[$desde]['REG_DESCRIP'],$con);
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">Provincia</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_provincia_id" name="ubic_provincia_id"  disabled>';
        $html .= Funciones::cargarUbicacionesSelectCP("provincias","COD_REGION","COD_PROVINCIA",$camino[$desde]['COD_REGION'],$camino[$desde]['PRO_DESCRIP'],$con);
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">Comarca</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_comarca_id" name="ubic_comarca_id" 
        disabled>';
        $html .= Funciones::cargarUbicacionesSelectCP("comarcas","COD_PROVINCIA","COD_COMARCA",$camino[$desde]['COD_PROVINCIA'],$camino[$desde]['COM_DESCRIP'],$con);
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">Municipio</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_municipio_id" name="ubic_municipio_id" disabled>';
        $html .= Funciones::cargarUbicacionesSelectCP("municipiosxcomarca","COD_COMARCA","COD_MUNICIPIO",$camino[$desde]['COD_COMARCA'],$camino[$desde]['MUN_DESCRIP'],$con);
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="col border form-check-inline">';
        $html .= '<p class="mb-0">Localidad</p>';
        $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_localidad_id" name="ubic_localidad_id" disabled>';
        $html .= Funciones::cargarUbicacionesSelectCP("localidades","COD_MUNICIPIO","COD_LOCALIDAD",$camino[$desde]['COD_MUNICIPIO'],
        $camino[$desde]['LOC_DESCRIP'],$con);
        $html .= '</select>';
        $html .= '</div>';

        if ($operacion <> "B"){
            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Barrio</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id">';
            $html .= Funciones::cargarUbicacionesSelectCP("barrios","COD_LOCALIDAD","COD_BARRIO",$camino[$desde]['COD_LOCALIDAD'],
            $camino[$desde]['LOC_DESCRIP'],$con);
            $html .= '</select>';
            $html .= '</div>';

            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Lugar</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id">';
            $html .= '<option value=""></option>';
            $html .= '</select>';
            $html .= '</div>';

            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Manzana</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_manzana_id" name="ubic_manzana_id">';
            $html .= '<option value=""></option>';
            $html .= '</select>';
            $html .= '</div>';
        } else {
            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Barrio</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id" disabled>';
            $html .= Funciones::cargarUbicacionesSelectCP("barrios","COD_LOCALIDAD","COD_BARRIO",$camino[$desde]['COD_LOCALIDAD'],
            $camino[$desde]['LOC_DESCRIP'],$con);
            $html .= '</select>';
            $html .= '</div>';

            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Lugar</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" disabled>';
            $html .= '<option value=""></option>';
            $html .= '</select>';
            $html .= '</div>';

            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Manzana</p>';
            $html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_manzana_id" name="ubic_manzana_id" disabled>';
            $html .= '<option value=""></option>';
            $html .= '</select>';
            $html .= '</div>';
        }

        if ($operacion <> "B"){
            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Calle</p>';
            $html .= '<input class="" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" required></input>';
            $html .= '</div>';

            $html .= '<div class="col border form-check-inline">';
            $html .= '<p class="mb-0">Número</p>';
            $html .= '<input class="" type="text" id="ubicd_numero_id" name="ubicd_numero_id" required></input>';
            $html .= '</div>';

            if(Funciones::verTipoPropiedad($con,$_REQUEST['tipopropiedad'])){
                $html .= '<div class="col border form-check-inline">
                            <p class="mb-0">Planta</p>
                            <input class="form-control" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value=""> '; 
                $html .= '</div>';
            }
        } else {
        }
        
        $html .= '</div>';

        echo $html;		
	
		} else {
			$html .= '<div>';
			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">País</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_pais_id" name="ubic_pais_id" disabled>';
			$html .= '<option value="" class="ubicPais">Elija</option>';
			$desde=0;
			if ($camino[$_SESSION['i_desde']] == -1){
				$desde=1;
			}
			foreach($paises as $c):
				if($c->COD_NUM == $camino[$desde]['COD_PAIS'] ){
					$html .= '<option selected class="fs-6" value="'.$c->COD_NUM.'">'.$c->DESCRIPCION.'</option>';
				} else {
					$html .= '<option class="fs-6" value="'.$c->COD_NUM.'">'.$c->DESCRIPCION.'</option>';
				}
			endforeach;
			$html .= '</select>';
			$html .= '</div>';
			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Región</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("regiones","COD_PAIS","COD_REGION",$camino[$desde]['COD_PAIS'],$camino[$desde]['REG_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Provincia</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_provincia_id" name="ubic_provincia_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("provincias","COD_REGION","COD_PROVINCIA",$camino[$desde]['COD_REGION'],$camino[$desde]['PRO_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Comarca</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_comarca_id" name="ubic_comarca_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("comarcas","COD_PROVINCIA","COD_COMARCA",$camino[$desde]['COD_PROVINCIA'],$camino[$desde]['COM_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Municipio</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_municipio_id" name="ubic_municipio_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("municipiosxcomarca","COD_COMARCA","COD_MUNICIPIO",$camino[$desde]['COD_COMARCA'],$camino[$desde]['MUN_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Localidad</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_localidad_id" name="ubic_localidad_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("localidades","COD_MUNICIPIO","COD_LOCALIDAD",$camino[$desde]['COD_MUNICIPIO'],
			$camino[$desde]['LOC_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Barrio</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id" disabled>';
			$html .= Funciones::cargarUbicacionesSelectCP("barrios","COD_LOCALIDAD","COD_BARRIO",$camino[$desde]['COD_LOCALIDAD'],
			$camino[$desde]['LOC_DESCRIP'],$con);
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Lugar</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" disabled>';
			$html .= '<option value=""></option>';
			$html .= '</select>';
			$html .= '</div>';

			$html .= '<div class="col border form-check-inline">';
			$html .= '<p class="mb-0">Manzana</p>';
			$html .= '<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_manzana_id" name="ubic_manzana_id" disabled>';
			$html .= '<option value=""></option>';
			$html .= '</select>';
			$html .= '</div>';

            if ($operacion <> "B"){
                $html .= '<div class="col border form-check-inline">';
                $html .= '<p class="mb-0">Calle</p>';
                $html .= '<input class="" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" required></input>';
                $html .= '</div>';

                $html .= '<div class="col border form-check-inline">';
                $html .= '<p class="mb-0">Número</p>';
                $html .= '<input class="" type="text" id="ubicd_numero_id" name="ubicd_numero_id" required></input>';
                $html .= '</div>';

                if(Funciones::verTipoPropiedad($con,$_REQUEST["tipopropiedad"])){;
                    $html .= '<div class="col form-check-inline">;
                            <p class="mb-0">Planta</p>;
                            <input class="form-control" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value="" > '; 
                    $html .= '</div>';
                }

            } else {
            }
        
			$html .= '</div>';

        echo $html;		
	}
} else {
    if ($locBuscarCP <> '%'){
        $_SESSION['tipoBusqueda'] = "C";

        $html  = '<div class="col form-check-inline">';
        $html .= '<p class="mb-0">En Todo Código Postal</p>';
        $html .= '<input type="text" style="text-align: center;" id="busqueda_cp" name="busqueda_cp" value="'.$locBuscarCP.'" readonly></input>';
        $html .= '</div>';

        echo $html; 
        
        } else {
            $html  = '<div class="col form-check-inline">';
            $html .= '<p>LOCALIDAD NO EXISTE</p>';
            $html .= '<input type="text" style="text-align: center;" id="busqueda_cp" name="busqueda_cp" value="Oprima Limpiar" readonly></input>';
            $html .= '</div>';
            echo $html; 

        }
    } 
?>
