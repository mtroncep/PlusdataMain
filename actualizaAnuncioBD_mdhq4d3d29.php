<?php
session_start();
/* Connect To Database*/
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$log="N";
$marcelo="";
$select='select * from datosformulario where ';
$and="";
$aux=false;

$cod_localidad="";

if (isset($_SESSION['cod_localidad'])){
	$cod_localidad=$_SESSION['cod_localidad'];
}

$moneda = "";
if (isset($_SESSION['moneda'])){
	$moneda=$_SESSION['moneda'];
}

$_POST = $_SESSION['datosformulario'];
//error_log("%%%%% ActualizaAnuncioBD Esto vino en el POST: ",var_dump($_POST),0);
//return;

if($_POST)
	{
	if ($_SESSION['operacion'] === 'M'){
// Anuncios para publicacion
		$delete = "delete from datosformulario where nroanuncio = '".$_SESSION['anuncio']."' 
		and nroanunciante = ".$_SESSION['anunciante'];
		$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);

// Anuncios para búsquedas
        $estadoAviso = Funciones::obtenerEstadoAviso($con, $_SESSION['anuncio']);
        
        $deletebusq = "delete from datosbusquedasformulario where nroanuncio = '".$_SESSION['anuncio']."' 
		and nroanunciante = ".$_SESSION['anunciante'];
		$aux=Funciones::actualizarAnuncioBD($deletebusq, $log, $con);
	}
	
    $nroanuncio=0;
    
    $cod_pais=0;
    $cod_region=0;
    $cod_provincia=0;
    $cod_comarca=0;
    $cod_municipio=0;
    $cod_localidad=0;
    $cod_barrio=0;
    $cod_nucleo=0;
    $cod_manzana=0;
    
    $cp='';
    $nroanunciante=0;
    $datosplanosanuncio = '';
    $anunciosSel = '';
    
    if ($_SESSION['operacion'] == 'B'){
//        var_dump($_POST);
//        echo "<br>";
//        echo("Tipo operación  : ".$_SESSION['operacion']);
//        echo("Tipo de búsqueda: ".$_SESSION['tipoBusqueda']);
//        return;
        $_SESSION['busquedaAGuardar']=$_POST;
        
        if (isset($_SESSION['tipoBusqueda'])){
            $_SESSION['tipoBusquedaAGuardar'] = $_SESSION['tipoBusqueda'];
        } else {
            $_SESSION['tipoBusquedaAGuardar'] = " ";
        }
        
        $anunciosSel=buscarAnuncios($_POST, $con);
//        return;
//        return;
//        echo("Buscar anuncios: ".$anunciosSel);
//        echo '<br>';
        $_SESSION['seleccionados'] = $anunciosSel;
        echo ('<script type="text/javascript">'.
        "window.location.href = 'procesarSeleccionados_new.php'</script>");
    }   
    
	foreach ($_POST as $clave=>$valor)
   		{
//        var_dump($_POST);
//        return;
        $vacio = ' ';
		if ( (($valor) || $clave === "valor_checkbox_02A_2" ) && $clave != "queSubmito"){
			if ($_SESSION['operacion'] == 'A' || $_SESSION['operacion'] == 'M'){
				$prefijo=substr($clave,0,5);
				if (substr($clave,0,5) === 'ubic_'){
					$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."',".'"'.Funciones::buscarIdUbicacion($clave, $valor, $con).'"'.",'".$valor."', ".$_SESSION['anunciante'].')';

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
                            if (strpos($valor, ",") !== false){
                                $valor = str_replace(',','.',$valor);
                            } else {
                                $valor = str_replace('.','',$valor);
                            }
                        }
                        $insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1).
                         "', ".'"'.$valor.'"'.", '".$vacio."', ".$_SESSION['anunciante'].")";

                        if ($clave === 'valor_checkbox_09_32'){
                            $cp = $valor;
                        }
                        $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".$valor."|";
                    } else {
                        $insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1).
                         "', ".'"'.$valor.'"'.", '".$vacio."', ".$_SESSION['anunciante'].")";

                        if ($clave === 'valor_checkbox_09_32'){
                            $cp = $valor;
                        }
                        $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".$valor."|";                        
                    }
                }
