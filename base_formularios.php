<!DOCTYPE html>
<?php
include "mysql.php";
$query=$mysqli->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ $paises[]=$r; }
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
		$(document).ready(function(){
			$("#pais_id").on('change', function () {
				$("#pais_id option:selected").each(function () {
					var id_pais = $(this).val();
					$.post("select_regiones.php", { id_pais: id_pais }, function(data) {
						$("#region_id").html(data);
					});			
				});
			});
		});

		$(document).ready(function(){
			$("#region_id").on('change', function () {
				$("#region_id option:selected").each(function () {
					var id_region = $(this).val();
					$.post("select_provincias.php", { id_region: id_region }, function(data) {
						$("#provincia_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#provincia_id").on('change', function () {
				$("#provincia_id option:selected").each(function () {
					var id_provincia = $(this).val();
					$.post("select_comarcas.php", { id_provincia: id_provincia }, function(data) {
						$("#comarca_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#comarca_id").on('change', function () {
				$("#comarca_id option:selected").each(function () {
					var id_comarca = $(this).val();
					$.post("select_municipios.php", { id_comarca: id_comarca }, function(data) {
						$("#municipio_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#municipio_id").on('change', function () {
				$("#municipio_id option:selected").each(function () {
					var id_municipio = $(this).val();
					$.post("select_localidades.php", { id_municipio: id_municipio }, function(data) {
						$("#localidad_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#localidad_id").on('change', function () {
				$("#localidad_id option:selected").each(function () {
					var id_localidad = $(this).val();
					$.post("select_barrios.php", { id_localidad: id_localidad }, function(data) {
						$("#barrio_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#barrio_id").on('change', function () {
				$("#barrio_id option:selected").each(function () {
					var id_barrio = $(this).val();
					$.post("select_nucleos.php", { id_barrio: id_barrio }, function(data) {
						$("#nucleo_id").html(data);
					});
				});
		   });
		});

		$(document).ready(function(){
			$("#nucleo_id").on('change', function () {
				$("#nucleo_id option:selected").each(function () {
					var id_nucleo = $(this).val();
					$.post("select_manzanas.php", { id_nucleo: id_nucleo }, function(data) {
						$("#manzana_id").html(data);
					});
				});
		   });
		});

	</script>
</head>
<body>
<div class="container">
   			<h6>UBICACION</h6>

            <div class="col bordder form-check-inline">
                <p class="mb-0">País</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="pais_id" name="pais_id" required>
                    <option value="">Elija</option>
					<?php foreach($paises as $c):?>
						  <option class="fs-6" value="<?php echo $c->COD_NUM; ?>"><?php echo $c->DESCRIPCION; ?></option>
					<?php endforeach; ?>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Región</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="region_id" name="region_id" required>
                    <option value=""><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Provincia</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="provincia_id" name="provincia_id" required>
                    <option value=""><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Comarca</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="comarca_id" name="comarca_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Municipio</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="municipio_id" name="municipio_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Localidad</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="localidad_id" name="localidad_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Barrio</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="barrio_id" name="barrio_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>

            <div class="col bordder form-check-inline">
                <p class="mb-0">Núcleo</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="nucleo_id" name="nucleo_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>
            <div class="col bordder form-check-inline">
                <p class="mb-0">Manzana</p>
                <select class="form-select form-select-sm" aria-label="Default select example" id="manzana_id" name="manzana_id" required>
                    <option selected><nav></nav></option>
                </select>            
            </div>
    </div>
</body>
</html>