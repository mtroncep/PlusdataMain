<!doctype html>
<?php
	session_start();
?>
    <script src="trad/traducciones.php"></script>
    <script src="trad/traductor.js"></script>
<?php
    if (!isset($_SESSION['codPais'])){
        echo '<script type="text/javascript">alert(traducir("Falta seleccionar el país"))</script>';
        $_SESSION['anuncioSelSinPais'] = "pruebaDetalles.php?operacion=".$_REQUEST['operacion'];
        return;
    }

	unset($_SESSION['cp']);
	unset($_SESSION['datos']);
	unset($_SESSION['anuncio']);
	unset($_SESSION['operacionCP']);
    unset($_SESSION['tipoBusqueda']);
	require_once ("conexion.php");
	require_once ("cargarDatosFormulario.php");
	$busquedaPublica="";
	$_SESSION['operacion'] = $_REQUEST['operacion'];

	if (!isset( $_SESSION['busquedaPublica'] )){
		$anunciante = $_SESSION['anunciante'];
		if ($_SESSION['operacion'] == 'A'){
			$anuncio=Funciones::obtenerNroAnuncio($con);
			$_SESSION['anuncio']=$anuncio;
//			echo ("Anunciante: ".$_SESSION['anunciante'].", nuevo anuncio: ".$anuncio);
		}
        if (!Funciones::obtenerEstadoSucursal($con,$anunciante) ){
            echo ("<script>alert('NO SE ENCUENTRA AUTORIZADO PARA CARGAR ANUNCIOS. POR FAVOR, COMUNIQUESE CON LA OFICINA CENTRAL');</script>");
            return;
        }
	} else {
		$busquedaPublica = $_SESSION['busquedaPublica'];
	}
?>

<html lang="es"><head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="css/homeNew.css" type="text/css">
    <link rel="stylesheet" href="dist/css/bootstrap.css">
<!--    <link rel="stylesheet" href="css/misEstilos.css"> -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!--	<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->


    <link rel="stylesheet" href="css/fuentes.css">
<!--    <link rel="stylesheet" href="css/mensajeGlobo.css"> -->
<!--    <script src="js/mensajeGlobo.js"></script> -->

<!--    <script src="trad/traduccion.php?lang=<?php //echo $_SESSION['idioma'];?>"></script>-->

<!-- <script src="trad/traducciones.php"></script> -->

<script type="text/javascript">
    var tipoPropiedad; 

    window.onload = function() {
		(() => {
        document.querySelector("form").addEventListener("submit", function(e) {
          let valid = true;
          let mensaje = null;
          let primerError = null;

          const requiredFields = document.querySelectorAll("[required]");

          requiredFields.forEach(field => {
            if (!field.readOnly){
                if (!field.value.trim()) {
                  field.classList.add("error");
                  if (!primerError) primerError = field;
                  valid = false;
                  mensaje = 'FALTA COMPLETAR DATOS OBLIGATORIOS, LOS MISMOS SE ENCUENTRAN MARCADOS EN ROJO';
                } else {
                  field.classList.remove("error");
                }
            }
          });
          if (mensaje != null){
              alert(mensaje);
          }
          if (!valid){
            e.preventDefault();
            primerError.scrollIntoView({ behavior: "smooth", block: "center" });
            primerError.focus();
          } 
        });
		})();

        const selProvincia = '';
        
        var parametros = {
            "action": "ajax",
            "nro_en_form": "01",
            "titulo": "TIPO DE OPERACION",
//			"agrpradio":"N",
			"tiposelector": "radio"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_01").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })
     }
/*
    function aplicarTraducciones() {
      document.querySelectorAll('[data-trad]').forEach(el => {
        const clave = el.getAttribute('data-trad');
        if (window.TRADUCCIONES?.[clave]) {
          el.innerText = window.TRADUCCIONES[clave];
        }
      });
    }
*/
	$(document).on('change', 'input[name=radio_01_0]', function(event) {
//        document.documentElement.setAttribute("data-loading", "true");
//        document.getElementById('loader').style.display = 'block';

        VentaAlquiler = $(this).val();

		var parametros = {
			"action": "ajax",
			"nro_en_form": "02A",
//	        "titulo": "PRECIO Y MONEDA",
			"titulo": "",
            "ventaalquiler": VentaAlquiler,
			"agrpradio":"N"
		};
		$.ajax({
			url: 'ajax/obtener_detalle.php',
            async: false,
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_02A").html(data).fadeIn('slow');
                aplicarTraducciones();
			}
		})

        var parametros = {
			"action": "ajax",
			"nro_en_form": "02",
//	        "titulo": "TIPO DE INMUEBLE",
			"titulo": "",
			"tiposelector": "radio",
            "ventaalquiler": VentaAlquiler,
			"agrpradio": "S"
		};
		$.ajax({
			url: 'ajax/obtener_detalle.php',
            async: false,
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_02").html(data).fadeIn('slow');
                aplicarTraducciones();
			}
		})

//        document.getElementById('loader').style.display = 'none';

	});

    $(document).on('change', 'input[name=radio_02_0]', function(event) {
//       document.getElementById('loader').style.display = 'block';
//        validarMonedaPrecio(); 

        tipoPropiedad = $(this).val();

		var parametros = {
            "action": "ajax",
            "nro_en_form": "02C",
            "titulo": "",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
			"agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_02C").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

		var parametros = {
            "action": "ajax",
            "nro_en_form": "geo",
            "titulo": "UBICACION GEOGRAFICA Y MEDIDAS",
            "tipopropiedad": tipoPropiedad
//            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_ubicacion_MAPA.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_geo").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "03",
            "titulo": "AREA ESTAR Y DORMITORIOS",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_03").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "04",
            "titulo": "AREA COCINA Y BAÑOS",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_04").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "05",
            "titulo": "OTRAS DEPENDENCIAS",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_05").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "06",
            "titulo": "DETALLES IMPORTANTES",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_06").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "06A",
            "titulo": "ORIENTACIÓN Y CERCANÍAS",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_06A").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "07",
            "titulo": "SERVICIOS DISPONIBLES",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_07").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "08",
            "titulo": "ESTADO DEL INMUEBLE",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_08").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "09",
            "titulo": "SERVICIOS REGIONALES",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
			"agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_09").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "10",
            "titulo": "SITUACION LEGAL",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_10").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "11",
            "titulo": "EQUIPAMIENTO RURAL",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_11").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "12",
            "titulo": "ARBOLADO Y CULTIVOS",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_12").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "13",
            "titulo": "DATOS ADICIONALES",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "agrpradio":"N",
			"tiposelector": "textarea"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_13").html(data).fadeIn('slow');
                aplicarTraducciones();
 //               document.documentElement.setAttribute("data-loading", "false");
            }
        })
        
        $.ajax({
            url: 'ajax/obtener_mensajes.php',
            async: false,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_mensajes").html(data).fadeIn('slow');
            }
        })

