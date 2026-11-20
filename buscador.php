<!doctype html>

<?php
	session_start();
	unset($_SESSION['cp']);
	unset($_SESSION['datos']);
	unset($_SESSION['operacionCP']);
//	session_unset();
	require_once ("conexion.php");
	require_once ("cargarDatosFormulario.php");
	$busquedaPublica="";
	$_SESSION['operacion'] = "B";
	if (!$_SESSION['busquedaPublica']){
		$anunciante = $_SESSION['anunciante'];
		if ($_SESSION['operacion'] == 'A'){
			$anuncio=Funciones::obtenerNroAnuncio($con);
			$_SESSION['anuncio']=$anuncio;
			echo ("Anunciante: ".$_SESSION['anunciante'].", nuevo anuncio: ".$anuncio);
		}
	} else {
		$busquedaPublica = $_SESSION['busquedaPublica'];
	}
?>

<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" href="dist/css/bootstrap.css">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
    <script type="text/javascript">
		var tipoPropiedad; 

    window.onload = function() {
		(() => {
		'use strict';
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		const forms = document.querySelectorAll('.needs-validation');
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms).forEach((form) => {
			form.addEventListener('submit', (event) => {
			if (!form.checkValidity()) {
				event.preventDefault();
				event.stopPropagation();
				alert("Por favor, agregue la información solicitada.")
			}
			form.classList.add('was-validated');
			}, false);
		});
		})();

        var parametros = {
            "action": "ajax",
            "nro_en_form": "01",
            "titulo": "TIPO DE OPERACION",
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

    }

	$(document).on('change', 'input[name=radio_01_0]', function(event) {
		VentaAlquiler = $(this).val();

		var parametros = {
			"action": "ajax",
			"nro_en_form": "02A",
	        "titulo": "PRECIO Y MONEDA",
			"titulo": "",
            "ventaalquiler": VentaAlquiler,
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
			"nro_en_form": "02",
	        "titulo": "TIPO DE INMUEBLE",
			"titulo": "",
			"tiposelector": "radio",
            "ventaalquiler": VentaAlquiler,
			"agrpradio": "S"
		};
		$.ajax({
			url: 'ajax/obtener_detalle.php',
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_02").html(data).fadeIn('slow');
			}
		})
	});
/*
    $(document).on('change', 'input[name=radio_02_0]', function(event) {
 		tipoPropiedad = $(this).val();
    });
*/
    $(document).on('change', 'input[name=radio_02_0]', function(event) {
 		tipoPropiedad = $(this).val();

		var parametros = {
            "action": "ajax",
            "nro_en_form": "geo",
            "titulo": "UBICACION"
//            "agrpradio":"N"
        };
        $.ajax({
            url: 'obtener_ubic_anuncios.php',
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_geo").html(data).fadeIn('slow');
            }
        })
    }
	);

    $(document).ready(function(){
        $(document).on('change', 'select[name=selCP]', function () {
            $("#enviarDatos").attr("disabled", false);
            $("#selCP option:selected").each(function (){

            var CP_LOC = $(this).val().split('+');
            var CP = CP_LOC[0];
            var CPLOC = CP_LOC[1];

            var parametros = {
                "action": "ajax",
                "nro_en_form": "09",
                "titulo": "SERVICIOS REGIONALES",
                "propiedad": "",
                "cp": CP,
                "loccp": CPLOC,
                "agrpradio":"N",
                "estado":"U",
                "primeraVez": "S"
		      };
		  $.ajax({
            url: 'ajax/obtener_detalleCP.php',
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_09").html(data).fadeIn('slow');
			}
		});

        var LOC = $(this).text().split('_');
        var LOCTXT = LOC[1];
        //var LOCLIMPIA = LOCTXT.replace("'", ' ');
        //alert(LOCTXT);
        var parametros = {
            "locBuscar": LOCTXT,
            "porciento":"",
            "soloCP": "U"
		};
		$.ajax({
			url: 'buscarLocalidadyCP.php',
			data: parametros,
			success: function(data) {
				$(".listaUbicCP").html(data).fadeIn('slow');
			}
		});
            });
        });
    });

    $(document).on('change', 'input[name=checkbox_09_32]', function () {
            $("#enviarDatos").attr("disabled", false);
	});
					   
    $(document).on('change', 'select[name=ubic_pais_id]', function () {
            $("#enviarDatos").attr("disabled", false);
	});
					   
