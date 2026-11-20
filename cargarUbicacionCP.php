<!DOCTYPE html>
<?php
session_start();

$readonly="";
if ($_SESSION['operacion'] == "A"){
	$anuncio=$_SESSION['anuncio'];
} else {
	if ($_SESSION['operacion'] == "C"){
		$readonly=' disabled="true" ';
	}
}
include "mysql.php";
require_once("cargarDatosFormulario.php");

$query=$mysqli->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ $paises[]=$r; }

$operacion='';
if ($_SESSION['operacion'] == "M" || $_SESSION['operacion'] == "C" ){
	$operacion=$_SESSION['operacion'];
	if (!isset($_SESSION['datos'])){
		$_SESSION['datos'] = $datos = Funciones::cargarDatosAnuncio($anuncio, $con);
	}
}

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

</head>
<body>
	<?php if ($operacion <> "M" && $operacion <> "C") { ?>
	<div class="col border form-check-inline">
		<p class="mb-0">País</p>
	<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_pais_id" name="ubic_pais_id" <?php echo($readonly)?>>
		<option value="" class="ubicPais">Elija</option>
		<?php foreach($paises as $c):?>
			  <option class="fs-6" value="<?php echo $c->COD_NUM; ?>"><?php echo $c->DESCRIPCION;?></option>
		<?php endforeach; ?>
	</select>
	</div>
	<?php } else { ?>
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
	<?php } else{ ?>
	<div class="col border form-check-inline">
		<p class="mb-0">Región</p>
		<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_region_id" name="ubic_region_id" <?php echo($readonly)?>>
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
	<?php } else{ ?>
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
	<?php } else{ ?>
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
	<?php } else{ ?>
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
	<?php } else{ ?>
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
	<?php } else{ ?>
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
		<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id">
			<option selected></option>
		</select>            
	</div>
	<?php } else{ ?>
	<div class="col border form-check-inline">
		<p class="mb-0">Lugar</p>
		<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_nucleo_id" name="ubic_nucleo_id" <?php echo($readonly)?>>
			<?php 	$buscarValor="ubic_nucleo_id";
					$dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
				 	$NucleoManzana=$dt['codigoubicacion'];
				 	Funciones::cargarUbicacionesSelect("nucleos","COD_BARRIO","COD_NUCLEO",$BarrioNucleo,$dt['valor'],$mysqli);
			?>
		</select>            
	</div>
	<?php } ?>

	<?php if ($operacion <> "M" && $operacion <> "C") { ?>
	<div class="col border form-check-inline">
		<p class="mb-0">Manzana</p>
		<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_manzana_id" name="ubic_manzana_id">
			<option selected></option>
		</select>            
	</div>
	<?php } else{ ?>
	<div class="col border form-check-inline">
		<p class="mb-0">Manzana</p>
		<select class="form-select form-select-sm" aria-label="Default select example" id="ubic_manzana_id" name="ubic_manzana_id" <?php echo($readonly)?>>
			<?php 	$buscarValor="ubic_manzana_id";
					$dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
				 	$NucleoFin=$dt['codigoubicacion'];
				 	Funciones::cargarUbicacionesSelect("manzanas","COD_NUCLEO","COD_MANZANA",$NucleoManzana,$dt['valor'],$mysqli);
			?>
			<option class="fs-6 " value="<?php echo $dt['codigoubicacion'];?>" selected><?php echo $dt['valor'];?></option>
		</select>            
    </div>
	<?php }

        if ($operacion <> "M" && $operacion <> "C") {
        ?>
        <div class="row">
            <div class="col-2">
                <label for="ubicd_calle_y_numero_id" class="control-label">Calle</label>
                <input class="form-control col-md-3" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" value="" required>
                        <div class="invalid-feedback">
                            Falta nombre calle.
                        </div>
            </div>
                        <div class="invalid-feedback">
                            Falta nombre calle.
                        </div>
            <div class="col-1">
                <label for="ubicd_numero_id" class="control-label">Número</label>
                <input class="form-control col-md-3" type="text" id="ubicd_numero_id" name="ubicd_numero_id" value="" required>
            </div>
        </div>
        <?php } else { ?>
        <div class="row">
            <div class="col-2">
                <label for="ubicd_calle_y_numero_id" class="control-label">Calle</label>
                <?php 	$buscarValor="ubicd_calle_y_numero_id";
                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                        $calle=$dt['valor'];
                ?>
                <input class="form-control col-md-3" type="text" id="ubicd_calle_y_numero_id" name="ubicd_calle_y_numero_id" value="<?php echo($calle); ?>" required>
            </div>
            <div class="col-1">
                <label for="ubicd_numero_id" class="control-label">Número</label>
                <?php 	$buscarValor="ubicd_numero_id";
                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                        $numero=$dt['valor'];
                ?>
                <input class="form-control col-md-3" type="text" id="ubicd_numero_id" name="ubicd_numero_id" value="<?php echo($numero); ?>" required>
            </div>
        <?php } 

        if ($operacion <> "M" && $operacion <> "C") { ?>
            <div class="col-1">
                <label for="ubicd_planta_id" class="control-label">Planta</label>
                <?php 	$buscarValor="ubicd_planta_id";
                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                        $planta=$dt['valor'];
                ?>
                <input class="form-control" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value="<?php echo($planta); ?>" required
            </div>
        <?php } else { ?>
            <div class="col-1">
                <label for="ubicd_planta_id" class="control-label">Planta</label>
                <?php 	$buscarValor="ubicd_planta_id";
                        $dt = Funciones::buscarDatoDeAnuncio($_SESSION['datos'],$buscarValor,'0',"C");
                        $planta=$dt['valor'];
                ?>
                <input class="form-control" type="text" id="ubicd_planta_id" name="ubicd_planta_id" value="<?php echo($planta); ?>" required>
            </div>
        </div>
        <?php } ?> 
</body>
</html>