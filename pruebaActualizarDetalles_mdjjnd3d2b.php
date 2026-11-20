<!doctype html>
<?php
	session_start();

	require_once("conexion.php");
	require_once("cargarDatosFormulario.php");

	$_SESSION['operacion']=$_REQUEST['operacion'];
	$_SESSION['anuncio']=$_REQUEST['anuncio'];
	$_SESSION['datos'] = Funciones::cargarDatosAnuncio($_SESSION['anuncio'], $con);
?>
<html lang="es">

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" href="css/fuentes.css">
	
    <link rel="stylesheet" href="css/homeNew.css" type="text/css">
    <script src="trad/traducciones.php"></script>
    <script src="trad/traductor.js"></script>

    <script type="text/javascript">
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

        var parametros = {
            "action": "ajax",
            "nro_en_form": "01",
            "titulo": "TIPO DE OPERACIÓN",
			"estado": "disabled",
			"tiposelector": "radio"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_01").html(data).fadeIn('slow');
//                aplicarTraducciones();
            }
        })

		VentaAlquiler = "<?php echo (Funciones::obtenerVentaAlquiler($_SESSION['datos']))?>";
		
		var parametros = {
            "action": "ajax",
            "nro_en_form": "02A",
//            "titulo": "PRECIO Y MONEDA",
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
//                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "02",
//            "titulo": "TIPO DE INMUEBLE",
            "titulo": "",
            "ventaalquiler": VentaAlquiler,
			"tiposelector": "radio",
			"estado": "disabled",
            "agrpradio": "S"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_02").html(data).fadeIn('slow');
//                aplicarTraducciones();
            }
        })

		tipoPropiedad = "<?php echo (Funciones::obtenerTipoPropiedad($_SESSION['datos']))?>";	
		
		var parametros = {
            "action": "ajax",
            "nro_en_form": "02C",
            "titulo": "",
//            "titulo": "UBICACIÓN GEOGRÁFICA Y MEDIDAS",
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
//                aplicarTraducciones();
            }
        })

		var parametros = {
            "action": "ajax",
            "nro_en_form": "geo",
            "tipopropiedad": tipoPropiedad,
            "titulo": ""
//            "agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_ubicacion.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_geo").html(data).fadeIn('slow');
//                aplicarTraducciones();
            }
        })

		var parametros = {
            "action": "ajax",
            "nro_en_form": "img",
            "titulo": "IMAGENES"
//            "agrpradio":"N"
        };
        $.ajax({
            url: 'mostrarPrimeraImagen.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_img").html(data).fadeIn('slow');
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "06A",
            "titulo": "ORIENTACION – CERCANIAS",
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
            }
        })

        var parametros = {
            "action": "ajax",
            "nro_en_form": "09",
            "titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
            "propiedad": tipoPropiedad,
            "ventaalquiler": VentaAlquiler,
            "estado": "readonly",
			"agrpradio":"N"
        };
        $.ajax({
            url: 'ajax/obtener_detalle.php',
            async: false,
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_09").html(data).fadeIn('slow');
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
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
//                aplicarTraducciones();
            }
        })
        
        $.ajax({
            url: 'ajax/obtener_mensajes.php',
  //          async: false,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_mensajes").html(data).fadeIn('slow');
            }
        })

        aplicarTraducciones();
        document.documentElement.setAttribute("data-loading", "false");
	}

	$(document).on('change', 'select[name=ubic_localidad_id]', function(event) {
            var texto = $('select[name="ubic_localidad_id"] option:selected').text();
            alert(texto);
            document.getElementById('IngresoLocalidad').style.display = 'block';

            var parametros = {
                "locBuscar":texto,
                "tipopropiedad": tipoPropiedad,
                "soloCP": "S"
            };
            $.ajax({
                url: 'buscarLocalidadyCP.php',
                data: parametros,
                success: function(data) {
                    $(".listaCP").html(data).fadeIn('slow');
                }
            });

	    })

    $(document).ready(function(){
    $(document).on('change', 'select[name=selCP]', function () {
        $("#enviarDatos").attr("disabled", false);
        $("#selCP option:selected").each(function (){

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
            
        }
    });
        });
    });
});

    function aplicarTraducciones() {
      document.querySelectorAll('[data-trad]').forEach(el => {
        const clave = el.getAttribute('data-trad');
        if (window.TRADUCCIONES?.[clave]) {
          el.innerText = window.TRADUCCIONES[clave];
        }
      });
    }

