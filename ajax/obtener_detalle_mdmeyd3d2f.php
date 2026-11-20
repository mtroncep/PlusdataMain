<?php
session_start();

require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");

if (!isset($_SESSION['busquedaPublica'])){
	$anunciante=$_SESSION['anunciante'];
}
/*
if ($_SESSION['operacion'] == 'B' && $_REQUEST['nro_en_form'] == '09'){
    return "";
}
*/
$VentaAlquiler = "";
if (isset($_REQUEST['ventaalquiler'])){
	$VentaAlquiler = $_REQUEST['ventaalquiler'];
}

if (isset($_REQUEST['propiedad'])){
    $tipoPropiedad=$_REQUEST['propiedad'];
}

$buscar=0;
if ($_SESSION['operacion'] == 'B'){
    $buscar=1;
	if ($_REQUEST['nro_en_form'] == '13'){
        return;
    }
}

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){

	$tables="detalles";
	$campos="*";
	if ($_SESSION['operacion'] == 'A'){
		if ($_REQUEST['nro_en_form'] == '02'){
			$sWhere=" detalles.nro_en_form in ('02', '02B') and ".VerSiVentaOAlquiler($VentaAlquiler, $con).
				" = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";
		} else {
		if (isset($_REQUEST['propiedad'])){
			$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and ". 
			VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";	
		}else{
			if (!empty($VentaAlquiler)){
				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".
					VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";
			} else {
				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and secuencia <> '' and Ocultar = 0 order by secuencia";
			}
		}}
	} else {
		if ($_REQUEST['nro_en_form'] == '02'){
			$sWhere=" detalles.nro_en_form in ('02', '02B') and ".VerSiVentaOAlquiler($VentaAlquiler, $con).
				" = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";
//			$sWhere=" detalles.nro_en_form in ('02', '02B') and secuencia <> '' order by secuencia";
		} else {
			if (isset($_REQUEST['propiedad'])){
                if ($_SESSION['operacion'] == 'M'){
                    $sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and ". 
                    VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";
                } else {
                    $sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and ". 
                    VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' and (Ocultar = 0 or (Ocultar = 1 and Mostrar = 1)) order by secuencia";
                }
            
//				echo($sWhere);
//				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and secuencia <> '' order by secuencia";
			}else{
				if (!empty($VentaAlquiler)){
					$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".
						VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' and Ocultar = 0 order by secuencia";
				} else {
					$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and secuencia <> '' and Ocultar = 0 order by secuencia";
				}
			}
		}
	}

	$selector = "checkbox";
	if (isset($_REQUEST['tiposelector'])){
		$selector = $_REQUEST['tiposelector'];
	}

	$primeraVez='';
	if (isset($_REQUEST['primeraVez'])){
		if ($_REQUEST['primeraVez'] == "S"){
			$anuncio = $_SESSION['anuncio'];
			if (!isset($_SESSION['datos'])){
				$_SESSION['datos'] = Funciones::cargarDatosAnuncio($anuncio, $anunciante, $con);
//				print_r($_SESSION['datos']);
			}
		}
	}

	$estado = "";
	if (isset($_REQUEST['estado'])){
//		$estado = ' onclick="return false;" ';
		$estado = 'readonly';
	}

	$moneda = "";
	if (isset($_SESSION['moneda'])){
//		$estado = ' onclick="return false;" ';
		$moneda = $_SESSION['moneda'];
	}
	$moneda1 = "";
	if (isset($_SESSION['moneda1'])){
//		$estado = ' onclick="return false;" ';
		$moneda1 = $_SESSION['moneda1'];
	}
    $monedaOK="";

    $incre=0;
	$acum=0;
    $increIdRadio=0;
	$acumvalor=0;
	if (isset($_REQUEST['agrpradio'])){
		if ($_REQUEST['agrpradio'] == "N"){
			$incre=1;
		}else{
			$incre=0;
		}
	}

	$incre=1;

    $nro_en_form = $_REQUEST['nro_en_form'];

//    if ($nro_en_form === "02A"){
//        echo $sWhere.'<br>';
//    }

    $query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
//    echo "Operacion: ".$VentaAlquiler."- SELECT $campos FROM  $tables where $sWhere" ;
//    return;
    
	$numrows=mysqli_num_rows($query);
//    if ($nro_en_form === "02A"){
//        echo "Cant: ".$numrows.'<br>';
//    }
	if ($numrows>0){
		if ($_SESSION['operacion'] <> "S") { ?>
            <?php
            if ($nro_en_form === "09") { ?>
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <div style="display: flex; flex-direction: column;">
                        <h6 data-trad="<?php echo($_REQUEST['titulo'])?>"><?php echo($_REQUEST['titulo'])?></h6>
                    </div>
                    <span class="informacion ms-2"
                          title="<?php echo "Las distancias y disponibilidades corresponden al área postal del anuncio, no al punto exacto del inmueble.";?>"
                          data-bs-toggle="tooltip"
                          data-bs-custom-class="mi-tooltip">
                        <img src="imagenes/exclamacion.png" width="15" height="15">
                    </span>
                </div>
            <?php } else { ?>
    			<h6 data-trad="<?php echo($_REQUEST['titulo'])?>"><?php echo($_REQUEST['titulo'])?></h6>
            <?php
            } ?>
			<div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>">
            <!-- cambie -->
			<?php 
			$finales=0;
			while($row = mysqli_fetch_array($query)){	
//                if ($nro_en_form === "02A"){
//                    echo $row['detalle'].'<br>';
//                    echo $row['tipo_campo'].'<br>';
//                    echo '<br>';
//                }
                $tipoCampoMoneda = 0;
				$detalle=$row['detalle'];
				$tipoCampo=$row['tipo_campo'];
				$idDetalle=$row['id'];
                $campofijo=$row['campofijo'];
                $cantMaxDig=$row['cantMaxDig'];
                $longInput=$row['longInput'];
                $decimales=$row['decimales'];
                if($row['mensajeayuda']){
                    $mensajeAyuda=Funciones::obtenerMensajeAyuda($detalle, $con);
                } else {
                    $mensajeAyuda="";
                }
                $finales++;

                switch ($tipoCampo){
                    case "N":
                        $monedaOK="";
                        if (strlen($detalle)>30){
                            $ancho=5; // estaba en 5
                            $md=2;
                        }else{if(strlen($detalle)>20){
                                $ancho=3;
                                $md=3;
                                }else{if(strlen($detalle)>8){
                                        $ancho=3;
                                        $md=3;
                                }else{
                                    $ancho=2;
                                    $md=4;
                                    }
                                }
                             }
                        $acumvalor=$acumvalor + $incre;
                        $maxDigitos = 5;
                       break;

                    case "D":
                        $monedaOK="";
                        if (strlen($detalle)>30){
                            $ancho=5; // estaba en 5
                            $md=2;
                        }else{if(strlen($detalle)>20){
                                $ancho=3;
                                $md=3;
                                }else{if(strlen($detalle)>8){
                                        $ancho=3;
                                        $md=3;
                                }else{
                                    $ancho=2;
                                    $md=4;
                                    }
                                }
                             }
                        $acumvalor=$acumvalor + $incre;
                        $maxDigitos = 5;
                       break;

                    case "B":
                        $monedaOK="";
                        if (strlen($detalle)>30){
                            $ancho=4;
                        }else{if(strlen($detalle)>19){ //aca
                                $ancho=3;
                                }else{if(strlen($detalle)>6){ // aca
                                        $ancho=2;
                                    }else{
                                        $ancho=1;
                                    }
                                }
                             }
                       break;

                    case "M":
                        $monedaOK="";
                        $monedaOK=verMonedaPais($detalle, $moneda, $moneda1);
                        $tipoCampoMoneda = 1;
                       break; 

                    case "T":
                        $monedaOK="";
                        break;
                }

                $acum=$acum + $incre;

                $check='';
                $valorCpo='';
                if ($_SESSION['operacion'] === 'M' || $_SESSION['operacion'] === 'C'){
                    $valorCpo='';
                    $estado="readonly";
                    $checkEstado="";
                    if ($nro_en_form === "09"){
                        $checkEstado="readonly";
                    }
                    $comentario="";

                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$detalle,$decimales,"V");
//                    echo "Detalle: ".$detalle." - ".$dt['tipocampo']."<br>";
                    if ($dt['tipocampo'] == 'r'  ){
                        $check="checked";
                    }else{
                        if ($dt['tipocampo'] == 'c'){
//                            echo "TipoCampo=c: ".$dt['campo']."<br>";
                            $check="checked";
                            $valorCpo='';
                            $buscarValor="valor_".$dt['campo'];
                            $dt1 = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,$decimales,"C");
                            $valorCpo = $dt1['valor'];
                        } else {
                            if ($dt['tipocampo'] == 't'){
//                                echo "Contenido=t: ".$dt['campo']."<br>";
                                $check="checked";
                                $valorCpo='';
                                $buscarValor="tvalo_".$dt['campo'];
                                $dt1 = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,$decimales,"C");
                                $valorCpo = $dt1['valor'];
//                                echo "Datos=t: ".$valorCpo."<br>";
                            }
                        }
                    }
                } else {
                    $estado="readonly";
                    $checkEstado="";
                    $check="";
                    $comentario="";
                    if (($detalle == 'Precio' || $detalle == 'Superficie' || $detalle == 'Metros2') && $_SESSION['operacion'] !== 'B'){
                        $check="checked";
                        $estado="";
                        $comentario="";
                        if ($detalle === "Superficie"){
                            $comentario = "Hasta 100.000 M2 informar en metros, valores superiores, en hectáreas";
                        }
                    }
                }
                if ($detalle <> 'Viviendas'){
            ?>
            <div class="form-check-inline">
                <?php if ($tipoCampo <> "T"){
                    $noMostrar = 0;
                    if ($campofijo === "checkbox_02A_precio_oculto" && 
                        $_SESSION['operacion'] === "B" ) {
                        $noMostrar = 1;
                        }
                    if ($campofijo === "checkbox_ubicd_planta_id"  && 
                        $_SESSION['operacion'] === "M") {
                        $noMostrar = 1;
                        }
                    if ($campofijo === "checkbox_ubicd_planta_id"  && 
                        $_SESSION['operacion'] === "A") {
                        $noMostrar = 1;
                        }
                    
                    if ($nro_en_form == '09' && $acum == '32' && $_SESSION['operacion'] === "B" ){
//                        $noMostrar = 0;
                        $noMostrar = 1;
                    }
                
                    if (!$noMostrar){
                        if ($comentario !== "" && $tipoPropiedad === "Suelos"){
                            echo "<small>".$comentario."</small>";
                        }
                        if (isset($_SESSION['anuncio'])){
//                            $idMensaje = sprintf('%s_%s_%s', $selector, $nro_en_form, $acum);
//                            $mensajeAnuncio = Funciones::buscarMensajeAnuncio($con, $_SESSION['anuncio'], $idMensaje);
                              $mensajeAnuncio = "";
                        } else {
                            $mensajeAnuncio = "";
                        }
                        ?>
                        <input type="<?php echo $selector;?>" class="custom-checkbox hint <?php echo ($selector.'_'.$nro_en_form);?>" 
                               value="<?php echo $detalle;?>"
                               id="<?php 
                            if ($campofijo === "")
                                {echo($selector.'_'.$nro_en_form).'_'.$acum;
                                } else {echo $campofijo;}?>"
                                <?php if ($selector === "radio") {?>
                                 onclick="cambiarColorFondo(this)"
                               <?php } ?>
                                name="<?php 
                                if(empty($row['agrupdet'])){ 
                                    if($campofijo === ""){ 
                                        if ($selector === "checkbox"){
                                            echo ($selector.'_'.$nro_en_form.'_'.$acum);
                                        } else {
                                            echo ($selector.'_'.$nro_en_form."_0");
                                        }
                                    } else 
                                        {echo $campofijo;};}
                                            else {echo ($row['agrupdet']);}?>" <?php if($checkEstado <> ''){?> 
                                                                                        readonly="<?php echo $checkEstado?>" <?php }             if ($selector === 'checkbox') {
                                                                                          if (empty($checkEstado)){
                                                                                        if ($tipoCampoMoneda == 0){
                                                                                            echo('onclick="manejo_estado_checkbok(this)"');
                                                                                            } else {
                                                                                              echo('onclick="return manejo_checkbox_moneda(this)"');
                                                                                              }
                                                                                            } else {
                                                                                              echo('onclick="javascript: return false;"');
                                                                                              }
                                                                                         }		
                                                                                        echo(" ".$check." ".$monedaOK);?>></input>
                        <?php if ($tipoCampo !== "M"){ ?>
                            <label for="<?php if ($campofijo === "") { echo($selector.'_'.$nro_en_form).'_'.$acum;} else { echo ($campofijo);}?>" class="custom-label" >
                            <?php if ($mensajeAyuda) { ?>
                            <?php } ?> 
                            </label>
                        <?php
                        } else {
                            if ($monedaOK !== "hidden") { ?>
                              <label for="<?php if ($campofijo === "") { echo($selector.'_'.$nro_en_form).'_'.$acum;} else { echo ($campofijo);}?>" class="custom-label"></label>
                        <?php }
                        }
                        ?>

                        <?php if ($monedaOK === ""){
                                 if ($campofijo === "" || $campofijo <> "") { ?>
                                    <label data-trad="<?php echo $detalle ?>" class="<?php echo $selector.'_'.$nro_en_form.'_0'?>"
                                        <?php
                                           if ($campofijo === "") { ?>
                                               for="<?php echo $selector.'_'.$nro_en_form.'_'.$acum?>" 
                                               id="<?php echo "label_".$selector.'_'.$nro_en_form.'_'.$acum?>"
                                           <?php } else { ?>
                                               for="<?php echo 'label_'.$campofijo?>" 
                                               id="<?php echo "label_".$campofijo?>" >
                                           <?php       
                                           }
                                           ?>
                                        <?php if ($mensajeAyuda ) { ?>
                                            <small><?php echo($detalle);?>
                                            </small>
                                           </label>
                                            <?php if ($mensajeAyuda) { ?>
                                            <spam class="informacion" title="<?php echo $mensajeAyuda;?>" data-bs-toggle="tooltip" data-bs-custom-class="mi-tooltip">
                                                <img src="imagenes/exclamacion.png" width="15px" height="15px"</img>
                                            </spam>
                                            <?php } ?>
<!--                                           </label> -->
                                        <?php } else { ?>
                                            <small><?php echo $detalle?></small>
                                            <?php if ($mensajeAyuda) { ?>
                                            <div class="informacion" title="<?php echo $mensajeAyuda;?>" data-bs-toggle="tooltip" data-bs-custom-class="mi-tooltip">
                                                <img src="imagenes/exclamacion.png" width="15px" height="15px"</img>
                                            </div>
                                            <?php } ?>
                                           </label>
                                       <?php } ?>
                                       <?php } else { ?>
                                    <label data-trad="<?php echo $detalle ?>" class="<?php echo $selector.'_'.$nro_en_form.'_0'?>" 
                                    for="<?php echo 'label_'.$campofijo?>" 
                                    id="<?php echo "label_".$campofijo?>">
                                    <?php if ($mensajeAyuda) { ?>
                                        <small><?php echo("<p<strong style='font-weight: bold;'>".$detalle."</strong>");?>
                                        </small>
                                        </label>
                                    <?php } else { ?>
                                        <small><?php echo $detalle ?></small> 
                                    <?php } ?>
                                        </label>
                                <?php } } ?>
                        <?php
                        $campoType = 'text';
                        if ($tipoCampo == "N"){?> <?php
                            $placeDesde='';
                            //$campoType = 'numeric';
                         if ($_SESSION['operacion'] == 'B'){
                            $placeDesde='Desde...';
                         }
                         ?>
                         <input class="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>" type="<?php echo $campoType?>" value="<?php echo($valorCpo)?>"
                                name="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>"
                                id="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>" 
                                <?php echo $estado?>
                                placeholder="<?php echo $placeDesde ?>" <?php echo('onkeyup="mascara(this)"') ?> <?php echo('onchange="mascara(this)"') ?>  maxlength="<?php echo $cantMaxDig?>" size="<?php echo $longInput?>" required>
                         <?php
                            if ($_SESSION['operacion'] == 'B'){?>
                             <input class="form-text-input" type="<?php echo $campoType?>" value="<?php echo($valorCpo)?>"
                                    name="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst";} else {echo $campofijo . "hst";} ?>"
                                    id="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst";} else {echo $campofijo . "hst";} ?>" readonly="readonly" placeholder="Hasta..." <?php echo('onkeyup="mascara(this)"') ?> <?php echo('onchange="mascara(this)"') ?> maxlength="<?php echo $cantMaxDig?>" size="<?php echo $longInput?>"  hidden>
                         <?php } ?>
                         <?php
                            if ($nro_en_form == '09' && $acum == '32'){
                                echo('<input type="button" value="Envio CP" name="envioCP" id="envioCP" hidden>');
                            }
                            ?>
                        <?php
                    } else {
                        $campoType = 'number';
                        if ($tipoCampo == "D"){?> <?php
                            $placeDesde='';
                         if ($_SESSION['operacion'] == 'B'){
                            $placeDesde='Desde...';
                         }
                         error_log("Valor decimal: ".$valorCpo);
                         error_log("Tipo campo:    ".$tipoCampo);
                         ?>
                         <input class="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>" type="text" value="<?php echo (str_replace('.',',', $valorCpo))?>"
                                name="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>"
                                id="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum;} else {echo $campofijo;} ?>" 
                                <?php echo $estado?>
                                placeholder="<?php echo $placeDesde ?>" maxlength="<?php echo $cantMaxDig?>" size="<?php echo $longInput?>" required>
                         <?php
                            if ($_SESSION['operacion'] == 'B'){?>
                             <input class="form-text-input" type="text" inputmode="decimal" value="<?php echo str_replace('.',',', $valorCpo)?>"
                                    name="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst";} else {echo $campofijo . "hst";} ?>"
                                    id="valor_<?php if($campofijo === ""){echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst";} else {echo $campofijo . "hst";} ?>" readonly="readonly" placeholder="Hasta..."   maxlength="<?php echo $cantMaxDig?>" size="<?php echo $longInput?>" hidden>
                         <?php } ?>
                         <?php
                            if ($nro_en_form == '09' && $acum == '32'){
                                echo('<input type="button" value="Envio CP" name="envioCP" id="envioCP" hidden>');
                            }
                        }
                    }?>
            <?php	
                }
                } else { $selector='textarea';?>
                    <input type="checkbox" class="form-check-input hint" value="<?php echo $detalle?>" id="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" 
                    name="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" <?php
                                                                                if ($selector == 'textarea') {
                                                                                    if (!empty($estado)){
                                                                                        echo('onclick="manejo_estado_textarea(this)"');
                                                                                    } else {
                                                                                        echo('onclick="javascript: return false;"');
                                                                                    }
                                                                                }		
                                                                                    echo(" ".$check." ".$monedaOK);?>>
                    <label class="form-check-label" for="<?php echo $detalle?>"><small><?php echo $detalle?></small></label>
                    <div class="form-floating">
                        <?php 
                        $ro="";
                        if ($_SESSION['operacion'] <> "M"){ 
                            $ro = 'readonly';
                        }
                        $selector="textarea";?>
                      <textarea class="form-control" oninput="validarTexto(this)" name="tvalo_<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" id="tvalo_<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" cols="150" rows="5" readonly="<?php echo $ro?>" ><?php echo($valorCpo)?></textarea>
                    </div>                
                <?php }?>
                </div>
                <?php
                }
            }  // While de cada input del formulario
		} // Operacion <> "S" 
          else { 
			?>
			<h6 data-trad="<?php echo($_REQUEST['titulo'])?>"><?php echo($_REQUEST['titulo'])?></h6>
			<div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>"> <!-- cambie -->
			<div class="form-check-inline">
		<?php }
	} else { ?>
        <h6 ><?php echo " "?></h6>
        <div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>"> <!-- cambie -->
    <?php    
    }
}

function VerSiVentaOAlquiler ($VentaAlquiler, $con){
	$query = mysqli_query($con,"SELECT Compartir, CompraVenta, Alquiler FROM detalles where detalle like '$VentaAlquiler'");
	$numrows=mysqli_num_rows($query);
	if ($numrows > 0){
		$row = mysqli_fetch_array($query);
		if ($row['CompraVenta'] == '1'){
			$columna="CompraVenta";
		}else{
			if ($row['Alquiler'] == '1'){
				$columna="Alquiler";
			} else {
				$columna="Compartir";
			}
		}
		return $columna;
	}
}

function verMonedaPais($detalle,$moneda,$moneda1){
    if (($detalle == $moneda) || ($detalle == $moneda1)){
        return "";
    } else {
        return "hidden";
    }
}
?>

<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>