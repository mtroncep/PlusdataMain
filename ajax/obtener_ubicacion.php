<!DOCTYPE html>
<?php
session_start();

$readonly="";
if ($_SESSION['operacion'] == "A"){
	$anuncio=$_SESSION['anuncio'];
} else {
	if ($_SESSION['operacion'] == "C" || $_SESSION['operacion'] == "M"){
		$readonly=' disabled="true" ';
	}
}
include "../mysql.php";
require_once("../cargarDatosFormulario.php");

$query=$mysqli->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ $paises[]=$r; }

$operacion='';
$operacion=$_SESSION['operacion'];
if ($_SESSION['operacion'] == "M" || $_SESSION['operacion'] == "C"){
	$operacion=$_SESSION['operacion'];
	if (!isset($_SESSION['datos'])){
		$_SESSION['datos'] = $datos = Funciones::cargarDatosAnuncio($anuncio, $con);
	}
}
//error_log("!!!!!!!!!!!!! ObtenerUbicacion DATOS ANUNCIO:".var_dump( $_SESSION['datos'] ));
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	
	<script type="text/javascript">
        var $elija = "<option value=''></option>";

		$(document).ready(function(){
			$(document).on('click', 'select[name=ubic_pais_id]', function () {
				$("#ubic_pais_id option:selected").each(function () {
                    $("#enviarDatos").attr("disabled", false);
					$("#ubic_region_id").html($elija);
					$("#ubic_provincia_id").html($elija);
					$("#ubic_comarca_id").html($elija);
					$("#ubic_municipio_id").html($elija);
					$("#ubic_localidad_id").html($elija);
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_pais = $(this).val();
					$.post("select_regiones.php", { id_pais: id_pais,  }, function(data) {
						$("#ubic_region_id").html(data);
					});			
				});
			});
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_region_id]', function () {
				$("#ubic_region_id option:selected").each(function () {
					$("#ubic_provincia_id").html($elija);
					$("#ubic_comarca_id").html($elija);
					$("#ubic_municipio_id").html($elija);
					$("#ubic_localidad_id").html($elija);
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_region = $(this).val();
//					alert(id_region);
					$.post("select_provincias.php", { id_region: id_region }, function(data) {
						$("#ubic_provincia_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_provincia_id]', function () {
				$("#ubic_provincia_id option:selected").each(function () {
					$("#ubic_comarca_id").html($elija);
					$("#ubic_municipio_id").html($elija);
					$("#ubic_localidad_id").html($elija);
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_provincia = $(this).val();
					$.post("select_comarcas.php", { id_provincia: id_provincia }, function(data) {
						$("#ubic_comarca_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_comarca_id]', function () {
				$("#ubic_comarca_id option:selected").each(function () {
					$("#ubic_municipio_id").html($elija);
					$("#ubic_localidad_id").html($elija);
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_comarca = $(this).val();
					$.post("select_municipios.php", { id_comarca: id_comarca }, function(data) {
						$("#ubic_municipio_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_municipio_id]', function () {
				$("#ubic_municipio_id option:selected").each(function () {
					$("#ubic_localidad_id").html($elija);
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_municipio = $(this).val();
					$.post("select_localidades.php", { id_municipio: id_municipio }, function(data) {
						$("#ubic_localidad_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_localidad_id]', function () {
				$("#ubic_localidad_id option:selected").each(function () {
					$("#ubic_barrio_id").html($elija);
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_localidad = $(this).val();
					$.post("select_barrios.php", { id_localidad: id_localidad }, function(data) {
						$("#ubic_barrio_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_barrio_id]', function () {
				$("#ubic_barrio_id option:selected").each(function () {
					$("#ubic_nucleo_id").html($elija);
					$("#ubic_manzana_id").html($elija);
					var id_barrio = $(this).val();
//                    alert (id_barrio);
					$.post("select_nucleos.php", { id_barrio: id_barrio }, function(data) {
						$("#ubic_nucleo_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$(document).on('change', 'select[name=ubic_nucleo_id]', function () {
				$("#ubic_nucleo_id option:selected").each(function () {
					$("#ubic_manzana_id").html($elija);
					var id_nucleo = $(this).val();
					$.post("select_manzanas.php", { id_nucleo: id_nucleo }, function(data) {
						$("#ubic_manzana_id").html(data);
					});
				});
		   });
		});

    </script>
</head>
<body>
<div class="container-sm mt-5">
    <h6>UBICACIÓN GEOGRÁFICA Y MEDIDAS</h6>
	<?php
    $mensajeAyuda = "Ingrese la planta según los ej:
                      PB : 00, 
                      1º : 01, 
                      2º : 02,
                      9º : 09,
                     11º : 11,
                    ";
    
    if ($_SESSION['operacion'] == "A") {
		echo ('<div class="form-group">
			<div class="form-check col-sm-11" id="bloqueUbicacion">
			</div>
		</div>
		<div class="col form-check-inline" id="IngresoLocalidad">
			<div class="listaCP">
				<label for="locBuscar" class="col-sm-6 col-form-label col-form-label-md">Nombre (o parte) de la localidad en la que desea publicar;<br>
                O Ingrese un Código Postal: </label>	
				<input type="text" class="form-text-input col col-md-4" id="locBuscar" name="locBuscar"></input>
			</div>
		</div>
		<div id="listaUbicacionCP" style="display:block">
			<p class="listaUbicCP"></p>
		</div>
		<div id=buscarPorUbicacion style="display:none">
		<p class="recargaUbicacionCP"></p>
		</div>
        <div class="col border form-check-inline" id="selxubicacion"> </div>');
    } else {
//	if ($_SESSION['operacion'] == "A" || $_SESSION['operacion'] == "B") {
//	if ($_SESSION['operacion'] == "A") {
		echo ('<div class="form-group">
			<div class="form-check col-sm-11" id="bloqueUbicacion">
			</div>
		</div>
		<div class="col form-check-inline" id="IngresoLocalidad">
			<div class="listaCP">
				<label for="locBuscar" class="col-sm-6 col-form-label col-form-label-md">Nombre (o parte) de la localidad en la que desea publicar;<br>
                O Ingrese un Código Postal: </label>	
				<input type="text" class="form-text-input col col-md-4" id="locBuscar" name="locBuscar"></input>
			</div>
		</div>
		<div id="listaUbicacionCP" style="display:block">
			<p class="listaUbicCP"></p>
		</div>
		<div id=buscarPorUbicacion style="display:none">
		<p class="recargaUbicacionCP"></p>
		</div>
        <div class="col border form-check-inline" id="selxubicacion">
        <h6>O SELECCIONE UBICACIÓN DE BÚSQUEDA MANUALMENTE</h6>');
/*		} else { ?>
			<div class="col border form-check-inline" id="IngresoLocalidad" style="display: none;">
				<div class="listaCP">
					<input type="text" class="form-text-input col col-md-4" id="locBuscar" name="locBuscar"></input>
				</div>
			</div>
            *///} ?>
			<?php 
			if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">País</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_pais_id" name="ubic_pais_id"
                <?php echo $readonly;?>>
                    <?php 
                    foreach($paises as $c):?>
                        <?php if ($c->COD_NUM <> $_SESSION['codPais']) { ?>
                        <?php } else{ ?>
                          <option selected class="fs-6" value="<?php echo $c->COD_NUM; ?>"><?php echo $c->DESCRIPCION;?></option>
                        <?php } ?>
                    <?php
                    endforeach;?>
                </select>
                </div>
				<?php
				} else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">País</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_pais_id" name="ubic_pais_id" <?php echo($readonly)?>>
                    <option value="">Elija</option>
                    <?php foreach($paises as $c):?>
                    <?php 	$buscarValor="ubic_pais_id";
                            $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                            $paisRegion=$dt['codigoubicacion'];
                            if ($dt['valor'] == $c->DESCRIPCION){
                    ?>
                          <option class="fs-6" value="<?php echo $c->COD_NUM; ?>" selected><?php echo $c->DESCRIPCION;?></option>
                    <?php } else { ?>
                          <option class="fs-6" value="<?php echo $c->COD_NUM; ?>"><?php echo $c->DESCRIPCION;?></option>
                    <?php } endforeach; ?>
                </select>
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Región</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id">
                        <option value=""></option>
                    </select>            
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Región</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id" 
                        <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_region_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                 $regionProvincia=$dt['codigoubicacion'];
                                 Funciones::cargarUbicacionesSelect("regiones","COD_PAIS","COD_REGION",$paisRegion,$dt['valor'],$con);
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Provincia</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_provincia_id" name="ubic_provincia_id">
                        <option value=""></option>
                    </select>  
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Provincia</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_provincia_id" name="ubic_provincia_id" <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_provincia_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                 $ProvinciaComarca=$dt['codigoubicacion'];
                                 echo($dt['valor']);
                                 Funciones::cargarUbicacionesSelect("provincias","COD_REGION","COD_PROVINCIA",$regionProvincia,$dt['valor'],$mysqli);
                        ?>
                    </select>            
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Comarca</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_comarca_id" name="ubic_comarca_id">
                        <option selected></option>
                    </select>            
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Comarca</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_comarca_id" name="ubic_comarca_id" <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_comarca_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                 $ComarcaMunicipio=$dt['codigoubicacion'];
                                 Funciones::cargarUbicacionesSelect("comarcas","COD_PROVINCIA","COD_COMARCA",$ProvinciaComarca,$dt['valor'],$mysqli);
                        ?>
                    </select>            
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Municipio</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_municipio_id" name="ubic_municipio_id">
                        <option selected></option>
                    </select>            
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Municipio</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_municipio_id" name="ubic_municipio_id" <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_municipio_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                 $MunicipioLocalidad=$dt['codigoubicacion'];
                                 Funciones::cargarUbicacionesSelect("municipiosxcomarca","COD_COMARCA","COD_MUNICIPIO",$ComarcaMunicipio,$dt['valor'],$mysqli);
                        ?>
                    </select>            
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Localidad</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_localidad_id" name="ubic_localidad_id">
                        <option selected></option>
                    </select>            
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Localidad</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_localidad_id" name="ubic_localidad_id" <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_localidad_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                $LocalidadBarrio=$dt['codigoubicacion'];
                                Funciones::cargarUbicacionesSelect("localidades","COD_MUNICIPIO","COD_LOCALIDAD",$MunicipioLocalidad,$dt['valor'],$mysqli);
                        ?>
                    </select>            
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Barrio</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id">
                        <option selected></option>
                    </select>            
                </div>
                <?php } else { ?>
                <div class="col border form-check-inline">
                    <p class="mb-0">Barrio</p>
                    <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id" <?php echo($readonly)?>>
                        <?php 	$buscarValor="ubic_barrio_id";
                                $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                 $BarrioNucleo=$dt['codigoubicacion'];
                                 Funciones::cargarUbicacionesSelect("barrios","COD_LOCALIDAD","COD_BARRIO",$LocalidadBarrio,$dt['valor'],$mysqli);
                        ?>
                    </select>            
                </div>
                <?php } ?>

                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                    <div class="col border form-check-inline">
                        <p class="mb-0">Lugar</p>
                        <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" >
                            <option selected></option>
                        </select>            
                    </div>
                <?php } else { ?>
                    <div class="col border form-check-inline">
                        <p class="mb-0">Lugar</p>
                        <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" 
                        <?php echo($readonly);?>>
                        <?php
                            $buscarValor="ubic_nucleo_id";
                            $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                            $NucleoManzana=$dt['codigoubicacion'];
                            Funciones::cargarUbicacionesSelect("nucleos","COD_BARRIO","COD_NUCLEO",$BarrioNucleo,$dt['valor'],$mysqli);
                        ?>
                        </select>            
                    </div>
                <?php } } // colocar debajo el } ?>

                <?php if ($operacion <> "B") { ?>
                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
    <!--
                    <div class="col border form-check-inline col-10">
                        <p class="mb-0">Calle</p>
                        <input class="form-control" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" required>
                        <div class="invalid-feedback">
                            Falta nombre calle.
                        </div>

                        <p class="mb-0">Número</p>
                        <input class="form-control" type="text" id="ubicd_numero_id" name="ubicd_numero_id" required>
                        <div class="invalid-feedback">
                            Falta el numero de puerta.
                        </div>

                        <p class="mb-0">Planta</p>
                        <input class="form-control" type="text" id="ubicd_planta_id" name="ubicd_planta_id">
                        <div class="invalid-feedback">
                            Falta la planta.
                        </div>
                    </div>
    -->
                    <?php } else { ?>

                    <div class="row g-2 align-items-center">

                        <!-- CALLE -->
                        <div class="col-12 col-md-5 d-flex align-items-center">
                            <div class="flex-grow-1 bloque-campo">
                                <label for="ubicd_calle_y_numero_id" class="control-label">Calle</label>

                                <?php
                                    $buscarValor="ubicd_calle_y_numero_id";
                                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                    $calle=$dt['valor'];
                                ?>

                                <input class="form-control"
                                       type="text"
                                       id="ubicd_calle_y_numero_id"
                                       name="ubicd_calle_y_numero_id"
                                       value="<?php echo($calle); ?>"
                                       required>

                                <div class="invalid-feedback">Falta nombre calle.</div>
                            </div>
                        </div>

                        <!-- NÚMERO -->
                        <div class="col-6 col-md-3 d-flex align-items-center">
                            <div class="flex-grow-1 bloque-campo">
                                <label for="ubicd_numero_id" class="control-label">Número</label>

                                <?php
                                    $buscarValor="ubicd_numero_id";
                                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                    $numero=$dt['valor'];
                                ?>

                                <input class="form-control"
                                       type="text"
                                       id="ubicd_numero_id"
                                       name="ubicd_numero_id"
                                       value="<?php echo($numero); ?>"
                                       required>

                                <div class="invalid-feedback">Falta el número de puerta.</div>
                            </div>
                        </div>

                        <!-- PLANTA (solo si corresponde) -->
                        <?php if ($operacion == "M" || $operacion == "C") {
                            if (Funciones::verTipoPropiedad($con,$_REQUEST['tipopropiedad'])) { ?>

                            <div class="col-6 col-md-2 d-flex align-items-center">
                                <div class="flex-grow-1 bloque-campo">
                                    <label for="ubicd_planta_id" class="control-label">Planta</label>

                                    <?php
                                        $buscarValor="ubicd_planta_id";
                                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                        $planta=$dt['valor'];
                                    ?>

                                    <input class="form-control"
                                           type="text"
                                           id="ubicd_planta_id"
                                           name="ubicd_planta_id"
                                           value="<?php echo($planta); ?>"
                                           required>
                                </div>

                                <span class="informacion ms-2"
                                      title="<?php echo $mensajeAyuda;?>"
                                      data-bs-toggle="tooltip"
                                      data-bs-custom-class="mi-tooltip">
                                    <img src="imagenes/exclamacion.png" width="15" height="15">
                                </span>
                            </div>

                        <?php }} ?>

                    </div>
    
    
    <!--
                        <div class="row">
                        <div class="col-2">
                            <label for="ubicd_calle_y_numero_id" class="control-label">Calle</label>
                            <?php 	$buscarValor="ubicd_calle_y_numero_id";
                                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                    $calle=$dt['valor'];
                            ?>
                            <input class="form-control col-md-3" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" value="<?php echo($calle); ?>" required>
                            <div class="invalid-feedback">
                                Falta nombre calle.
                            </div>
                        </div>
                        <div class="col-1">
                            <label for="ubicd_numero_id" class="control-label">Número</label>
                            <?php 	$buscarValor="ubicd_numero_id";
                                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                    $numero=$dt['valor'];
                            ?>
                            <input class="form-control col-md-3" type="text" id="ubicd_numero_id" name="ubicd_numero_id" value="<?php echo($numero); ?>" required>
                            <div class="invalid-feedback">
                                Falta el numero de puerta.
                            </div>

                        </div>
                    <?php } 

                    if ($operacion <> "M" && $operacion <> "C") { ?>
                        <!--
                        <div class="col-1">
                            <label for="ubicd_planta_id" class="control-label">Planta</label>
                            <input class="form-control form-check-label" type="text" id="ubicd_planta_id" name="ubicd_planta_id">

                        </div>
                        -->
                    <?php } else {
                        if( Funciones::verTipoPropiedad($con,$_REQUEST['tipopropiedad'])){ ?>
                                <div class="col-1">
                                    <label for="ubicd_planta_id" class="control-label">Planta</label>
                                    <?php 	$buscarValor="ubicd_planta_id";
                                            $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                            $planta=$dt['valor'];
                                    ?>
                                    <input class="form-control form-check-label" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value="<?php echo($planta); ?>" required>
                                </div>
                                <div class="col-1">
                                    <spam class="informacion" title="<?php echo $mensajeAyuda;?>" data-bs-toggle="tooltip" data-bs-custom-class="mi-tooltip">
                                        <img src="imagenes/exclamacion.png" width="15px" height="15px"</img>
                                    </spam>
                                    <div class="invalid-feedback">
                                        Falta la planta.
                                    </div>
                                </div>
                        </div>
-->
                    <?php } }
                } else { ?>
                    <?php if(Funciones::verTipoPropiedad($con,$_REQUEST['tipopropiedad'])){?>
                        <div class="row form-check-inline">
                            <div class="form-check-inline">
                                <p class="mb-0">Calle</p>
                                <input type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" disabled></input>
                            </div>

                            <div class="form-check-inline">
                                <p class="mb-0">Número</p>
                                <input type="text" id="ubicd_numero_id" name="ubicd_numero_id" disabled></input>
                            </div>

                            <div class="form-check-inline">
                                <input type="checkbox" id="checkbox_ubicd_planta_id" name="checkbox_ubicd_planta_id" value="Planta igual o superior a:" onclick="manejo_estado_checkbok(this)">
                                    <label class="form-check-label" for="checkbox_ubicd_planta_id">
                                        <small>Planta igual o superior a:</small>
                                    </label>
                            </div>

                            <div class="col border form-check-inline">
                                <input class="form-text-input" type="text" value="" name="valor_checkbox_ubicd_planta_id"
                                                id="valor_checkbox_ubicd_planta_id" readonly="readonly"
                                                placeholder="Desde..." onkeypress="mascara(this,cpf)" required>
                                <input class="form-text-input" type="text" value="" name="valor_checkbox_ubicd_planta_idhst"
                                                    id="valor_checkbox_ubicd_planta_idhst" readonly="readonly" placeholder="Hasta..." onkeypress="mascara(this,cpf)" hidden>
                            </div>
                        </div>
                <?php }} ?>
</div>
</body>
</html>