//   Código Postal   
    $(document).on('change', 'input[name=valor_checkbox_09_32]', function(event) {
        var CP = $(this).val();
		var lngCP=CP.length;
		if (lngCP == '3'){var cpF = "00" + $(this).val()};
		if (lngCP == '4'){var cpF = "0" + $(this).val()};
		if (lngCP == '5'){var cpF = $(this).val()};
		
		document.getElementById('valor_checkbox_09_32').value=cpF;
		$(this).text=cpF;

//		alert($(this).val());
		var parametros = {
			"action": "ajax",
			"nro_en_form": "09",
			"titulo": "SERVICIOS REGIONALES",
			"propiedad": "",
			"cp": $(this).val(),
			"agrpradio":"N",
			"primeraVez": "S",
            "estado": "enable"
		};
		$.ajax({
			url: 'ajax/obtener_detalleCP.php',
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_09").html(data).fadeIn('slow');
			}
		});
	})

    $(document).on('change', 'input[name=locBuscar]', function(event) {
//		alert("Localidad a buscar: "+$(this).val());
        
		document.getElementById('buscarPorUbicacion').style.display = 'none';

        var parametros = {
            "locBuscar":$(this).val(),
            "porciento":"%",
            "soloCP": "S"
		};
		$.ajax({
			url: 'buscarLocalidadyCP.php',
			data: parametros,
			success: function(data) {
				$(".listaUbicCP").html(data).fadeIn('slow');
			}
		});
	})

    $(document).on('change', 'select[name=ubic_region_id]', function(event) {
        var valor = document.getElementById('ubic_region_id').value;
		$("#ubic_region_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_provincia_id]', function(event) {
        var valor = document.getElementById('ubic_provincia_id').value;
		$("#ubic_provincia_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_comarca_id]', function(event) {
        var valor = document.getElementById('ubic_comarca_id').value;
		$("#ubic_comarca_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_municipio_id]', function(event) {
        var valor = document.getElementById('ubic_municipio_id').value;
		$("#ubic_municipio_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_localidad_id]', function(event) {
        var valor = document.getElementById('ubic_localidad_id').value;
		$("#ubic_localidad_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_barrio_id]', function(event) {
        var valor = document.getElementById('ubic_barrio_id').value;
		$("#ubic_barrio_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_nucleo_id]', function(event) {
        var valor = document.getElementById('ubic_nucleo_id').value;
		$("#ubic_nucleo_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=ubic_manzana_id]', function(event) {
        var valor = document.getElementById('ubic_manzana_id').value;
		$("#ubic_manzana_id option[value="+ valor +"]").attr("selected",true);
	})

    $(document).on('change', 'select[name=selCP]', function(event) {
        var texto = $('select[name="ubic_localidad_id"] option:selected').text();
//        alert(texto);        
        document.getElementById('IngresoLocalidad').style.display = 'none';
        document.getElementById('bloqueUbicacion').style.display = 'block';
	})

    $(document).on('change', 'input[name=BuscaUbicacion]', function(event) {
        document.getElementById('BuscaLocalidad').checked = false;
        document.getElementById('BuscaLocalidad').style.display = 'none';
        document.getElementById('labelLocalidad').style.display = 'none';
        document.getElementById('IngresoLocalidad').style.display = 'none';
        document.getElementById('listaUbicacionCP').style.display = 'block';
        document.getElementById('buscarPorUbicacion').style.display = 'block';
//        alert("Reejecutamos CP");

        var parametros = {
                "action": "ajax",
                "nro_en_form": "09",
                "titulo": "SERVICIOS REGIONALES",
                "propiedad": "",
                "cp": "", // $(this).val(),
                "agrpradio":"N",
				"soloCP":"N",
                "primeraVez": "S"
            };
            $.ajax({
                url: 'cargarUbicacionCP.php',
                data: parametros,
                beforeSend: function(objeto) {},
                success: function(data) {
                    $(".recargaUbicacionCP").html(data).fadeIn('slow');
                }
            });

	})

    $(document).on('change', 'input[name=BuscaLocalidad]', function(event) {
        document.getElementById('BuscaUbicacion').checked = false;
        document.getElementById('IngresoLocalidad').style.display = 'block';
        document.getElementById('buscarPorUbicacion').style.display = 'none';
        document.getElementById('listaUbicacionCP').style.display = 'block';
    });

</script>

	<script type="text/javascript">
		function manejo_estado_checkbok(checkbox){
			var nombrechek  = checkbox.name;
			var long        = nombrechek.length;		
			var prefijo     = nombrechek.indexOf('_');
			var sufijo      = nombrechek.substr(prefijo);
			var nombrecampo = 'valor_checkbox' + sufijo;
			if(checkbox.checked){
				document.getElementById(nombrecampo).readOnly=false;
			} else { 
			  if (nombrechek != "checkbox_09_32"){
				 var valor = document.getElementById(nombrecampo);
				 valor.readOnly=true;
				 valor.value="";
				 valor.required=true;
			  } else {
				  var valor = document.getElementById(nombrecampo);
				  if (valor.value == ""){
				  	checkbox.checked=false;
				  } else {
				  	checkbox.checked=true;
				  }
			  }
			}
			return;
		}
	</script>

	<script type="text/javascript">
	function enableSelects(){
		$("#ubic_pais_id").attr("disabled", false);
		$("#ubic_region_id").attr("disabled", false);
		$("#ubic_provincia_id").attr("disabled", false);
		$("#ubic_comarca_id").attr("disabled", false);
		$("#ubic_municipio_id").attr("disabled", false);
		$("#ubic_localidad_id").attr("disabled", false);
		$("#ubic_barrio_id").attr("disabled", false);
		$("#ubic_nucleo_id").attr("disabled", false);
		$("#ubic_manzana_id").attr("disabled", false);
		$("#BuscaLocalidad").attr("disabled", true);
		$("#BuscaUbicacion").attr("disabled", true);
		$("#locBuscar").attr("disabled", true);
		$("#selCP").attr("disabled", true);
	}	
	</script>

	<script type="text/javascript">
	function volverAIndiceNav(){
	 window.location.href = 'indiceNav.php';
	}

	function volverAIndex(){
	 window.location.href = 'index.php';
	}
	</script>
	
</head>

<body>
    <div class="container-sm mt-5">
		<div> <a href="indiceNavParticular.php"><img src="imagenes/logoClaro.png" width="150" height="75" alt="Inicio"/></a> </div>
	  <?php
			if ($_SESSION['operacion'] == 'A') {?>
        		<h1 align="center" class="text-verdemarcelo display-6">Ingreso de Publicación</h1>
		<?php } else {?>
        		<h1 align="center" class="text-verdemarcelo display-6">Buscar Publicaciones</h1>
		<?php } ?>
        <form class="form form-control needs-validation" onSubmit="enableSelects()" style="background: #B4DEE8" action="actualizaAnuncioBD.php" method="post" novalidate>
			<input class="alert-success" type="button" value="Volver" <?php 
				   														if ($_SESSION['operacion'] == 'A'){
																			echo ('onClick="history.go(-1);">');
																		} else {
																			if (empty($busquedaPublica)){
																				echo ('onClick="volverAIndiceNav();">');
																			} else {
																				echo ('onClick="volverAIndex();">');
																			}
																		}
				   													  ?>
            <br>
            <div class="d-flex flex-wrap flex-row">
                <p class="outer_div_01"></p>
                <p class="outer_div_02A"></p>
                <p class="outer_div_02"></p>
            </div>
            <div class="d-flex flex-wrap flex-row">
                <p class="outer_div_geo"></p>
            </div>
            
            <br>
			<input class="alert-success" type="submit" id = "enviarDatos" value=
				   "<?php 
				   if ($_SESSION['operacion'] == 'A'){
				         echo ("Confirmar");
				   } else {
					     echo ("Buscar");}
				   ?> " disabled>
			<input class="alert-success" type="button" value="Volver"  <?php 
				   														if ($_SESSION['operacion'] == 'A'){
																			echo ('onClick="history.go(-1);">');
																		} else {
																			if (empty($busquedaPublica)){
																				echo ('onClick="volverAIndiceNav();">');
																			} else {
																				echo ('onClick="volverAIndex();">');
																			}
																		}
				   													  ?>
        </form>
    </div>

</body>
</html>