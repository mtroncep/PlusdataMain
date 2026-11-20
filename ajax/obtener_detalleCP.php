<?php
session_start();

require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");
?>
<?php
$action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
    $buscar = false;
    if ($_SESSION['operacion'] == 'B'){
        $buscar = true;
        $_SESSION['tipoBusqueda']=$_REQUEST['tipoBusqueda'];
    }
    $operacion=$_SESSION['operacion'];
	$tables="detalles";
	$campos="*";
	$sWhere=" detalles.nro_en_form = '".$_REQUEST['nro_en_form']."' order by secuencia";

	$selector = "checkbox";
	if (isset($_REQUEST['tiposelector'])){
		$selector = $_REQUEST['tiposelector'];
	}

	$primeraVez='';
	if (isset($_REQUEST['primeraVez'])){
		if ($_REQUEST['primeraVez'] == "S"){
            $cp = $_REQUEST['cp'];
            $loccp = $_REQUEST['loccp'];
            $_SESSION['cp'] = $cp;
            $_SESSION['loccp'] = $loccp;
            $_SESSION['datos'] = Funciones::cargarDatosCP($cp, $loccp, $con);
            $count=0;
            $count=count($_SESSION['datos']);
//            echo("Cantidad CP: ".$count);
//            return;
            if ($count == 2 && !$buscar){
                $_SESSION['operacionCP']='A';
//                echo('OperacionCP: '.$_SESSION['operacionCP']);
            } else {
                $_SESSION['operacionCP']='M';
//                echo('OperacionCP: '.$_SESSION['operacionCP']);
            }
		}
	}

	$estado = "";
    if (isset($_REQUEST['estado'])){
        if ($_REQUEST['estado'] <> "edit"){
            if ($count > 2) {
                    $estado = "readonly";
                }
        }
    } else {
        $estado = "readonly";        
    }
//	$estado = "";

    $habilitado = '';
    if (isset($_REQUEST['habilitado'])){
        if ($_REQUEST['habilitado'] <> "edit"){
            if ($count > 2) {
//                $estado = "readonly";
                $habilitado = $_REQUEST['habilitado'];
            }
        }
    } 
    
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

	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
	$numrows=mysqli_num_rows($query);
	if ($numrows>0){?>
			<h6 data-trad="<?php echo($_REQUEST['titulo'])?>"><?php echo($_REQUEST['titulo'])?></h6>
			<div class="d-flex d-flex-row flex-wrap" id="SECTOR_<?php echo($nro_en_form)?>"> <!-- cambie -->
			<?php 
			$finales=0;
			while($row = mysqli_fetch_array($query)){	
				$detalle=$row['detalle'];
				$tipoCampo=$row['tipo_campo'];
				$idDetalle=$row['id'];
				$finales++;
				switch ($tipoCampo){
					case "N":
						if (strlen($detalle)>30){
							$ancho=6;
							$md=6;
						}else{if(strlen($detalle)>20){
								$ancho=5;
								$md=5;
								}else{if(strlen($detalle)>8){
										$ancho=4;
										$md=4;
								}else{
									$ancho=3;
									$md=3;
									}
								}
							 }
						$acumvalor=$acumvalor + $incre;
					break;

					case "B":
						if (strlen($detalle)>30){
							$ancho=5;
						}else{if(strlen($detalle)>20){
								$ancho=3;
								}else{if(strlen($detalle)>7){
										$ancho=3;
									}else{
										$ancho=2;
									}
								}
							 }
					break;
				}
				$acum=$acum + $incre;
				$check='';
				$valorCpo='';
                $dt = [];
                $dt1 = [];
				if ($_SESSION['operacionCP'] == 'M' || $_SESSION['operacionCP'] == 'A'){
					$valorCpo='';
					$dt = Funciones::buscarDatoDeCP($_SESSION['datos'],$detalle,"V");
					if ($dt['tipocampo'] == 'r'  ){
						$check="checked";
					} else {
						if ($dt['tipocampo'] == 'c'){
							$check="checked";
							$valorCpo='';
							$buscarValor="valor_".$dt['campo'];
							$dt1 = Funciones::buscarDatoDeCP($_SESSION['datos'],$buscarValor,"C");
							$valorCpo = $dt1['valor'];
						}
					}
				}

				if ($tipoCampo == "N"){
					if ($detalle == "Código Postal"){
						$valorCpo = $_REQUEST['cp'];
						$check="checked";
						$_SESSION['cp']=$valorCpo;
					}
				}
				?>
<?php
if ($buscar && $acum == '32'){ ?>
    
    <div class="item-servicio">
        <input type="<?php echo $selector?>"
               class="form-check-input <?php echo($selector.'_'.$nro_en_form)?>"
               value="<?php echo $detalle?>"
               id="<?php echo ($selector.'_'.$nro_en_form).'_'.$acum?>"
               name="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>"
               readonly="<?php echo $estado?>"
               <?php
                    if ($selector == 'checkbox') {
                        if (empty($estado)){
                            echo('onclick="manejo_estado_checkbok(this)"');
                        } else {
                            echo('onclick="javascript: return false;"');
                        }
                    }
                    echo(" ".$check);
               ?>
        >

        <label data-trad="<?php echo $detalle ?>"
               class="form-check-label"
               for="<?php echo $detalle?>">
               <small><?php echo $detalle?></small>
        </label>

        <?php if ($tipoCampo == "N"){ ?>
            <input class="form-text-input"
                   type="text"
                   value="<?php echo($valorCpo)?>"
                   name="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>"
                   id="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>"
                   readonly="readonly"
                   required>
            <div class="invalid-feedback">Ingrese valor indicado</div>
        <?php } ?>
    </div>

<?php } else { 
    if (!$buscar){ ?>

        <div class="item-servicio">
            <input type="<?php echo $selector?>"
                   class="form-check-input <?php echo($selector. '_' . $nro_en_form)?>"
                   value="<?php echo $detalle?>"
                   id="<?php echo ($selector.'_'.$nro_en_form).'_'.$acum?>"
                   name="<?php echo($selector.'_'.$nro_en_form).'_'.$acum?>"
                   <?php
                        if ($selector == 'checkbox') {
                            if (empty($estado)){
                                echo('onclick="manejo_estado_checkbok(this)"');
                            } else {
                                echo('onclick="javascript: return false;"');
                            }
                        }
                        echo(" ".$habilitado." ".$check);
                   ?>
            >

            <label data-trad="<?php echo $detalle ?>"
                   class="form-check-label"
                   for="<?php echo $detalle?>">
                   <small><?php echo $detalle?></small>
            </label>

            <?php if ($tipoCampo == "N"){ ?>
                <input class="form-text-input"
                       type="text"
                       value="<?php echo($valorCpo)?>"
                       name="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>"
                       id="valor_<?php echo($selector. '_' . $nro_en_form) . "_" . $acum?>"
                       required
                       readonly>
                <div class="invalid-feedback">Ingrese valor indicado</div>
            <?php } ?>
        </div>

        <?php } } ?>		    
<!-- </div> -->
        <?php
		} ?>
</div>
<br>
        <?php
	} 
}
?>