//        document.getElementById('loader').style.display = 'none';
    }
	);

    $(document).ready(function(){
        $(document).on('change', 'select[name=selCP]', function () {
            $("#enviarDatos").attr("disabled", false);
            $("#enviarDatosB").attr("disabled", false);
            $("#selCP option:selected").each(function () {

            var CP_LOC = $(this).val().split('+');
            var CP = CP_LOC[0];
            var CPLOC = CP_LOC[1];
            if (typeof CPLOC === "undefined") {
                CPLOC="%";
            }

            alfabetico=/^[A-Za-z %]+$/.test(CPLOC);
//            alert ("CP: "+CP+", LOC: "+CPLOC+", ¿Es alfabático? "+alfabetico);

            var operacion = "<?php echo $_SESSION['operacion']?>";

            if (CPLOC !== ""){
//                alert("CPLOC: "+CPLOC);
                var parametros = {
                    "action": "ajax",
                    "nro_en_form": "09",
                    "titulo": "SERVICIOS REGIONALES",
                    "propiedad": "",
                    "cp": CP,
                    "loccp": CPLOC,
                    "agrpradio":"N",
                    "estado":"",
                    "propiedad": tipoPropiedad,
                    "ventaalquiler": VentaAlquiler,
                    "primeraVez": "S",
    //                "primeraVez": "N",
                    "tipoBusqueda":"L"
		        }
                $.ajax({
                url: 'ajax/obtener_detalleCP.php',
                data: parametros,
                beforeSend: function(objeto) {},
                success: function(data) {
                    $(".outer_div_09").html(data).fadeIn('slow');
                    aplicarTraducciones();
                    }
                });
                
                var LOC = $(this).text().split('_');
                var LOCTXT = LOC[1];
                var LOCCP  = LOC[2];
//                alert ("LOCTXT: "+LOCTXT+", LOCCP: "+LOCCP+", ¿Es alfabático? "+alfabetico);

                var parametros = {
                    "locBuscar": LOCTXT,
                    "locBuscarCP": LOCCP,
                    "porciento":"",
                    "tipopropiedad": tipoPropiedad,
                    "lineasSel": "",
                    "soloCP": "U"
                };
                $.ajax({
                    url: 'buscarLocalidadyCP.php',
                    data: parametros,
                    success: function(data) {
                        $(".listaUbicCP").html(data).fadeIn('slow');
                    }
                });
                
            } else {
//                    alert("CP/CPLOC: "+CP+"/"+CPLOC);

                    var parametros = {
                    "action": "ajax",
                    "nro_en_form": "09",
                    "titulo": "SERVICIOS REGIONALES",
                    "propiedad": "",
                    "cp": CP,
                    "loccp": CPLOC,
                    "agrpradio":"N",
                    "estado":"U",
                    "primeraVez": "S",
                    "tipoBusqueda":"C"
                    }
                  $.ajax({
                    url: 'ajax/obtener_detalleCP.php',
                    data: parametros,
                    beforeSend: function(objeto) {},
                    success: function(data) {
                        $(".outer_div_09").html(data).fadeIn('slow');
                        aplicarTraducciones();
                        }
                    });

                    var LOC = $(this).text().split('_');
                    var LOCTXT = LOC[1];
                    var LOCCP  = LOC[2];
                    
//                    alert("loctxt: "+LOCTXT+", loccp: "+LOCCP);
                    
                    var parametros = {
                        "locBuscar": LOCTXT,
                        "locBuscarCP": LOCCP,
                        "porciento":"",
                        "tipopropiedad": tipoPropiedad,
                        "lineasSel": "",
                        "soloCP": "S"
                    };
                    $.ajax({
                        url: 'buscarLocalidadyCP.php',
                        data: parametros,
                        success: function(data) {
                            $(".listaUbicCP").html(data).fadeIn('slow');
                        }
                    });
            }
            });
        });
    });

    $(document).on('change', 'input[name=checkbox_09_32]', function () {
            $("#enviarDatos").attr("disabled", false);
            $("#enviarDatosB").attr("disabled", false);
	});


    $(document).on('click', 'select[name=ubic_pais_id]', function () {
            $("#enviarDatos").attr("disabled", false);
            $("#enviarDatosB").attr("disabled", false);
		    document.getElementById('IngresoLocalidad').style.display = 'none';
	});
					   					   