//                error_log("INSERT: ".$insert,0);
				$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
			} else {
                }
            }
		} // Fin foreach
    
        if ($_SESSION['operacion'] === 'A' || $_SESSION['operacion'] === 'M'){
            $nroanuncio=$_SESSION['anuncio'];
            $nroanunciante=$_SESSION['anunciante'];

            // Para actualizar tabla datosformulario con los posibles comentarios agregados por el anunciante
            $updateComentariosAnunciante = "update datosformulario d, mensajesanunciantetmp m set d.mensajedelanunciante = m.mensaje WHERE d.nroanuncio = m.nroanuncio and d.campo = m.idposicion";
            $updTmp=Funciones::actualizarAnuncioBD($updateComentariosAnunciante, $log, $con);

            // Para borrar los mensajes temporales de anuncios...
            $deleteMensajesanunciantetmp = "delete from mensajesanunciantetmp where nroanuncio = ".$nroanuncio;
            $deleTmp=Funciones::actualizarAnuncioBD($deleteMensajesanunciantetmp, $log, $con);
            
            if ($_SESSION['operacion'] === 'A' ){ 
                // Para liberar el numero de anuncio reservado al incio de la carga de avisos...
                $borrarReservaAnuncio = "delete from datosbusquedasformulario where nroanuncio =".$nroanuncio;
                $borrAux=Funciones::actualizarAnuncioBD($borrarReservaAnuncio, $log, $con);
                
                // Para insertar el anuncio en la tabla de búquedas...
                $insertbusq = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_localidad,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados,fechaingreso,estado) values (".$nroanuncio.",".$cod_pais.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_localidad.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.",'".$cp."',".$nroanunciante.",".'"'.$datosplanosanuncio.'"'.",CURRENT_TIMESTAMP, 1)";
                
                // Para habilitar el anuncio para que comience a almacenar estadisticas de seleccion...
                $insertDatosAnuncios = "insert into datosanuncios
                (nroanuncio) values ($nroanuncio)";
                $aux=Funciones::actualizarAnuncioBD($insertDatosAnuncios, $log, $con);
            } else {
                $insertbusq = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_localidad,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados,fechaingreso,estado) values (".$nroanuncio.",".$cod_pais.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_localidad.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.",'".$cp."',".$nroanunciante.",".'"'.$datosplanosanuncio.'"'.",CURRENT_TIMESTAMP, ".$estadoAviso.")";
            }
            $aux=Funciones::actualizarAnuncioBD($insertbusq, $log, $con);
/*
            if ($_SESSION['operacion'] === 'A' ){
                $aux=Funciones::actualizarAnuncioBD($insertDatosAnuncios, $log, $con);
            }
*/            
            if ($_SESSION['operacion'] === 'A'){
                $nroAnuncianteInmob = Funciones::obtenerCodigoOficina($con,$nroanunciante);
                $cargarAvisoComoInactivo = "insert into ctacteavisos (cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo,estado,nroanuncianteinmob) values 
                (".$cod_pais.",".$nroanunciante.",".$nroanuncio.",CURRENT_DATE, NULL, 1,'".$nroAnuncianteInmob."')";
                $ingresarainctivo = mysqli_query($con,$cargarAvisoComoInactivo);
//                error_log("================Anunciante inmob: ".$nroAnuncianteInmob);
//                error_log("================INSERT texto: ".$cargarAvisoComoInactivo);
//                error_log("================RESULTADO INSERT texto: ".$ingresarainctivo);
            }
        }

		if ($aux){
            $alertStr="<script>alert('Procesando anuncio Nro: ".$_SESSION['anuncio']."')</script>";
			echo ($alertStr);
            
            if ($_SESSION['operacion'] === "A"){
                Funciones::cartelAnuncio($_SESSION['anunciante'],$_SESSION['anuncio'],$con);
                echo "<script>
                if(confirm('¿Agrega imagenes ahora?')){
                document.location='indexImagenes.php';}
                </script>";
//                echo ('<script> window.location.href = "indexImagenes.php"; </script>');
            }

            if ($log === "N"){
                    if ($_SESSION['operacion'] === "A" || $_SESSION['operacion'] === "M"){
                        echo ('<script type="text/javascript">'."window.location.href = 'indexAnuncios.php';</script>");
                    }
				}
            }   
	
		if (isset($_SESSION['operacionCP']) && ($_SESSION['operacionCP'] == "A")){
            $borrarCP="delete from codigospostales where cp='".$_SESSION['cp']."' and cod_localidad=".$cod_localidad;
            $aux=Funciones::actualizarAnuncioBD($borrarCP, $log, $con);
			$aux=Funciones::actualizarCP_POST($_SESSION['cp'], $cod_localidad, $_POST, $con);
		}
	}

