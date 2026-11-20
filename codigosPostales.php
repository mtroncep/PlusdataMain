<!doctype html>

<?php
	session_start();

	$cod_localidad = $_REQUEST['cod_localidad'];
	$cod_descripcion = $_REQUEST['cod_descripcion'];
	$_SESSION['cod_localidad']=$cod_localidad;
	$_SESSION['cod_descripcion']=$cod_descripcion;

	$_SESSION['primeraVez'] = 1;
	$_SESSION['operacion'] = "";
	$cp = '';
    $loccp = '';
	if (isset($_REQUEST['cp'])){
		$cp=$_REQUEST['cp'];
		$loccp=$_REQUEST['cod_localidad'];
		$_SESSION['operacion'] = "M";
	}
//    echo ("CP: ".$cp." "." LOC: ".$loccp);
//    return;
?>

<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->

	<link rel="stylesheet" href="dist/css/bootstrap.css">

    <!-- <link rel="stylesheet" href="css/misEstilos.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
   	<script type="text/javascript">
		function manejo_estado_checkbok(checkbox){

            $("#confirmar").attr("disabled", false);
            
			var nombrechek  = checkbox.name;
			var long        = nombrechek.length;		
			var prefijo     = nombrechek.indexOf('_');
			var sufijo      = nombrechek.substr(prefijo);
			var nombrecampo = 'valor_checkbox' + sufijo;
            //Si está marcada ejecuta la condición verdadera.
			if(checkbox.checked){
                if (document.getElementById(nombrecampo)){
                    document.getElementById(nombrecampo).readOnly=false;
                }
			}
			//Si se ha desmarcado se ejecuta el siguiente mensaje.
			else{
                if (document.getElementById(nombrecampo)){
                    var valor = document.getElementById(nombrecampo);
                    valor.readOnly=true;
                    valor.value="";
                    valor.required=true;
                }
            }
            return true;
		}
	</script>
	
    <script type="text/javascript">
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
	}
	
	$(document).on('change', 'input[name=cp]', function(event) {
		var lngCP=$(this).val().length;
		if (lngCP == '3'){var cpF = "00" + $(this).val()};
		if (lngCP == '4'){var cpF = "0" + $(this).val()};
		if (lngCP == '5'){var cpF = $(this).val()};
		
		$("#confirmar").attr("disabled", false);

		document.getElementById('cp').value=cpF;
		$(this).text=cpF;
		
		var parametros = {
			"action": "ajax",
			"nro_en_form": "09",
			"titulo": "SERVICIOS REGIONALES",
			"propiedad": "",
			"cp": cpF,
            "loccp": "<?php echo($loccp)?>",
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
	
	$(document).on('change', 'input[name=cpnuevo]', function(event) {
		var lngCP=$(this).val().length;
		if (lngCP == '3'){var cpF = "00" + $(this).val()};
		if (lngCP == '4'){var cpF = "0" + $(this).val()};
		if (lngCP == '5'){var cpF = $(this).val()};
		
		document.getElementById('cpnuevo').value=cpF;
		$(this).text=cpF;

        $("#confirmar").attr("disabled", false);
	})
	
	<?php if ($cp <> "") { ?>
		$("#confirmar").attr("disabled", false);
		var parametros = {
			"action": "ajax",
			"nro_en_form": "09",
			"titulo": "INFRAESTRUCTURA Y SERVICIOS REGIONALES",
			"propiedad": "",
			"cp": "<?php echo($cp)?>",
            "loccp": "<?php echo($loccp)?>",
			"agrpradio":"N",
			"primeraVez": "S",
			"estado":"edit"
		};
		$.ajax({
			url: 'ajax/obtener_detalleCP.php',
			data: parametros,
			beforeSend: function(objeto) {},
			success: function(data) {
				$(".outer_div_09").html(data).fadeIn('slow');
			}
		});
	<?php } ?>
        
	function anular(e) {
          tecla = (document.all) ? e.keyCode : e.which;
          return (tecla != 13);
     }
</script>

<script type="text/javascript">
	function enableSelects(){
		$("#confirmar").attr("disabled", true);
	}	
	</script>

</head>

<body>
    <div class="container-sm mt-5">
		<div> <a href="indiceNavParticular.php"><img src="imagenes/logoClaro.png" width="150" height="75" alt="Inicio"/></a> </div>
        		<h1 align="center" class="text-verdemarcelo display-6">Actualización de Códigos Postales</h1>
        <form class="form form-control needs-validation" style="background: #B4DEE8" novalidate>
            <br>
			<div class="col-12 text-center" align="left">
				<label for="cp">Código Postal</label>
				<?php
				if (!empty($cp)){ ?>
					<input type=text name="cp" id="cp" value="<?php echo($cp)?>" readonly>
				<?php } else {?>
					<input type=text name="cp" id="cp" value="<?php echo($cp)?>" onkeypress="return anular(event)">
                    <input class="alert-success" type="button" value="Tomar C.P.">
				<?php } ?>
			</div>
            <br>
        </form>

		<form class="form form-control needs-validation" style="background: #B4DEE8" action="actualizaCP.php" method="post" onSubmit="enableSelects()" novalidate>
			<div class="col-12 text-start">
                <?php 
                if (!empty($cp)){ ?>
				    <label for="cpnuevo">Quiero cambiar el CP por:</label>
 				    <input type=text name="cpnuevo" id="cpnuevo">
                <?php } ?>
			</div>
			<br>
			   <p class="outer_div_09"></p>
			<br>
			<input class="alert-success" type="submit" value="Confirmar" id="confirmar" name="confirmar" disabled>
			<input class="alert-success" type="button" value="Volver" onClick="history.go(-1);">
		</form>
    </div>
</body>
</html>