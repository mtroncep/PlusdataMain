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

require_once("../mysql.php");
require_once("../cargarDatosFormulario.php");

$paisMapa=Funciones::obtenerMapaPais($con, $_SESSION['codPais']);
    
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
//var_dump( $_SESSION['datos'] );
//return;
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
<!--	<script src="https://code.jquery.com/jquery-3.2.1.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/fuentes.css">

	<script type="text/javascript">
        var $elija = "<option value=''></option>";

		$(document).ready(function(){
//			$(document).on('click', 'select[name=ubic_pais_id]', function () {
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
					$.post("select_regiones.php", { id_pais: id_pais  }, function(data) {
						$("#ubic_region_id").html(data);
					});			
				});
//			});
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
//					alert("Cambio la provincia: "+id_provincia);
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
//					alert("Cambio la comarca: "+id_comarca);
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
<!--    <h6>UBICACIÓN GEOGRÁFICA Y MEDIDAS</h6> -->
    <h6 data-trad="¿DONDE?">¿DONDE?</h6>
	<?php
    if ($_SESSION['operacion'] == "A") {
        $mascaraCP = Funciones::obtenerFormatoCPPais($con, $_SESSION['codPais']);
		echo ('<div class="form-group">
			<div class="form-check col-sm-11" id="bloqueUbicacion">
			</div>
		</div>
		<div class="col form-check-inline" id="IngresoLocalidad">
			<div class="listaCP">
				<label for="locBuscar" class="col-sm-6 col-form-label col-form-label-md" data-trad="Nombre (o parte) de la ciudad que le interese;">Nombre (o parte) de la ciudad que le interese;<br>
                O un Código Postal: </label>	
				<input type="text" oninput="this.value = this.value.toUpperCase();" class="form-text-input col col-md-5" id="locBuscar" placeholder="'.$mascaraCP[0].'" name="locBuscar" onkeydown="anularEnter(event)" data-mensaje="Luego de colocar el valor deseado, oprima la tecla <TAB> para tomarlo."></input>
                <img src="imagenes/exclamacion.png" width="15px" height="15px" title="'.$mascaraCP[1].'" data-bs-toggle="tooltip" 
                data-bs-custom-class="mi-tooltip">
                </img>
			</div>
		</div>
		<div id="listaUbicacionCP" style="display:block">
			<p class="listaUbicCP"></p>
		</div>
		<div id=buscarPorUbicacion style="display:none">
		<p class="recargaUbicacionCP"></p>
		</div>
        <div class="col form-check-inline" id="selxubicacion"> </div>');
    } else {
//	if ($_SESSION['operacion'] == "A" || $_SESSION['operacion'] == "B") {
//	if ($_SESSION['operacion'] == "A") {
        $mascaraCP = Funciones::obtenerFormatoCPPais($con, $_SESSION['codPais']);
		echo ('<div class="form-group">
			<div class="form-check col-sm-11" id="bloqueUbicacion">
			</div>
		</div>
		<div class="col form-check-inline" id="IngresoLocalidad">
			<div class="listaCP">
				<label for="locBuscar" class="col-sm-6 col-form-label col-form-label-md" data-trad="Nombre (o parte) de la ciudad que le interese;">>Nombre (o parte) de la ciudad que le interese;<br>
                O un Código Postal: </label>
				<input type="text" oninput="this.value = this.value.toUpperCase();" class="form-text-input col col-md-5" id="locBuscar" placeholder="'.$mascaraCP[0].'" name="locBuscar" onkeydown="anularEnter(event)" data-mensaje="Luego de colocar el valor deseado, oprima la tecla <TAB> para tomarlo."></input>
                <img src="imagenes/exclamacion.png" width="15px" height="15px" title="'.$mascaraCP[1].'" data-bs-toggle="tooltip" 
                data-bs-custom-class="mi-tooltip">
                </img>
			</div>
		</div>
		<div id="listaUbicacionCP" style="display:block">
			<p class="listaUbicCP"></p>
		</div>
		<div id=buscarPorUbicacion style="display:none">
		<p class="recargaUbicacionCP"></p>
		</div>
        <div class="col form-check-inline" id="selxubicacion">
        <h6 data-trad="ELIJA DESDE EL MAPA Y/O la BARRA DE DATOS">ELIJA DESDE EL MAPA Y/O la BARRA DE DATOS</h6>');
        if ($_SESSION['operacion'] === "B" && $paisMapa <> "" && Funciones::usarMapas()){ ?>
<!--
            <div class="row">
                <div class="col iframe-container mb-2" align="center" id="ca_DIV" onClick="sacarBusquedaLocalidad()"> 
                    <iframe src="<?php echo $paisMapa?>" id="iframe_MAPA">
                    </iframe>
                </div>
            </div>
-->
                    <h6 data-trad="BARRA DE DATOS">BARRA DE DATOS</h6>

                    <div class="col form-check-inline" id="IngresoLocalidad" style="display: none;">
                        <div class="listaCP">
                            <input type="text" class="form-text-input col col-md-4" id="locBuscarm" name="locBuscarm"></input>
                        </div>
                    </div>
                    <?php 
                    if ($operacion <> "M" && $operacion <> "C") { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="País">País</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="País">País</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Región">Región</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id">
                                <option value=""></option>
                            </select>            
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Región">Región</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Provincia">Provincia</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_provincia_id" name="ubic_provincia_id">
                                <option value=""></option>
                            </select>  
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Provincia">Provincia</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Comarca">Comarca</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_comarca_id" name="ubic_comarca_id">
                                <option selected></option>
                            </select>            
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Comarca">Comarca</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Municipio">Municipio</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_municipio_id" name="ubic_municipio_id">
                                <option selected></option>
                            </select>            
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Municipio">Municipio</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Localidad">Localidad</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_localidad_id" name="ubic_localidad_id">
                                <option selected></option>
                            </select>            
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Localidad">Localidad</p>
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
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Barrio">Barrio</p>
                            <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_barrio_id" name="ubic_barrio_id">
                                <option selected></option>
                            </select>            
                        </div>
                        <?php } else { ?>
                        <div class="col form-check-inline">
                            <p class="mb-0" data-trad="Barrio">Barrio</p>
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
                            <div class="col form-check-inline">
                                <p class="mb-0" data-trad="Lugar">Lugar</p>
                                <select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" >
                                    <option selected></option>
                                </select>            
                            </div>
                        <?php } else { ?>
                            <div class="col form-check-inline">
                                <p class="mb-0" data-trad="Lugar">Lugar</p>
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
                        <?php } ?>
                            <hr>
            <div class="row">
                <div class="col iframe-container mb-2" align="center" id="ca_DIV" onClick="sacarBusquedaLocalidad()"> 
                    <iframe src="<?php echo $paisMapa?>" id="iframe_MAPA">
                    </iframe>
                </div>
            </div>
                        <?php } // colocar debajo el } ?>
<!--                </div> -->
        <?php } ?>

                <?php if ($operacion <> "B") { ?>
                <?php if ($operacion <> "M" && $operacion <> "C") { ?>
                    <?php } else { ?>
                    <div class="row">
                        <div class="col-2">
                            <label for="ubicd_calle_y_numero_id" class="control-label">Calle</label>
                            <?php 	$buscarValor="ubicd_calle_y_numero_id";
                                    $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                    $calle=$dt['valor'];
                            ?>
                            <input class="form-control col-md-3" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" value="<?php echo($calle); ?>" required>
                            <div class="invalid-feedback" data-trad="Falta nombre calle.">
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
                            <div class="invalid-feedback" data-trad="Falta el numero de puerta.">
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
                                <label for="ubicd_planta_id" class="control-label" data-trad="Planta">Planta</label>
                                <?php 	$buscarValor="ubicd_planta_id";
                                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                                        $planta=$dt['valor'];
                                ?>
                                <input class="form-control form-check-label" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value="<?php echo($planta); ?>">
                                <div class="invalid-feedback" data-trad="Falta la planta.">
                                    Falta la planta.
                                </div>
                            </div>
                        </div>
                    <?php } }
                }?>
</div>
</body>
</html>
