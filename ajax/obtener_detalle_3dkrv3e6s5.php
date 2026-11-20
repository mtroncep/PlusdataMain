<?php
session_start();

require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");

if (!$_SESSION['busquedaPublica']){
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

if ($_SESSION['operacion'] == 'B'){
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
				" = 1 and secuencia <> '' order by secuencia";
		} else {
		if (isset($_REQUEST['propiedad'])){
			$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and ". 
			VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' order by secuencia";	
		}else{
			if (!empty($VentaAlquiler)){
				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".
					VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' order by secuencia";
			} else {
				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and secuencia <> '' order by secuencia";
			}
		}}
	} else {
		if ($_REQUEST['nro_en_form'] == '02'){
			$sWhere=" detalles.nro_en_form in ('02', '02B') and ".VerSiVentaOAlquiler($VentaAlquiler, $con).
				" = 1 and secuencia <> '' order by secuencia";
//			$sWhere=" detalles.nro_en_form in ('02', '02B') and secuencia <> '' order by secuencia";
		} else {
			if (isset($_REQUEST['propiedad'])){
				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and ". 
				VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' order by secuencia";
//				echo($sWhere);
//				$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".$_REQUEST['propiedad']." = 1 and secuencia <> '' order by secuencia";
			}else{
				if (!empty($VentaAlquiler)){
					$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and ".
						VerSiVentaOAlquiler($VentaAlquiler, $con)." = 1 and secuencia <> '' order by secuencia";
				} else {
					$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."'"." and secuencia <> '' order by secuencia";
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
	$acumvalor=0;
	if (isset($_REQUEST['agrpradio'])){
		if ($_REQUEST['agrpradio'] == "N"){
			$incre=1;
		}else{
			$incre=0;
		}
	}

	$nro_en_form = $_REQUEST['nro_en_form'];

//    if ($nro_en_form === "02A"){
//        echo $sWhere.'<br>';
//    }

    $query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
	$numrows=mysqli_num_rows($query);
//    if ($nro_en_form === "02A"){
//        echo "Cant: ".$numrows.'<br>';
//    }
	if ($numrows>0){
		if ($_SESSION['operacion'] <> "S") { ?>
			<h6><?php echo($_REQUEST['titulo'])?></h6>
			<div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>"> <!-- cambie -->
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
				if ($_SESSION['operacion'] == 'M' || $_SESSION['operacion'] == 'C'){
					$valorCpo='';
                    $estado="readonly";
                    $checkEstado="";

					$dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$detalle,"V");
//                    echo "Detalle: ".$detalle." - ".$dt['tipocampo']."<br>";
					if ($dt['tipocampo'] == 'r'  ){
						$check="checked";
					}else{
						if ($dt['tipocampo'] == 'c'){
//                            echo "TipoCampo=c: ".$dt['campo']."<br>";
							$check="checked";
							$valorCpo='';
							$buscarValor="valor_".$dt['campo'];
							$dt1 = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,"C");
							$valorCpo = $dt1['valor'];
						} else {
                            if ($dt['tipocampo'] == 't'){
//                                echo "Contenido=t: ".$dt['campo']."<br>";
                                $check="checked";
                                $valorCpo='';
                                $buscarValor="tvalo_".$dt['campo'];
                                $dt1 = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,"C");
                                $valorCpo = $dt1['valor'];
//                                echo "Datos=t: ".$valorCpo."<br>";
                            }
                        }
					}
				} else {
                    $estado="readonly";
                    $checkEstado="";
                    if ($detalle == 'Precio' && $_SESSION['operacion'] !== 'B'){
                        $check="checked";
                        $estado="";
                    }
                }
                if ($detalle <> 'Viviendas'){
			?>
			<div class="form-check-inline">
                <?php if ($tipoCampo <> "T"){ ?>
                    <input type="<?php echo $selector?>" class="form-check-input hint" value="<?php echo $detalle?>" id="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>"
                           name="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" readonly="<?php echo $checkEstado?>" <?php
                                                                            if ($selector == 'checkbox') {
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
                                                                            echo(" ".$check." ".$monedaOK);?>>
                    <?php if ($monedaOK == ""){?>
                        <label class="form-check-label" for="<?php echo $detalle?>"><small><?php echo $detalle?></small></label>
                    <?php } ?>
                    <?php
                    $campoType = 'text';
                    if ($tipoCampo == "N"){?> <?php
                        $placeDesde='';
                        //$campoType = 'numeric';
                     if ($_SESSION['operacion'] == 'B'){
                        $placeDesde='Desde...';
                     }
                         ?>
                     <input class="valor_<?php echo($selector. '_' . $nro_en_form) . '_' . $acum?>" type="<?php echo $campoType?>" value="<?php echo($valorCpo)?>"
                            name="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>"
                            id="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>" <?php echo $estado?>
                            placeholder="<?php echo $placeDesde ?>" <?php echo('onkeypress="mascara(this,cpf)"') ?> required>
                     <?php
                        if ($_SESSION['operacion'] == 'B'){?>
                         <input class="form-text-input" type="<?php echo $campoType?>" value="<?php echo($valorCpo)?>"
                                name="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst"?>"
                                id="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum . "hst"?>" readonly="readonly" placeholder="Hasta..." <?php echo('onkeypress="mascara(this,cpf)"') ?> hidden>
                     <?php } ?>
                     <div class="invalid-feedback">Ingrese</div>
                     <?php
                        if ($nro_en_form == '09' && $acum == '32'){
                            echo('<input type="button" value="Envio CP" name="envioCP" id="envioCP" hidden>');
                        }
                        ?>
                    <?php
                    }?>
		    <?php	
			} else { $selector='textarea';?>
                <input type="checkbox" class="form-check-input hint" value="<?php echo $detalle?>" id="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" 
                name="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" readonly="<?php echo $estado?>" <?php
                                                                            if ($selector == 'checkbox') {
                                                                                if (empty($estado)){
                                                                                    echo('onclick="manejo_estado_checkbok(this)"');
                                                                                } else {
                                                                                    echo('onclick="javascript: return false;"');
                                                                                }
                                                                            }		
                                                                            echo(" ".$check." ".$monedaOK);?>>
                <label class="form-check-label" for="<?php echo $detalle?>"><small><?php echo $detalle?></small></label>
                <div class="form-floating">
                    <?php 
                    $ro='';
                    if ($_SESSION['operacion'] <> 'M'){ 
                        $ro = 'readonly';
                    }
                    $selector="textarea";?>
                  <textarea class="form-control" name="tvalo_<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" id="tvalo_<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>" cols="150" rows="5" ><?php echo($valorCpo)?></textarea>
                </div>                
            <?php }?>
            </div>
            <?php
            }
            }
		} else { 
			?>
			<h6><?php echo($_REQUEST['titulo'])?></h6>
			<div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>"> <!-- cambie -->
			<div class="form-check-inline">
		<?php }
	} else { ?>
        <h6><?php echo " "?></h6>
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