//   CÃ³digo Postal   
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
			"titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
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
                aplicarTraducciones();
			}
		});
	})

    $(document).on('change', 'select[name=ubic_region_id]', function(event) {
        var valor = document.getElementById('ubic_region_id').value;
		$("#ubic_region_id option[value="+ valor +"]").attr("selected",true);
//        alert("Cambio Region: "+valor);
	})

    $(document).on('change', 'select[name=ubic_provincia_id]', function(event) {
        var valor = document.getElementById('ubic_provincia_id').value;
        $("#ubic_provincia_id option[value="+ valor +"]").attr("selected",true);
//        console.log("Cambio Provincia: "+valor);
	})

    $(document).on('change', 'select[name=ubic_comarca_id]', function(event) {
        var valor = document.getElementById('ubic_comarca_id').value;
		$("#ubic_comarca_id option[value="+ valor +"]").attr("selected",true);
//        console.log("Cambio Comarca: "+valor);
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

    $(document).on('change', 'input[id=checkbox_06A_70]', function(event) {
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var check = document.getElementById('checkbox_06A_70').checked;
        var checkCant = document.getElementById('checkbox_06A_74');

        var check3 = null;
        if (document.getElementById('checkbox_06A_71') !== null){
            var check3 = document.getElementById('checkbox_06A_71').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_06A_72') !== null){
            var check4 = document.getElementById('checkbox_06A_72').checked;
        }

        var check5 = null;
        if (document.getElementById('checkbox_06A_73') !== null){
            var check5 = document.getElementById('checkbox_06A_73').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_06A_74').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_06A_74').checked=true;
                document.getElementById('valor_checkbox_06A_74').readOnly=false;
            }
        } else {
            if (!check3 && !check4 && !check5){
                document.getElementById('checkbox_06A_74').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_06A_74').readOnly=true;
                document.getElementById('valor_checkbox_06A_74').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_06A_71]', function(event) {
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var check = document.getElementById('checkbox_06A_71').checked;
        var checkCant = document.getElementById('checkbox_06A_74');

        var check3 = null;
        if (document.getElementById('checkbox_06A_70') !== null){
            var check3 = document.getElementById('checkbox_06A_70').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_06A_72') !== null){
            var check4 = document.getElementById('checkbox_06A_72').checked;
        }

        var check5 = null;
        if (document.getElementById('checkbox_06A_73') !== null){
            var check5 = document.getElementById('checkbox_06A_73').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_06A_74').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_06A_74').checked=true;
                document.getElementById('valor_checkbox_06A_74').readOnly=false;
            }
        } else {
            if (!check3 && !check4 && !check5){
                document.getElementById('checkbox_06A_74').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_06A_74').readOnly=true;
                document.getElementById('valor_checkbox_06A_74').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_06A_72]', function(event) {
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var check = document.getElementById('checkbox_06A_72').checked;
        var checkCant = document.getElementById('checkbox_06A_74');

        var check3 = null;
        if (document.getElementById('checkbox_06A_70') !== null){
            var check3 = document.getElementById('checkbox_06A_70').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_06A_71') !== null){
            var check4 = document.getElementById('checkbox_06A_71').checked;
        }

        var check5 = null;
        if (document.getElementById('checkbox_06A_73') !== null){
            var check5 = document.getElementById('checkbox_06A_73').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_06A_74').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_06A_74').checked=true;
                document.getElementById('valor_checkbox_06A_74').readOnly=false;
            }
        } else {
            if (!check3 && !check4 && !check5){
                document.getElementById('checkbox_06A_74').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_06A_74').readOnly=true;
                document.getElementById('valor_checkbox_06A_74').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_06A_73]', function(event) {
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var check = document.getElementById('checkbox_06A_73').checked;
        var checkCant = document.getElementById('checkbox_06A_74');

        var check3 = null;
        if (document.getElementById('checkbox_06A_70') !== null){
            var check3 = document.getElementById('checkbox_06A_70').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_06A_71') !== null){
            var check4 = document.getElementById('checkbox_06A_71').checked;
        }

        var check5 = null;
        if (document.getElementById('checkbox_06A_72') !== null){
            var check5 = document.getElementById('checkbox_06A_72').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_06A_74').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_06A_74').checked=true;
                document.getElementById('valor_checkbox_06A_74').readOnly=false;
            }
        } else {
            if (!check3 && !check4 && !check5){
                document.getElementById('checkbox_06A_74').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_06A_74').readOnly=true;
                document.getElementById('valor_checkbox_06A_74').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_05_70]', function(event) {
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var check = document.getElementById('checkbox_05_70').checked;
        var checkCant = document.getElementById('checkbox_05_73');

        var check3 = null;
        if (document.getElementById('checkbox_05_71') !== null){
            var check3 = document.getElementById('checkbox_05_71').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_05_72') !== null){
            var check4 = document.getElementById('checkbox_05_72').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_05_73').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_05_73').checked=true;
                document.getElementById('valor_checkbox_05_73').readOnly=false;
            }
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_05_71]', function(event) {
        var check = document.getElementById('checkbox_05_71').checked;
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var checkCant = document.getElementById('checkbox_05_73');

        var check3 = null;
        if (document.getElementById('checkbox_05_70') !== null){
            var check3 = document.getElementById('checkbox_05_70').checked;
        }

        var check4 = null;
        if (document.getElementById('checkbox_05_72') !== null){
            var check4 = document.getElementById('checkbox_05_72').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_05_73').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_05_73').checked=true;
                document.getElementById('valor_checkbox_05_73').readOnly=false;
            }
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_05_72]', function(event) {
        var check = document.getElementById('checkbox_05_72').checked;
        var operacion = '<?php echo $_SESSION['operacion']?>';
        var checkCant = document.getElementById('checkbox_05_73');

        var check3 = null;
        if (document.getElementById('checkbox_05_70') !== null){
            var check3 = document.getElementById('checkbox_05_70').checked;
        }

        var check4 = null;
        if (document.getElementById('checkbox_05_71') !== null){
            var check4 = document.getElementById('checkbox_05_71').checked;
        }

        if (check){
            if (operacion == 'B') {
                document.getElementById('checkbox_05_73').checked=true;
                manejo_estado_checkbok(checkCant);
            } else {
                document.getElementById('checkbox_05_73').checked=true;
                document.getElementById('valor_checkbox_05_73').readOnly=false;
            }
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                manejo_estado_checkbok(checkCant);
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
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
                "titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
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
                    aplicarTraducciones();
                }
            });

	})

    $(document).on('change', 'input[name=BuscaLocalidad]', function(event) {
        document.getElementById('BuscaUbicacion').checked = false;
        document.getElementById('IngresoLocalidad').style.display = 'block';
        document.getElementById('buscarPorUbicacion').style.display = 'none';
        document.getElementById('listaUbicacionCP').style.display = 'block';
    });

