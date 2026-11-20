<?php
session_start();
/* Connect To Database*/
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");
?>
<?php
if (gethostname() == "DESKTOP-6LCGB06"){
    // Entorno local
    $linkAnuncio = "localhost/HMIPrueba";
} else {
    // Entorno de producción
    $linkAnuncio = "https://plusdata.info";
}

$select="select id, nrobusqueda, anunciante,mailanunciante,fechaguardada,fechaencontrada,criteriobusqueda,datosasociados,tipobusqueda,paisproceso from busquedasguardadas where fechaencontrada is NULL";
$query = mysqli_query($con,$select);
while($row = mysqli_fetch_array($query)){
/*
    if (isset($_SESSION['tipoBusqueda'])){
        if ($row['tipobusqueda'] === " "){
            unset($_SESSION['tipoBusqueda']);
        }
    }
*/
    $_SESSION['tipoBusqueda']=$row['tipobusqueda'];
//    echo "<br><br>Identificador de búsqueda: ".$row['nrobusqueda'];
    $_POST = json_decode($row['criteriobusqueda'], true);
    echo "<br>P_POST: ".$_POST;

    $_post_datos_asociados=$row['datosasociados'];
    echo "<br>Datos asociados: ".$_post_datos_asociados;
    
    $anunciosSel=buscarAnuncios($_POST, $con);
    
    echo "<br>Vuelta de buscarAnuncios: ".$anunciosSel;

    $anunciosSelGuardado = $anunciosSel;
    
    if ($anunciosSel){
        $datosBusquedaAnuncio=Funciones::armarDatosAnuncio($row['datosasociados'], $row['criteriobusqueda'], $con);
        error_log("Criterio búsqueda: ".$datosBusquedaAnuncio,0);

        $itemsAviso = explode("#",$anunciosSel);

        $avisoPublicado=Funciones::formato_numero($itemsAviso[0], 8, 4);

//        if ($linkAnuncio === ""){
            $paisProceso = $row['paisproceso'];
            $linkVerAnuncio = "<a href='$linkAnuncio/procesarVistaPreviaAnuncio.php?elAnuncio=$itemsAviso[0]&paisproceso=$paisProceso'>consúltalo aquí</a>";
//            $linkVerAnuncio = "<a href="."procesarVistaPreviaAnuncio.php?elAnuncio=".$itemsAviso[0]."&paisproceso=".$row['paisproceso']."&datosAsociados=".$_post_datos_asociados.">Consulte el anuncio detallado aqui</a>, O desde nuestra web con el número de anuncio.";
//        } else {
//            $linkVerAnuncio = "<a href=".$linkAnuncio."procesarVistaPreviaAnuncio.php?elAnuncio=".$itemsAviso[0]."&paisproceso=".$row['paisproceso']."&datosAsociados=".$_post_datos_asociados.">Consulte el anuncio detallado aqui</a>, O desde nuestra web con el número de anuncio.";
//        }
        
        $cuerpoMail = "
        <div>
          <p align='center' width='100%' style='padding-top: 0px; font-family: Helvetica; font-size: 20px; color:#003781;'><strong>Hay un inmueble que cumple con tu pedido</strong></p>
        </div>
        <div align='center' width='100%' style='border: 1px solid black; border-radius: 5px;'>
          <p style='padding-top: 5px; font-family: Helvetica; font-size: 18px; color:#003781;'>Búsqueda Guardada Nº <strong>".$row['nrobusqueda']."</strong></p>
          <p style='font-family: Helvetica; font-size: 16px; color:#003781;'>Código de Anuncio: <strong>$avisoPublicado</strong> $linkVerAnuncio</p>
        </div>
        <div>
          <p align='center' style='margin: 15px 0px 1px 10px; font-family: Helvetica; font-size: 18px; color:#003781;'>Criterios exigidos</p>
          <div align='left'>
           <p style='margin: 0px 100px;'>".$datosBusquedaAnuncio."</p>
          </div>
        </div>
        ";
        
        echo "<br><br>";
        echo "BUSQUEDA PUBLICADA:".$row['nrobusqueda']."<br>";
        echo "ANUNCIO PUBLICADO: ".$avisoPublicado."<br>";
        echo "<br><br>";
        $update = "update busquedasguardadas set fechaencontrada=CURRENT_DATE where id=".$row['id'];
		$aux=Funciones::actualizarAnuncioBD($update, "N", $con);
        $subject = "Consulta de aviso publicado de su búqueda guardada";
        Funciones::enviarMail($row['mailanunciante'],$cuerpoMail,$subject,"");
    } else {
//       echo "<br>*** SIN RESULTADOS ***<br>";
    }
}
//echo "<br>";
//echo "<br>";
//echo "<br>*** FIN DEL PROCESO DE SELECCION Y EJECUCION DE BUSQUEDAS GUARDADAS ***<br>";