function buscarIdDetalle($detalle, $con){
	$count_query = mysqli_query($con,"SELECT count(*) AS numrows FROM detalles where detalle='$detalle'");
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}

	$query = mysqli_query($con,"SELECT id FROM detalles where detalle='$detalle'");
	if ($numrows>0){
		$row = mysqli_fetch_array($query);
		return $row['id'];
	}else{
		return "";
	}
}

function buscarAnuncios($POST, $con){
    //Buscar pais, localidad y código postal
    if (isset($_SESSION['tipoBusqueda'])){
        if ($_SESSION['tipoBusqueda'] == "L"){
            //var_dump($POST);
            foreach ($POST as $clave=>$valor){
                if ($valor <> 0 or $valor <> ""){
                    if (substr($clave,0,5) === 'ubic_'){
                        if ($clave === 'ubic_pais_id'){
                            $cod_pais = $valor;
//                            echo "Clave valor: ".$cod_pais.'<br>';
                        }
                        if ($clave === 'ubic_localidad_id'){
                            $cod_localidad = $valor;
//                            echo "Clave valor: ".$cod_localidad.'<br>';
                        }

                    } else {
                        if ($clave === 'valor_checkbox_09_32'){
                            $cp = $valor;
//                            echo "Clave valor: ".$cp.'<br>';
//                            echo "Clave valor: ".$clave.' => '.$valor.'<br>';
                        }
                    }
                }
            }

            if(!Funciones::esAlfabetico($cp)){
                $select = "select * from datosbusquedasformulario where cod_pais=".$cod_pais." and estado=1"." and cod_localidad=".$cod_localidad." and cp='".$cp."' order by nroanuncio";
            } else {
                $select = "select * from datosbusquedasformulario where cod_pais=".$cod_pais." and cod_localidad=".$cod_localidad." and estado=1".
                    " order by nroanuncio";
            }
 //           echo "Select 1: ".$select."<br>";
        } else {
            if ($_SESSION['tipoBusqueda'] == "C"){
                foreach ($POST as $clave=>$valor){
                    if ($clave == 'valor_checkbox_09_32'){
                        $cp = $valor;
                        break;
                    }
                }
                $select = "select * from datosbusquedasformulario where estado=1 and cp='".$cp."' order by nroanuncio";
//                echo "Select 2: ".$select."<br>";
            }
        }
    } else {
// Acá se busca por ubicación!!!...
        $_SESSION['tipoBusqueda'] = "";
        foreach ($POST as $clave=>$valor){
            if ($valor <> 0 && $clave != "queSubmito"){
                if (substr($clave,0,5) === 'ubic_'){
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
                }
//              echo $clave.' => '.$valor.'<br>';
            }
        }
        
        if (!empty($cod_manzana)){
            $campo = "cod_manzana";
            $valorCampo = $cod_manzana;
        } else {
            if (!empty($cod_nucleo)){
                $campo = "cod_nucleo";
                $valorCampo = $cod_nucleo;
            } else {
                if (!empty($cod_barrio)){
                    $campo = "cod_barrio";
                    $valorCampo = $cod_barrio;
                } else {
                    if (!empty($cod_localidad)){
                        $campo = "cod_localidad";
                        $valorCampo = $cod_localidad;
                } else {
                    if (!empty($cod_municipio)){
                        $campo = "cod_municipio";
                        $valorCampo = $cod_municipio;
                    } else {
                        if (!empty($cod_comarca)){
                            $campo = "cod_comarca";
                            $valorCampo = $cod_comarca;
                        } else {
                            if (!empty($cod_provincia)){
                                $campo = "cod_provincia";
                                $valorCampo = $cod_provincia;
                            } else {
                                if (!empty($cod_region)){
                                    $campo = "cod_region";
                                    $valorCampo = $cod_region;
                                } else {
                                    if (!empty($cod_pais)){
                                        $campo = "cod_pais";
                                        $valorCampo = $cod_pais;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $select = "select * from datosbusquedasformulario where estado = 1 and ".$campo." = ".$valorCampo." order by nroanuncio";
//        echo "Select 3: ".$select."<br>";
    }
    }
//    error_log("&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&",0);
//    error_log("Select: ".$select,0);
//    echo ("<br>Tipo búsqueda: ".$_SESSION['tipoBusqueda']."<br>");
//    echo "Select: ".$select."<br>";
//    return;
    $query = mysqli_query($con,$select);
    if (!$query){
//        echo "Sali por SELECT incorrecto".$select;
        $anunciosSel = "";
        return $anunciosSel;
    }

//    $cantPublicaciones = mysqli_num_rows($query);
    $cantPublicaciones = 0;

    $anunciosSel='';
    $separadorAnuncios='';
    
    $estaValorDesde = 0;
    $valorDesde='';
    
    while($row = mysqli_fetch_array($query)){
//        echo "<br>";
//        echo "Anuncio: ".$row['nroanuncio']."<br>";
        foreach ($POST as $clave => $valor){
//            echo "Clave/valor: ".$clave."/".$valor."<br>";
            if ($valor <> "" && $clave != "queSubmito"){
//                if ((substr($clave,0,5) <> 'ubic_') /*&& (strpos($clave,'_09_') === false)*/){
                if ((substr($clave,0,5) <> 'ubic_') && ($clave <> 'busqueda_cp') &&
                    ($_SESSION['tipoBusqueda'] === "" || (strpos($clave,'_09_') === false))){
//                    echo "Clave/valor: ".$clave."/".$valor."<br>";
//                if ((substr($clave,0,5) <> 'ubic_')){
                    if (substr($clave,0,1) <> 'v'){
                        $estaValorDesde = 0;
                        $valorDesde='';
                        $existe=buscarPostEnFormulario($clave,$valor,$row['datosasociados']);
                        if (!$existe){
                            break;
                        }
                    } else {
//                        echo "Clave: ".$clave."<br>";
                        if ($estaValorDesde == 0){
                            $estaValorDesde=1;
                            $valorDesde=str_replace('.','',$valor);
                            $claveDesde=$clave;
                            $existe=0;
//                            echo "ValorDesde: ".$valorDesde."<br>";
                        } else {
                            if ($valor !== null){
                                $valor = str_replace('.','',$valor);
                                if ($claveDesde === "valor_checkbox_ubicd_planta_id"){
                                    $claveDesde = "ubicd_planta_id";
                                }
//                                echo "Desde: ".intval($valorDesde).", hasta: ".intval($valor)."<br>";

                                $existe=buscarValorEnFormulario($claveDesde,$valorDesde,$valor,$row['datosasociados']);
                                if (!$existe){
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    if ($clave === 'valor_checkbox_09_32' && $_SESSION['tipoBusqueda'] === 'C'){
//                        echo "Clave/valor: ".$clave."/".$valor."<br>";
                        $existe=buscarPostCPEnFormulario($clave,$valor,$row['datosasociados']);
//                        echo "Resultado búsqueda: ".$existe.'<br>';
//                        print_r($row['datosasociados']);
                        if (!$existe){
                            break;
                        }
                    }
            }
            
        } //Fin ForEach
    }

    if ($existe) {
//        echo "Resultado búsqueda: ".$existe.'<br>';
        $datosAnuncio=Funciones::armarDatosAnuncio($row['datosasociados'], $POST, $con);
        $anunciosSel.=$row['nroanuncio']."#".$row['nroanunciante'].'#'.$datosAnuncio.'|';
        $cantPublicaciones=$cantPublicaciones + 1;
    }
}
//    echo "Anuncios seleccionados: ".$anunciosSel;
    $_SESSION['cantPublicaciones'] = $cantPublicaciones;
    return $anunciosSel;
}

function buscarPostEnFormulario($clave,$valor,$datosasociados){
    if ($clave === "checkbox_ubicd_planta_id"){
        return true;
    }

    $items = explode("|",$datosasociados);
    
    foreach($items as $item){
        $claveValor=explode(",",$item);
//        if ((substr($claveValor[0],0,5) <> 'ubic_') /* && (strpos($claveValor[0],'_09_') === false)*/)
        if ((substr($clave,0,5) <> 'ubic_') && 
            ($_SESSION['tipoBusqueda'] === "" || (strpos($clave,'_09_') === false)))
        {
//            echo "ClaveA: ".$clave." Valor: ".$valor." ClaveValor: ".$claveValor[0]."<br>";
            if ($clave === $claveValor[0]){
                if ($claveValor[1] <> 'v'){
                    if ($valor === $claveValor[2]){
//                        echo "Clave: ".$clave." Valor: ".$valor." ClaveValor: ".$claveValor[2]."<br>";
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    if ($valor >= $claveValor[2]){
                    return 1;
                } else {
                    return 0;                
                    }
                }
            }
        }
    }
    return 0;
}

function buscarPostCPEnFormulario($clave,$valor,$datosasociados){
    $items = explode("|",$datosasociados);
    
    foreach($items as $item){
        $claveValor=explode(",",$item);
//        if ((substr($claveValor[0],0,5) <> 'ubic_') /* && (strpos($claveValor[0],'_09_') === false)*/)
        if ((substr($clave,0,5) <> 'ubic_') && ($_SESSION['tipoBusqueda'] === "C"))
        {
//            echo "ClaveA: ".$clave." Valor: ".$valor." ClaveValor: ".$claveValor[0]."<br>";
            if ($clave === $claveValor[0]){
                if ($claveValor[1] === 'v'){
                    if ($valor >= $claveValor[2]){
                    return 1;
                    }
                }
            }
        }
    }
    return 0;
}

function buscarValorEnFormulario($clave,$valorDesde,$valorHasta,$datosasociados){
    $items = explode("|",$datosasociados);
    
    foreach($items as $item){
        $claveValor=explode(",",$item);
//        if ((substr($claveValor[0],0,5) <> 'ubic_') /* && (strpos($claveValor[0],'_09_') === false)*/){
          if ((substr($clave,0,5) <> 'ubic_') && 
            ($_SESSION['tipoBusqueda'] === "" || (strpos($clave,'_09_') === false))){
//        if ((substr($claveValor[0],0,5) <> 'ubic_')){
            /*
            echo "Clave BVF: ".$clave."<br>";
            echo "Clave FRM: ".$claveValor[0]."<br>";
            echo "V.dsd/hst: ".$valorDesde."/".$valorHasta."<br>";
            echo "Valor.Cmp: ".$claveValor[2]."<br>";
            */
//            echo "<br> Publicacion: ".intval($claveValor[2]).", desde: ".intval($valorDesde).", hasta: ".intval($valorHasta);
            if ($clave === $claveValor[0]){
                if ((intval($claveValor[2]) >= intval($valorDesde)) && (intval($claveValor[2]) <= intval($valorHasta))){
//                    echo "Encontre valor: ".$claveValor[2]."<br>";
                    $existe=1;
                } else {
                    $existe=0;
                    break;
                }
                return $existe;
            }
        }
    }
}
?>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>