//    $(document).on('change', 'input[id=valor_checkbox_02A_1]', function(event) {

    $(document).on('focusout', 'input', function(event) {
        var nombreDesde = this.name;
        var valor = this.value;
        if (valor.length == 0){
            document.getElementById(nombreDesde).value="";
            valor = "";
        }
        //        alert (nombreDesde);
        var hst = nombreDesde.search("hst")
        var nombreHasta = nombreDesde + "hst";

        if (document.getElementById(nombreHasta) !== null){
            if ((nombreDesde.substr(0,14) == "valor_checkbox") && (hst == -1)){
                var valorHasta = document.getElementById(nombreHasta);
                valorHasta.value=valor;
            }
        } else {
            valor = "";
        }
    });
    
    </script>

	<script type="text/javascript">
        function manejo_checkbox_moneda(moneda){
            var monedaNombre = moneda.name;

            var monedaEuro    = document.getElementById("checkbox_02A_euro");
            var monedaDolar   = document.getElementById("checkbox_02A_dolar");
            var monedaPesosAR = document.getElementById("checkbox_02A_argentino");
            var monedaPesosUY = document.getElementById("checkbox_02A_uruguay");
            var monedaRealBR  = document.getElementById("checkbox_02A_real");

            var cantMonedaCheck = 0;
            
            if (monedaEuro.checked){
                cantMonedaCheck = cantMonedaCheck + 1;
            }
            if  (monedaDolar.checked){
                cantMonedaCheck = cantMonedaCheck + 1;
            }
            if (monedaPesosAR.checked){
                cantMonedaCheck = cantMonedaCheck + 1;
            } 
            if (monedaPesosUY.checked){
                cantMonedaCheck = cantMonedaCheck + 1;
            } 
            if (monedaRealBR.checked){
                cantMonedaCheck = cantMonedaCheck + 1;
            }
            if (cantMonedaCheck > 1){
                alert("SE PERMITE SELECCIONAR SOLO UNA MONEDA.")
                return false;
            }
// Resaltamos la opcion seleccionada...
            var labelnombrecheck = moneda.id;
            var nombrelabel = 'label_' + labelnombrecheck;
            if(moneda.checked){
                document.getElementById(nombrelabel).style.backgroundColor = '#ffd700';
            } else {
                document.getElementById(nombrelabel).style.backgroundColor = '';
            }
        }

        function manejo_estado_checkbok(checkbox){
            if (document.getElementsByName(checkbox.name).length > 1){
                var aux = checkboxExcluyentes(checkbox);
            } ;

            var tipocampo = checkbox.id;
            var operacion = '<?php echo $_SESSION['operacion']?>';
//            alert(operacion);

            if (operacion === 'B'){
                var labelnombrecheck = checkbox.id;
                var nombrelabel = 'label_' + labelnombrecheck;
                console.log("Label: "+nombrelabel);
                if(checkbox.checked){
                    document.getElementById(nombrelabel).style.backgroundColor = '#ffd700';
                } else {
                    document.getElementById(nombrelabel).style.backgroundColor = '';
                }
            }
            
            var nombrechek  = checkbox.id;
			var long        = nombrechek.length;		
			var prefijo     = nombrechek.indexOf('_');
			var sufijo      = nombrechek.substr(prefijo);
			var nombrecampo    = 'valor_checkbox' + sufijo;
			var nombrecampoHST = 'valor_checkbox' + sufijo + 'hst';
//            alert(nombrechek+" "+nombrecampo+" "+nombrecampoHST);
			if(checkbox.checked){
                if (document.getElementById(nombrecampo) !== null){
                    document.getElementById(nombrecampo).readOnly=false;
                    document.getElementById(nombrecampo).value = "";
                    if (document.getElementById(nombrecampoHST) !== null){
                        document.getElementById(nombrecampoHST).readOnly=false;
                        document.getElementById(nombrecampoHST).value = "";
                        document.getElementById(nombrecampoHST).removeAttribute("hidden");
                    }
                }
            } else {
//                checkbox.checked=true;
			  if (nombrechek !== "checkbox_09_32"){
                if (document.getElementById(nombrecampo) !== null){
                     var valor = document.getElementById(nombrecampo);
                     valor.readOnly=true;
                     valor.value="";
                     valor.required=true;
                     valor.classList.remove("error");

                    if (document.getElementById(nombrecampoHST) !== null){
                         var valor = document.getElementById(nombrecampoHST);
                         valor.readOnly=true;
                         valor.value="";
                         valor.required=true;
                         valor.setAttribute("hidden", true);
                    }
                }
			  } else {
				  var valor = document.getElementById(nombrecampo);
				  if (valor.value === ""){
				  	checkbox.checked=false;
				  } else {
				  	checkbox.checked=true;
                    valor.value="";
				  }
			  }
			}
			return;
		}

        function manejo_estado_textarea(checkbox){
            var nombrechek  = checkbox.id;
			var long        = nombrechek.length;		
			var prefijo     = nombrechek.indexOf('_');
			var sufijo      = nombrechek.substr(prefijo);
			var nombrecampo = 'tvalo_textarea' + sufijo;
//            alert(nombrecampo);
            if(checkbox.checked){
                if (document.getElementById(nombrecampo) !== null){
                    document.getElementById(nombrecampo).readOnly=false;
                    document.getElementById(nombrecampo).value = "";
                }
            } else {
//                checkbox.checked=true;
			  if (nombrechek !== "checkbox_09_32"){
                if (document.getElementById(nombrecampo) !== null){
                     var valor = document.getElementById(nombrecampo);
                     valor.readOnly=true;
                     valor.value="";
                     valor.required=true;
                }
			  } else {
				  var valor = document.getElementById(nombrecampo);
				  if (valor.value === ""){
				  	checkbox.checked=false;
				  } else {
				  	checkbox.checked=true;
                    valor.value="";
				  }
			  }
			}
			return;
		}
    </script>

    <script type="text/javascript">
        function checkboxExcluyentes(event) {
            document.getElementsByName(event.name).forEach((item) =>
            { if (item.id !== event.id) item.checked = false;
                document.getElementById('label_'+item.id).style.backgroundColor = '';
            });
        }
    </script>

    <script type="text/javascript">
		function manejo_cpo_numerico(campo){
            var nombrecponumerico = campo.name;
//            alert(nombrecponumerico);
//            let myNumericInput = new AutoNumeric(campo,{decimalPlaces: 3});
//            alert("Valor campo: "+campo.value);
            if (campo.value === ""){
                let myNumericInput = new AutoNumeric(campo, {digitGroupSeparator: '.',
                                                             decimalCharacter   : ',',
                                                             decimalPlaces: 0,
                                                             modifyValueOnWheel: false,
                                                             selectOnFocus: false
                                                            }
                                                            );
            } else {
                let myNumericInput = AutoNumeric('init');
            }
//            let myNumericInput = new AutoNumeric(campo, {decimalPlaces: 0} );
			return;
		  }
	</script>

	<script type="text/javascript">
        function validarFormulario(){
            var operacion = '<?php echo $_SESSION['operacion']?>';
//            alert (operacion);
                if (!controlSector('checkbox_03','AREA ESTAR Y DORMITORIOS', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_04','AREA COCINA Y BAÃ‘OS', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_06','DETALLES IMPORTANTES', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_07','SERVICIOS DISPONIBLES', operacion)){
                    return false;
                    }
                //if (!controlSector('checkbox_08','ESTADO DEL INMUEBLE', operacion)){
                //    return false;
                //    }
                if (operacion !== 'A'){
                    if (!controlSector('checkbox_09','SERVICIOS REGIONALES', operacion)){
                        return false;
                    }
                }
                if (!controlSector('checkbox_10','SITUACION LEGAL', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_11','EQUIPAMIENTO RURAL', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_12','ARBOLADO Y CULTIVOS', operacion)){
                    return false;
                    }
                if (!controlSector('checkbox_13','DATOS ADICIONALES', operacion)){
                    return false;
                    }

                if (!validarMonedaPrecio()){
                    return false;
                }
/*          
            } else {
                return true;
            }
*/            
        }

        function controlSector(sector, sectorNombre, operacion){
            if (operacion === 'B'){
                return true;
            }
            var cantMarcados = 0;
            var className = document.getElementsByClassName(sector);
            if (className.length == 0){
                return true;
                }
            for(var index=0;index < className.length;index++){
                if (className[index].checked){
                    cantMarcados += 1;
                }
            }
            if ((cantMarcados == 0) || (cantMarcados == 1 && sector == 'checkbox_09')){
                alert("Falta informar algún item de del sector: "+sectorNombre);
                return false;
            }
            return true;
        }
        
    function validarMonedaPrecio(){
        var tipoProceso = '<?php echo $_SESSION['operacion'] ?>';
/*        
        if (tipoProceso == 'B' ){
            return habilitarSalida();
        }
*/        
        var valorPrecio = document.getElementById("valor_checkbox_02A_precio").value;
        
        if (document.getElementById("valor_checkbox_02A_gastos") !== null){
            var valorGastos = document.getElementById("valor_checkbox_02A_gastos").value;
        }

        var monedaEuro    = document.getElementById("checkbox_02A_euro");
        var monedaDolar   = document.getElementById("checkbox_02A_dolar");
        var monedaPesosAR = document.getElementById("checkbox_02A_argentino");
        var monedaPesosUY = document.getElementById("checkbox_02A_uruguay");
        var monedaRealBR  = document.getElementById("checkbox_02A_real");


        if (valorPrecio !== ""){
            if (monedaEuro.checked || monedaDolar.checked || monedaPesosAR.checked || monedaPesosUY.checked || monedaRealBR.checked){
                return habilitarSalida();
            } else {
                alert("Falta informar la moneda del precio.");
                return false;
            }
        } else {
            if (monedaEuro.checked || monedaDolar.checked || monedaPesosAR.checked || monedaPesosUY.checked || monedaRealBR.checked){
                if (valorPrecio === ""){
                    alert("Falta informar el precio de la propiedad.");
                    return false;
                }
                return habilitarSalida();
            } else {
                return habilitarSalida();
            }
        }
    }

    function sacarBusquedaLocalidad() {
        $("#enviarDatos").attr("disabled", false);
        $("#enviarDatosB").attr("disabled", false);
        document.getElementById('IngresoLocalidad').style.display = 'none';
	}

        function habilitarSalida(){
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
        $("#cod_localidad").attr("disabled", false);
        
        if (document.getElementById('buscarPorUbicacion') !== null){
            document.getElementById('buscarPorUbicacion').style.display = 'block';
        }

        if (document.getElementById('selxubicacion') !== null){
            document.getElementById('selxubicacion').style.display = 'block';
        }
        
        return true;
    }    

    $(document).on('change', 'input[name=locBuscar]', function(event) {
        var controlselxubicacion = $("#selxubicacion").is(":visible");
//        alert("Selxubicacion: "+controlselxubicacion);

        if (controlselxubicacion){
            division = document.getElementById('selxubicacion');
            padre = division.parentNode;
            padre.removeChild(division);
        } else {
            padre.appendChild(division);
        }

/*
        division = document.getElementById('selxubicacion');
        padre = division.parentNode;
        padre.removeChild(division);
*/
        var parametros = {
            "locBuscar":$(this).val(),
            "porciento":"%",
            "tipopropiedad": tipoPropiedad,
            "lineasSel": "10",
            "soloCP": "S"
        };
        $.ajax({
            url: 'buscarLocalidadyCP.php',
            method: "post",
            data: parametros,
            success: function(data) {
                $(".listaUbicCP").html(data).fadeIn('slow');
                aplicarTraducciones();
            }
        });
    })

	</script>

    <script>
        // Escucha mensajes desde el iframe
        window.addEventListener('message', function(event) {
            if (event.origin !== window.location.origin) {
                // AsegÃºrate de que el mensaje proviene del mismo origen
                return;
            }

            var data = event.data.split(';');
            console.log("DATA: "+data);
/*
            if (data[0] == 'ubic_provincia_id'){
                $.post("select_comarcas.php", { id_provincia: data[1] }, function(data1) {
				    $("#ubic_comarca_id").html(data1)});
                console.log("Mande a cargar las comarcas de "+data[1]);
            }
*/
//            alert("Recibido desde iframe: "+data[0]+' - '+data[1]);
//            $recepcion="Recibido desde iframe: "+data[0]+" - "+data[1];
//            console.log("Recibido desde iframe: "+data[0]+" - "+data[1]);
//            if (data[1] === ''){
//                console.log("El valor anterior es vacio");
//            }

            var mainSelect = document.getElementById(data[0]);
            mainSelect.value = data[1];
            console.log("DATA[0]: "+data[0]+" - DATA[1]: "+data[1]);
            console.log("Valor clocado en "+data[0]+" "+mainSelect.value);
//            var changeEvent = new Event('change');
            var changeEvent = new Event('change', { 'bubbles': true, 'cancelable': true });
            mainSelect.dispatchEvent(changeEvent);
            
//            mainSelect.addEventListener('change', function(event) { 
//            });
//            alert("Escucha iframe: "+data[0]+' - '+data[1]);
//            mainSelect.value = data[1];
//            var event = new Event('change', { 'bubbles': true, 'cancelable': true });
//            mainSelect.dispatchEvent(event);
        });
    </script>

<script language="javascript">
    function mascara(input)
    {
        var num = input.value.replace(/\./g,'');
        if(!isNaN(num)){
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        input.value = num;
    }

    else{ alert('Solo se permiten numeros');
        input.value = input.value.replace(/[^\d\.]*/g,'');
    }
    }
</script>
    
<script>
    function cambiarColorFondo(radio) {
        var tipocampo = radio.id;
        var operacion = '<?php echo $_SESSION['operacion']?>';

        if (operacion === 'B'){
            let labels = document.querySelectorAll('label[class*="'+radio.name+'"]');
            // Recorrer los labels seleccionados y hacer algo con cada uno
            labels.forEach(label => {
                label.style.backgroundColor = '';
            });
            
            var labelnombrecheck = radio.id;
            var nombrelabel = 'label_' + labelnombrecheck;
            if(radio.checked){
                document.getElementById(nombrelabel).style.backgroundColor = '#ffd700';
            } else {
                document.getElementById(nombrelabel).style.backgroundColor = '';
                }
            }
    }

</script>
    
<script> 
    function anularEnter(event) { 
        if (event.key === "Enter") { 
            event.preventDefault();
            alert ("Por favor, use la tecla <Tab> en lugar de <Enter>");
        } 
    } 
</script>

<script>
    function blanquearInput() {
        document.getElementById('elAnuncio').value = '';
    }
</script>

<style>
    elAnuncio:placeholder {
        color: white !important;
        font-size: 1.80em;
        font-style: normal;
        outline: none;
        width: auto;
    }
</style>

<style>
    h3 {
      color: red;
      font-style: italic;
      text-align: center;
    }

    #enviarDatosPrevia {
      background-image: url('imagenes/buscarPublicacion.png');
      background-size: cover;
}
</style>