/*
    $_SESSION['seleccionados'] = $anunciosSel;
    echo ('<script type="text/javascript">'.
    "window.location.href = 'procesarSeleccionados_new.php'</script>");
*/


function buscarAnuncios($POST, $con){
//    var_dump($POST);
//    return;
//Buscar pais, localidad y código postal
if (isset($_SESSION['tipoBusqueda'])){
    echo '<br>'.'Tipo busqueda: '.$_SESSION['tipoBusqueda'];
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

        if (isset($cp)){
            if(!Funciones::esAlfabetico($cp)){
                $select = "select * from datosbusquedasformulario where cod_pais=".$cod_pais." and estado=1"." and cod_localidad=".$cod_localidad." and cp='".$cp."' order by nroanuncio";
            } else {
                $select = "select * from datosbusquedasformulario where cod_pais=".$cod_pais." and cod_localidad=".$cod_localidad." and estado=1"." order by nroanuncio";
            }
        } else {
                $select = "select * from datosbusquedasformulario where cod_pais=".$cod_pais." and estado=1"." order by nroanuncio";
        }
//            echo "<br>Select 1: ".$select."<br>";
    } else { if ($_SESSION['tipoBusqueda'] == "C"){
                foreach ($POST as $clave=>$valor){
                    if ($clave == 'valor_checkbox_09_32'){
                        $cp = $valor;
                        break;
                    }
                }
                $select = "select * from datosbusquedasformulario where estado=1 and cp='".$cp."' order by nroanuncio";
//                echo "Select 2: ".$select."<br>";
            } else {
// Acá se busca por ubicación!!!...
//        var_dump($POST);
//        echo "<br>"."Entre en búsqueda por ubicación...";
//        return;
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
              echo "<br>Clave: ".$clave.' => '."Valor: ".$valor;
        }
    }

    if (!empty($cod_manzana)){
        $campo = "cod_manzana";
        $valorCampo = $cod_manzana;
        } else { if (!empty($cod_nucleo)){
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
    } //Fin del IF 
    $select = "select * from datosbusquedasformulario where estado = 1 and ".$campo." = ".$valorCampo." order by nroanuncio";
//        echo "Select 3: ".$select."<br>";
}
}
}
    echo "<br>Select: ".$select."<br>";
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
$existe="Sin datos";
    
while($row = mysqli_fetch_array($query)){
//        echo "<br>";
//        echo "Anuncio: ".$row['nroanuncio']."<br>";
    foreach ($POST as $clave => $valor){
        if ($valor <> "" && $clave != "queSubmito"){
//                echo "Clave/valor: ".$clave."/".$valor."<br>";
            if ((substr($clave,0,5) <> 'ubic_') && (strpos($clave,'_09_') === false)){
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
            }
        }
    } // Fin del foreach
//echo '<br>¿Variable existe?: '.$existe.'<br>';
if ($existe === "Sin datos"){
    $_SESSION['cantPublicaciones'] = $cantPublicaciones;
    return $anunciosSel;
}    

if ($existe) {
    $datosAnuncio=armarDatosAnuncio($row['datosasociados'], $POST);
    $anunciosSel.=$row['nroanuncio']."#".$row['nroanunciante'].'#'.$datosAnuncio.'|';
    $cantPublicaciones=$cantPublicaciones + 1;
}
} // Fin del while...
//    echo "Anuncios seleccionados: ".$anunciosSel;
$_SESSION['cantPublicaciones'] = $cantPublicaciones;
return $anunciosSel;
} //Fin de la funcion