$(document).on('change', 'input[name=locBuscar]', function(event) {

    division = document.getElementById('selxubicacion');
    padre = division.parentNode;
    padre.removeChild(division);


    var parametros = {
        "locBuscar":$(this).val(),
        "porciento":"%",
        "tipopropiedad": tipoPropiedad,
        "soloCP": "S"
    };
    $.ajax({
        url: 'buscarLocalidadyCP.php',
        method: "post",
        data: parametros,
        success: function(data) {
            $(".listaUbicCP").html(data).fadeIn('slow');
        }
    });
})

    $(document).on('change', 'input[id=checkbox_05_70]', function(event) {
        var check = document.getElementById('checkbox_05_70').checked;

        var check3 = null;
        if (document.getElementById('checkbox_05_71') !== null){
            var check3 = document.getElementById('checkbox_05_71').checked;
        }
        
        var check4 = null;
        if (document.getElementById('checkbox_05_72') !== null){
            var check4 = document.getElementById('checkbox_05_72').checked;
        }

        if (check){
            document.getElementById('checkbox_05_73').checked=true;
            document.getElementById('valor_checkbox_05_73').readOnly=false;
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_05_71]', function(event) {
        var check = document.getElementById('checkbox_05_71').checked;

        var check3 = null;
        if (document.getElementById('checkbox_05_70') !== null){
            var check3 = document.getElementById('checkbox_05_70').checked;
        }

        var check4 = null;
        if (document.getElementById('checkbox_05_72') !== null){
            var check4 = document.getElementById('checkbox_05_72').checked;
        }

        if (check){
            document.getElementById('checkbox_05_73').checked=true;
            document.getElementById('valor_checkbox_05_73').readOnly=false;
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
    })

    $(document).on('change', 'input[id=checkbox_05_72]', function(event) {
        var check = document.getElementById('checkbox_05_72').checked;

        var check3 = null;
        if (document.getElementById('checkbox_05_70') !== null){
            var check3 = document.getElementById('checkbox_05_70').checked;
        }

        var check4 = null;
        if (document.getElementById('checkbox_05_71') !== null){
            var check4 = document.getElementById('checkbox_05_71').checked;
        }

        if (check){
            document.getElementById('checkbox_05_73').checked=true;
            document.getElementById('valor_checkbox_05_73').readOnly=false;
        } else {
            if (!check3 && !check4){
                document.getElementById('checkbox_05_73').checked=false;
                document.getElementById('valor_checkbox_05_73').readOnly=true;
                document.getElementById('valor_checkbox_05_73').value="";
            }
        }
    })

$(document).ready(function(){
    $(document).on('change', 'select[name=selCP]', function () {
        $("#enviarDatos").attr("disabled", false);
        $("#enviarDatosB").attr("disabled", false);
        $("#selCP option:selected").each(function () {

        var CP_LOC = $(this).val().split('+');
        var CP = CP_LOC[0];
        var CPLOC = CP_LOC[1];
        if (CPLOC !== ""){
            var parametros = {
            "action": "ajax",
            "nro_en_form": "09",
            "titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
            "propiedad": "",
            "cp": CP,
            "loccp": CPLOC,
            "agrpradio":"N",
            "estado":"readonly",
            "primeraVez": "S",
            "tipoBusqueda":"L"
            }
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
            var LOCCP  = LOC[2];
            var parametros = {
                "locBuscar": LOCTXT,
                "locBuscarCP": LOCCP,
                "porciento":"",
                "tipopropiedad": tipoPropiedad,
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
            var parametros = {
            "action": "ajax",
            "nro_en_form": "09",
            "titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
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
                }
            });
        }
        });
    });
});

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
			$("#locBuscar").attr("disabled", true);
			$("#selCP").attr("disabled", true);
		}	
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
        }
    
        function manejo_estado_checkbok(checkbox){
            var aux = checkboxExcluyentes(checkbox);
			var tipocampo   = checkbox.id;

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
    </script>
    
    <script type="text/javascript">
		function manejo_cpo_numerico(campo){
            var nombrecponumerico = campo.name;
//            alert(nombrecponumerico);
//            let myNumericInput = new AutoNumeric(campo,{decimalPlaces: 3});
            alert("Valor campo: "+campo.value);
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
            if (!controlSector('checkbox_04','AREA COCINA Y BAÑOS', operacion)){
                return false;
                }
            /*
            if (!controlSector('checkbox_05','OTRAS DEPENDENCIAS', operacion)){
                return false;
                }
                */
            if (!controlSector('checkbox_06','DETALLES IMPORTANTES', operacion)){
                return false;
                }
            /*
            if (!controlSector('checkbox_06A','ORIENTACIÓN – CERCANÍAS', operacion)){
                return false;
                }
                */
            if (!controlSector('checkbox_07','SERVICIOS DISPONIBLES', operacion)){
                return false;
                }
            if (!controlSector('checkbox_08','ESTADO DEL INMUEBLE', operacion)){
                return false;
                }
            if (!controlSector('checkbox_09','SERVICIOS REGIONALES', operacion)){
                return false;
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
            if (operacion == 'B'){
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
//                alert(className[index].value+" "+className[index].checked+" "+cantMarcados);
            }
            if (cantMarcados == 0 || (cantMarcados == 1 && sector == 'checkbox_09')){
                alert("Falta informar algún item de del sector: "+sectorNombre);
                return false;
            }
            return true;
        }

        function validarMonedaPrecio(){
            var tipoProceso = '<?php echo $_SESSION['operacion'] ?>';

            if (tipoProceso == 'B' ){
                return habilitarSalida();
            }

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
            }
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


    </script>
    
    <script type="text/javascript">
        function checkboxExcluyentes(event) {
            document.getElementsByName(event.name).forEach((item) =>
            { if (item.id != event.id) item.checked = false });
        }
    </script>


	
    <script type="text/javascript">
    $(document).ready(function() {
        $("form").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
    });
    </script>

    <script type="text/javascript">
        function retorno() {
            window.location.href="indexAnuncios.php";
        }
    </script>
    
<style>
    .modificarMensaje {
      width: 15px;
      height: 15px;
      background-image: url('imagenes/modificarMensaje.jpg');
      background-size: cover;
      background-position: center;
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
    
</style>

</head>

<body>
    <script>
        document.documentElement.setAttribute("data-loading", "true");
    </script>
    
    <div id="loader" class="loader" style="padding-bottom: 600px;">
        <div class="spinner"></div>
    </div>

    <div id='contenido'>
        <div id="menuContextual">
          <div id="agregarComentario">Agregar comentario</div>
        </div>

        <div class="container-sm mt-5">
              <div class="tituloFijar row col-12">
                <h1 align="center" class="display-9" data-trad="Actualizar Publicación">Actualizar Publicación</h1>
                <h5 align="left"> Anuncio: <?php echo($_SESSION['anuncio'])?></h5>
                <div class="col">
                    <div style="position: relative; display: inline-block;">
                        <input class="alert-success" name="queSubmito" type="submit" id="enviarDatos" form="miFormulario"
                                 value="Publicar" style="color: transparent; text-shadow: none;">
                        <span data-trad="Publicar"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                       pointer-events: none; color: black;">
                            Publicar
                        </span>
                    </div>
                    <div style="position: relative; display: inline-block;">
                        <input class="alert-success" type="button" value="Volver" style="color: transparent; text-shadow: none;" onclick="retorno();">
                        </input>
                        <span data-trad="Volver"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                       pointer-events: none; color: black;">
                            Volver
                        </span>
                    </div>
                </div>
              </div>
                <form class="form form-control needs-validation" style="background: #FFFFFF" onSubmit="return validarFormulario()" action="ejecutarSubmit.php" method="post" id="miFormulario" novalidate>
              <div>
                <br>
                <p class="outer_div_01"></p>
                <p class="outer_div_02"></p>
                <p class="outer_div_02A"></p>
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
                <p class="outer_div_09"></p>
                <p class="outer_div_10"></p>
                <p class="outer_div_11"></p>
                <p class="outer_div_12"></p>
                <p class="outer_div_13"></p>
                <br>
            </form>
              </div>
        </div>

        <div>
            <p class="outer_div_mensajes"></p>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!--
        <script src="./dist/js/bootstrap.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript">
          $(document).on('change', 'input[name=radio_010]', function(event) {
            $('input[type=checkbox]').live('click', function(){
            var parent = $(this).parent().attr('id');
            alert(parent);
            $('#'+parent+' input[type=checkbox]').removeAttr('checked');
            $(this).attr('checked', 'checked');
            });
        }); 
        </script>
    -->    
        <script>
            $(function () {
              $('[data-bs-toggle="tooltip"]').tooltip()
            })
        </script>

    <script>
        function validarTexto(textarea) {
          textarea.value = textarea.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚ .,¡!¿?]/g, '');
        }
    </script>
</div>
</body>
</html>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>