<style>
    .radio-label {
      background-color: transparent; /* Color inicial del fondo */
    }
    .radio-label-seleccionado {
      background-color: #F2A45E; /* Color del fondo cuando estÃ¡ seleccionado */
    }
    
</style>

<style> 
    .iframe-container { 
        position: relative; 
        width: 100%;
        height: 800px;
        padding-bottom: 50%;
        overflow: hidden; 
/*        border: 1px solid black; */
        } 

    .iframe-container iframe { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        #border: 0;
        }
</style> 


<style>
    .custom-checkbox {
        display: none;
    }

    .custom-label {
        display: inline-block;
        width: 15px;
        height: 15px;
        background-image: url('imagenes/checkbox-sin-seleccionar.png');
        background-size: cover;
        cursor: pointer;
    }

    .custom-checkbox:checked + .custom-label {
        background-image: url('imagenes/checkbox-seleccion.png'); /* Imagen para el estado marcado */
    }
</style>

<style>
    #menuContextual {
      position: absolute;
      display: none;
      background: #f2f2f2;
      border: 1px solid #ccc;
      padding: 5px;
      z-index: 1000;
    }
    #menuContextual div {
      padding: 8px;
      cursor: pointer;
    }
    #menuContextual div:hover {
      background: #ddd;
}
</style>

