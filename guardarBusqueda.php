<!DOCTYPE html>
<?php
session_start();
/* Connect To Database*/
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

if ($_SESSION['cantPublicaciones'] === 0){
    if (isset($_SESSION['busquedaPublica'])){
        echo ("<script>alert('PARA GUARDAR LA BÚSQUEDA, REALICE UNA NUEVA o POR FAVOR INGRESE al SITIO o REGISTRESE. GRACIAS');</script>");
        echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
    }
}

if (isset($_SESSION['busquedaAGuardar'])){
	$_POSTT=$_SESSION['busquedaAGuardar'];
    $tipoBusqueda=$_SESSION['tipoBusquedaAGuardar'];
} else {
    return;
}
$jsonTexto=json_encode($_POSTT, JSON_UNESCAPED_UNICODE); 

$nroBusqueda=Funciones::obtenerNroBusqueda($con);
$insert="insert into busquedasguardadas (nrobusqueda,anunciante,mailanunciante,fechaguardada,fechavigencia,criteriobusqueda, tipobusqueda, paisproceso) values 
(".$nroBusqueda.",".$_SESSION['anunciante'].",'".$_SESSION['emailReg']."', CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 2 month), '".$jsonTexto."','".$tipoBusqueda."',".$_SESSION['codPais'].")";
//echo "<br>".$insert;
//return;

$aux=Funciones::actualizarAnuncioBD($insert, "N", $con);

if ($aux){
    $aux1=cargarDatosBusquedas($con, $nroBusqueda, $_POSTT);
}
if ($aux1){
    echo ("<script>alert('BUSQUEDA GUARDADA CORRECTAMENTE. INGRESA A LA BUSQUEDA DIARIA. SE INFORMARA POR MAIL CUANDO SE ENCUENTRE INFORMACION. LA VIGENCIA DE LA BUSQUEDA ES DE DOS MESES, POSIBILIDAD DE EXTENDERLA.');</script>");
} else {
    echo ("<script>alert('BUSQUEDA SIN GUARDAR!!! SE PRODUJO UN ERROR INTERNO.');</script>");
}

echo ('<script type="text/javascript">'.
"window.location.href = 'pruebaDetalles.php?operacion=B'</script>");

function cargarDatosBusquedas($con, $nroBusqueda, $POST){
    $datosplanosanuncio="";
    
	foreach ($POST as $clave=>$valor)
    {
    $vacio = ' ';
    if ( (($valor) || $clave === "valor_checkbox_02A_2" ) && $clave != "queSubmito"){
            $prefijo=substr($clave,0,4);
            if (substr($clave,0,5) === 'ubic_'){
                $insert = "insert into datosbusquedas (nrobusqueda,campo,tipocampo,valor,codigoubicacion) values ('".$nroBusqueda."','".$clave."', '".substr($prefijo,0,1)."',".'"'.Funciones::buscarIdUbicacion($clave, $valor, $con).'"'.",'".$valor."')";

                if ($clave === 'ubic_pais_id'){
                    $cod_pais = $valor;
                }
                if ($clave === 'ubic_region_id'){
                    $cod_region = $valor;
                }
                if ($clave === 'ubic_provincia_id'){
                    $cod_provincia = $valor;
                }
                if ($clave === 'ubic_comarca_id'){
                    $cod_comarca = $valor;
                }
                if ($clave === 'ubic_municipio_id'){
                    $cod_municipio = $valor;
                }
                if ($clave === 'ubic_localidad_id'){
                    $cod_localidad = $valor;
                }
                if ($clave === 'ubic_barrio_id'){
                    $cod_barrio = $valor;
                }
                if ($clave === 'ubic_nucleo_id'){
                    $cod_nucleo = $valor;
                }
                if ($clave === 'ubic_manzana_id'){
                    $cod_manzana = $valor;
                }
                $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".Funciones::buscarIdUbicacion($clave, $valor, $con).",".$valor."|";
            } else {
                if (substr($prefijo,0,1) <> "t"){
                    if (substr($prefijo,0,1) == "v"){
                        $valor = str_replace('.','',$valor);
                    }
                    $insert = "insert into datosbusquedas (nrobusqueda,campo,tipocampo,valor,codigoubicacion) values ('".$nroBusqueda."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', '".$vacio."')";

                    if ($clave === 'valor_checkbox_09_32'){
                        $cp = $valor;
                    }
                    $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".$valor."|";
                } else {
                    $insert = "insert into datosbusquedas (nrobusqueda,campo,tipocampo,valor,codigoubicacion) values ('".$nroBusqueda."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', '".$vacio."')";

                    if ($clave === 'valor_checkbox_09_32'){
                        $cp = $valor;
                    }
                    $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".$valor."|";                        
                }
            }
            $aux=Funciones::actualizarAnuncioBD($insert, "N", $con);
        }
    } // Fin foreach
    $update="update busquedasguardadas set datosasociados=".'"'.$datosplanosanuncio.'"'." where nrobusqueda = ".$nroBusqueda;
    $aux=Funciones::actualizarAnuncioBD($update, "N", $con);

    if ($aux){
        return 1;
    } else {
        echo ("<script>alert('SE PRODUJO UN ERROR al actualizar la busqueda con datos busquedas.');</script>");
    }
} // Fin function
