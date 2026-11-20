<!DOCTYPE html>
<?php
	session_start();
	session_unset();
	$_SESSION['operacion']="S";
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
.my-custom-scrollbar {
	position: relative;
	height: 250px;
	overflow: auto;
	background: #C7F4D4;
}
.table-wrapper-scroll-y {
	display: block;
}
	
.form-check-inline {
	background-color: #C7F4D4;
	}
</style>

<script type="text/javascript">
	function manejo_estado_checkbok(checkbox){
		var nombreCheck  = checkbox.id;
//		alert(nombreCheck);
		var nombreCampo  = nombreCheck + 'Cpo';
//		alert(nombreCampo);
		//Si está marcada ejecuta la condición verdadera.
		if(checkbox.checked){
	//			alert('CHEKED - Nombre checkbox: ' + nombrechek + '  ' + 'Nombre campo: ' + nombrecampo);
			document.getElementById(nombreCampo).readOnly=false;
		}
		//Si se ha desmarcado se ejecuta el siguiente mensaje.
		else{
	//			alert('NO CHEKED - Nombre checkbox: ' + nombrechek + '  ' + 'Nombre campo: ' + nombrecampo);
			var valor = document.getElementById(nombreCampo);
			valor.readOnly=true;
			valor.value="";
			valor.required=true;
		}
	}
</script>
	

<script type="text/javascript">

window.onload = function () {
		var parametros = {
		"action": "ajax",
		"nro_en_form": "01",
		"titulo": "TIPO DE OPERACIÓN",
		"tiposelector": "radio"
		};
		$.ajax({
			url: 'ajax/obtener_detalle.php',
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_01").html(data).fadeIn('slow');
			}
		})

		var parametros = {
		"action": "ajax",
		"nro_en_form": "02",
		"titulo": "TIPO DE INMUEBLE",
		"tiposelector": "radio"
		};
	
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_02").html(data).fadeIn('slow');
		}
	})
}

$(document).on('change', 'input[name=radio_02_0]', function(event) {
	var tipoPropiedad = $(this).val();
	alert(tipoPropiedad);
	var parametros = {
		"action": "ajax",
		"nro_en_form": "02A",
		"titulo": "PRECIO Y MONEDA",
        "propiedad": tipoPropiedad,
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_02A").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "geo",
        "propiedad": tipoPropiedad,
		"titulo": "UBICACION"
//            "agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_ubicacion.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_geo").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "03",
        "propiedad": tipoPropiedad,
		"titulo": "AREA ESTAR Y DORMITORIOS",
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_03").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "04",
		"titulo": "AREA COCINA Y BAÑOS",
        "propiedad": tipoPropiedad,
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_04").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "05",
		"titulo": "OTRAS DEPENDENCIAS",
        "propiedad": tipoPropiedad,
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_05").html(data).fadeIn('slow');
		}
	})
	
	var parametros = {
	"action": "ajax",
	"nro_en_form": "06",
	"titulo": "DETALLES IMPORTANTES",
    "propiedad": tipoPropiedad,
	"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_06").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "06A",
		"titulo": "ORIENTACION – CERCANIAS",
        "propiedad": tipoPropiedad,
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_06A").html(data).fadeIn('slow');
		}
	})

	var parametros = {
		"action": "ajax",
		"nro_en_form": "07",
		"titulo": "SERVICIOS DISPONIBLES",
        "propiedad": tipoPropiedad,
		"agrpradio":"N"
	};
	$.ajax({
		url: 'ajax/obtener_detalle.php',
		data: parametros,
		beforeSend: function(objeto) {},
		success: function(data) {
			$(".outer_div_07").html(data).fadeIn('slow');
		}
	})
	});

</script>
</head>
<body>
    <div class="container">
        <ul class="nav nav-tabs" id="formTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Tipo Operación/Inmueble</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos1-tab" data-bs-toggle="tab" data-bs-target="#datos1" type="button" role="tab" aria-controls="datos1" aria-selected="false">Precio/Ubicación</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos2-tab" data-bs-toggle="tab" data-bs-target="#datos2" type="button" role="tab" aria-controls="datos2" aria-selected="false">Area Estar/Servicios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos3-tab" data-bs-toggle="tab" data-bs-target="#datos3" type="button" role="tab" aria-controls="datos3" aria-selected="false">Dependencias/Detalles</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos4-tab" data-bs-toggle="tab" data-bs-target="#datos4" type="button" role="tab" aria-controls="datos4" aria-selected="false">Orientación/Servicios</button>
            </li>
        </ul>

        <form class="row needs-validation" method="POST" action="MostrarEnvíoFormTabs.php" novalidate>
            <br>
            <div class="tab-content">
              <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="form-group row">
						<div class="col col-6">
							<p class="outer_div_01"></p>
						</div>
						<br>
					</div>
                    <div class="form-group row">
						<div class="col col-6">
							<p class="outer_div_02"></p>
						</div>
						<br>
					</div>
				</div>

				<div class="tab-pane" id="datos1" role="tabpanel" aria-labelledby="datos2-tab">
                    <div class="form-group row">
						<div class="col col-6">
							<p class="outer_div_02A"></p>
						</div>
						<br>
					</div>
                    <div class="form-group row">
						<div class="col col-10">
				            <p class="outer_div_geo"></p>
						</div>
						<br>
					</div>
                </div>
				
				<div class="tab-pane" id="datos2" role="tabpanel" aria-labelledby="datos2-tab">
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_03"></p>
						</div>
                    </div>
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_04"></p>
						</div>
                    </div>
                </div>
				
                <div class="tab-pane" id="datos3" role="tabpanel" aria-labelledby="datos3-tab">
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_05"></p>
						</div>
                    </div>
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_06"></p>
						</div>
                    </div>
                </div>

                <div class="tab-pane" id="datos4" role="tabpanel" aria-labelledby="datos3-tab">
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_06A"></p>
						</div>
                    </div>
                    <div class="form-group">
						<div class="col col-6">
							<p class="outer_div_07"></p>
						</div>
                    </div>
          		</div>
        	</div>    
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