<style>
    .modificarMensaje {
      width: 5px;
      height: 5px;
      background-image: url('imagenes/modificarMensaje.jpg');
      background-size: cover;
      background-position: center;
    }
body,td,th {
    font-family: Arial, sans-serif;
}
    .tituloFijar {
      position: sticky; /* o fixed */
      top: 0;
      width: 100%;
      background-color: white;
      padding: 10px;
      z-index: 100;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

</style>

<style>
    .mi-tooltip .tooltip-inner {
      border-radius: 15px;
      background-color: #f0f0f0;
      color: #333;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .mi-tooltip .tooltip-arrow::before {
      border-top-color: #f0f0f0 !important;
    }
    
    .error {
      border: 2px solid red;
      background-color: #FCD7D8;
}

</style>
    
<style>
    .servicios-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 25px; /* vertical - horizontal */
        align-items: center;
    }

    .item-servicio {
        display: flex;
        align-items: center;
        white-space: nowrap; /* evita que el texto se corte */
    }

    .item-servicio input[type="text"] {
        width: 70px;          /* ajustá a gusto: 60, 80, 100 */
        padding: 2px 4px;
        font-size: 0.85rem;   /* más compacto */
    }

    .item-servicio label {
        margin-left: 6px;     /* separa texto del checkbox */
        margin-right: 10px;   /* separa del siguiente ítem */
        font-size: 0.9rem;
    }