function buscarPostEnFormulario($clave,$valor,$datosasociados){
if ($clave === "checkbox_ubicd_planta_id"){
    return true;
}

$items = explode("|",$datosasociados);

foreach($items as $item){
    $claveValor=explode(",",$item);
    if ((substr($claveValor[0],0,5) <> 'ubic_') && (strpos($claveValor[0],'_09_') === false)){
//            echo "ClaveA: ".$clave." Valor: ".$valor." ClaveValor: ".$claveValor[0]."<br>";
        if ($clave === $claveValor[0]){
            if ($claveValor[1] <> 'v'){
                if ($valor === $claveValor[2]){
//                        echo "<br>Clave: ".$clave." Valor: ".$valor." ClaveValor: ".$claveValor[2]."<br>";
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

function buscarValorEnFormulario($clave,$valorDesde,$valorHasta,$datosasociados){
$items = explode("|",$datosasociados);

foreach($items as $item){
    $claveValor=explode(",",$item);
    if ((substr($claveValor[0],0,5) <> 'ubic_') && (strpos($claveValor[0],'_09_') === false)){
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

function  armarDatosAnuncio($datos, $POST){
    $items = explode("|",$datos);
    $datosMostrar='';
    $coma='';
    $sector='';
    $pos_ubic_ant=0;
    $pos_01_ant=0;
    $pos_02_ant=0;
    $pos_02c_ant=0;
    $pos_03_ant=0;
    $pos_04_ant=0;
    $pos_05_ant=0;
    $pos_06_ant=0;
    $pos_07_ant=0;
    $pos_06A_ant=0;
    $pos_08_ant=0;
    $pos_09_ant=0;
    $pos_10_ant=0;
    $pos_11_ant=0;
    $pos_09=0;
    
    $secLab=0;
    
    $no_mostrar_precio = Funciones::buscarItemEnFormulario("checkbox_02A_precio_oculto",$datos);

    foreach($items as $item){
        $claveValor=explode(",",$item);

        $pos_01 = strpos($claveValor[0], "_01_");
        if ($pos_01 <> 0){
            if ($pos_01_ant == 0){
                $datosMostrar.="<b>Operación:</b> ";
                $pos_01_ant = $pos_01;
            }
        }

        $pos_ubic = strpos($claveValor[0], "bic_");
        if ($pos_ubic <> 0){
            if ($pos_ubic_ant == 0){
                $datosMostrar.="<br><b>Ubicación:</b> ";
                $pos_ubic_ant = $pos_ubic;
                $coma="";
            }
        }

        $pos_03 = strpos($claveValor[0], "_03_");
        if ($pos_03 <> 0){
            if ($pos_03_ant == 0){
                $datosMostrar.="<br><b>Estar:</b> ";
                $pos_03_ant = $pos_03;
                $coma="";
            }
        }

        $pos_04 = strpos($claveValor[0], "_04_");
        if ($pos_04 <> 0){
            if ($pos_04_ant == 0){
                $datosMostrar.="<br><b>Baño y Cocina:</b> ";
                $pos_04_ant = $pos_04;
                $coma="";
            }
        }

        $pos_05 = strpos($claveValor[0], "_05_");
        if ($pos_05 <> 0){
            if ($pos_05_ant == 0){
                $datosMostrar.="<br><b>Otras dependencias:</b> ";
                $pos_05_ant = $pos_05;
                $coma="";
            }
        }

        $pos_06 = strpos($claveValor[0], "_06_");
        if ($pos_06 <> 0){
            if ($pos_06_ant == 0){
                $datosMostrar.="<br><b>Detalles:</b> ";
                $pos_06_ant = $pos_06;
                $coma="";
            }
        }

        $pos_06A = strpos($claveValor[0], "_06A_");
        if ($pos_06A <> 0){
            if ($pos_06A_ant == 0){
                $datosMostrar.="<br><b>Orientación:</b> ";
                $pos_06A_ant = $pos_06A;
                $coma="";
            }
        }

        $pos_07 = strpos($claveValor[0], "_07_");
        if ($pos_07 <> 0){
            if ($pos_07_ant == 0){
                $datosMostrar.="<br><b>Servicios:</b> ";
                $pos_07_ant = $pos_07;
                $coma="";
            }
        }

        $pos_08 = strpos($claveValor[0], "_08_");
        if ($pos_08 <> 0){
            if ($pos_08_ant == 0){
                $datosMostrar.="<br><b>Estado del inmueble:</b> ";
                $pos_08_ant = $pos_08;
                $coma="";
            }
        }

        $pos_09 = strpos($claveValor[0], "_09_");
        if ($pos_09 <> 0){
            if ($pos_09_ant == 0){
                $datosMostrar.="<br><b>Servicios regionales:</b> ";
                $pos_09_ant = $pos_09;
                $coma="";
            }
        }

        $pos_10 = strpos($claveValor[0], "_10_");
        if ($pos_10 <> 0){
            if ($pos_10_ant == 0){
                $datosMostrar.="<br><b>Situación legal:</b> ";
                $pos_10_ant = $pos_10;
                $coma="";
            }
        }

        $pos_11 = strpos($claveValor[0], "_11_");
        if ($pos_11 <> 0){
            if ($pos_11_ant == 0){
                $datosMostrar.="<br><b>Equipamiento rural:</b> ";
                $pos_11_ant = $pos_11;
                $coma="";
            }
        }

        if (count($claveValor) > 1){
            if ($claveValor[1] === 'v'){
                if ($claveValor[0] <> 'valor_checkbox_09_32'){
                    $claveValor[2] = number_format($claveValor[2],0,',','.');
                }
            }
            if ($claveValor[0] === 'ubicd_calle_y_numero_id'){
                $datosMostrar.=$coma."<br>Calle: ".$claveValor[2];
                $coma=' - ';
            } else {
                if ($claveValor[0] === 'ubicd_planta_id') {
                    $datosMostrar.=$coma.", Planta: ".$claveValor[2];
                    $coma=' - ';
                } else {
                    if ($claveValor[0] === 'ubicd_numero_id') {
                    } else {
                    if ($claveValor[0] === 'valor_checkbox_02A_precio' || $claveValor[0] === 'valor_checkbox_02A_1'){
                        if($no_mostrar_precio === 0){
                            $datosMostrar.=": ".$claveValor[2];
                            $coma=' - ';
                        } else {
                            $datosMostrar.=$coma."Consultar";
                        }
                        } else {
                            if ($no_mostrar_precio === 1 && $claveValor[2] === "Publicar con precio oculto"){
                            } else {
                                if ($claveValor[0] === "ubic_pais_id" || $claveValor[0] === "ubic_region_id" || $claveValor[0] ===   "ubic_provincia_id" || $claveValor[0] === "ubic_comarca_id" || $claveValor[0] === "ubic_municipio_id") {
                                } else {
                                    if ($claveValor[1] === 'v'){
                                        $coma=": ";
                                    }
                                    if ($POST <> "" && $claveValor[1] <> 'v' && Funciones::buscarItemEnPOST($claveValor[0], $POST)){
                                        $secLab=$secLab+1;
                                        $label="lab_".$secLab;
                                        $texto=$claveValor[2];
                                        $datosMostrar.=$coma.Funciones::armarTextoColor($label, $texto);
                                        $coma=' - ';
                                    } else {
                                    $datosMostrar.=$coma.$claveValor[2];
                                    $coma=' - ';
                                    }
                                }
                            }
                        }
                }}}
        }
    }// Fin foreach
    $datosMostrar.= '</div>';
    return $datosMostrar;
}

?>