</style>

</head>

<body>
<!--
    <div id="loader" class="loader" style="padding-bottom: 600px;">
        <div class="spinner"></div>
    </div>
-->
    <div id="menuContextual">
      <div id="agregarComentario">Agregar comentario</div>
    </div>

    <div class="mi-formulario container-sm mt-5">
                <div class="tituloFijar row col-12">
        <?php
			if ($_SESSION['operacion'] == 'A') {?>
        		<h1 align="center" class="display-6" data-trad="Ingreso de Publicación">Ingreso de Publicación</h1>
		<?php } else {?>
        		<h1 align="center" class="display-9" data-trad="Buscar Publicaciones">Buscar Publicaciones</h1>
<!--
                <div align="center">
                    <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
                        <input class="col-1" type="text" id="elAnuncio" name="elAnuncio" placeholder="0000 0000" style="text-align: right" onfocus="blanquearInput()">
                        <input class="alert-success" type="submit" id="elAnuncio" value="Consultar por Referencia"></input>
                    </form>
                </div>
-->
                <?php } ?>
    
                <div align="left">
                <?php if ($_SESSION['operacion'] === "A"){ ?>
                    <div style="position: relative; display: inline-block;">
                        <button class="alert-success" name="queSubmito" type="submit" id="enviarDatos" form="miFormulario"
                                 value="Publicar" disabled data-trad="Publicar">
<!--
                        <span data-trad="Publicar"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                       pointer-events: none; color: black;">
                            Publicar
                        </span>
-->
                    </div>
                    <?php } else {?>
                    <div style="position: relative; display: inline-block;">
                        <button class="alert-success" name="queSubmito" type="submit" id="enviarDatos" form="miFormulario"
                                value="Buscar" disabled data-trad="Buscar">
<!--
                        <input class="alert-success" name="queSubmito" type="submit" id="enviarDatos" form="miFormulario"
                                value="Buscar" disabled style="color: transparent; text-shadow: none;" data-trad="Buscar">
                        <span data-trad="Buscar"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                       pointer-events: none; color: black;">
                            Buscar
                        </span>
-->
                    </div>
                    <?php } ?>
                    <div style="position: relative; display: inline-block;">
                        <input class="alert-success" type="button" value="Limpiar" style="color: transparent; text-shadow: none;"
                           <?php 
                            if ($_SESSION['operacion'] === 'A'){
                                echo ("onClick="."window.location.href="."'pruebaDetalles.php?operacion=A'");
                            } else {
                                if (empty($busquedaPublica)){
                                    echo ('onClick="history.go(0);">');
                                } else {
                                    echo ("onClick="."window.location.href="."'pruebaDetalles.php?operacion=B'");
                                }
                            }
                           ?>
                        </input>
                        <span data-trad="Limpiar"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                       pointer-events: none; color: black;">
                            Limpiar
                        </span>
                    </div>
                </div>
               </div>

            <form class="form form-control needs-validation" action="ejecutarSubmit.php" onSubmit="return validarFormulario()" method="POST" id="miFormulario" novalidate>
            <?php //} ?>
            <br>
<!--            <div id="tooltip" class="custom-tooltip" hiiden></div> -->
            <div class="d-flex flex-wrap flex-row">
                <div class="row col-8">
                    <p class="outer_div_01"></p>
                </div>
                <div class="row col-8">
                    <p class="outer_div_02"></p>
                </div>
                <div class="row col-10">
                    <p class="outer_div_02A"></p>
                </div>
            </div>
            <div class="d-flex flex-wrap flex-row" id="">
                <p class="outer_div_geo"></p>
                <p class="outer_div_02C"></p>
                <p class="outer_div_img"></p>
                <p class="outer_div_03"></p>
                <p class="outer_div_04"></p>
                <p class="outer_div_05"></p>
                <p class="outer_div_06"></p>
                <p class="outer_div_06A"></p>
                <p class="outer_div_07"></p>
                <p class="outer_div_08"></p>
                <div class="outer_div_09 servicios-wrap"></div>
                <p class="outer_div_10"></p>
                <p class="outer_div_11"></p>
                <p class="outer_div_12"></p>
                <p class="outer_div_13"></p>
            </div>
            <br>
        </form>
    </div>

    <div>
        <p class="outer_div_mensajes"></p>
        <p class="outer_div_resultados"></p>
    </div>

<!-- <script src="trad/appTraduccion.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
       var className = document.getElementsByClassName('checkbox_03');
       for(var index=0;index < className.length;index++){
          console.log(className[index].innerHTML);
       }
    });
</script>

<script>
    $(function () {
      $('[data-bs-toggle="tooltip"]').tooltip()
    })
</script>

<!-- <textarea id="miTextarea" oninput="validarTexto(this)"></textarea> -->
<script>
    function validarTexto(textarea) {
      textarea.value = textarea.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚ .,¡!¿?]/g, '');
    }
</script>

</body>
</html>
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";