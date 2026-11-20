<!doctype html>
<?php
session_start();
unset($_SESSION['cp']);
unset($_SESSION['datos']);
unset($_SESSION['operacionCP']);

$hayRequest = false;
if (isset($_REQUEST['cod_localidad'])){
	
	$hayRequest = true;
	
	$cod_localidad=$_REQUEST['cod_localidad'];
	$cod_descripcion=$_REQUEST['cod_descripcion'];

	$_SESSION['cod_descripcionCP'] = $_REQUEST['cod_descripcion'];
	$_SESSION['paisCP'] = $_REQUEST['pais'];
	$_SESSION['regionCP']=$_REQUEST['region'];
	$_SESSION['municipioCP']=$_REQUEST['municipio'];
	$_SESSION['comarcaCP']=$_REQUEST['comarca'];
	$_SESSION['provinciaCP']=$_REQUEST['provincia'];
}
?>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ABM de ubicaciones geograficas - PAISES - MySQL - jQuery AJAX </title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/custom_new.css">
	
<script type="text/javascript">
    $(document).on('change', 'input[name=localidad]', function(event) {
//		alert("Localidad a buscar: "+$(this).val());
//		window.location.href = 'armarLocalidadIndexCP.php?locBuscar='+$(this).val();
        
        var parametros = {
            "locBuscar": $(this).val()
        };
        $.ajax({
            url: 'armarLocalidadIndexCP.php',
            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".selLocalidad").html(data).fadeIn('slow');
            }
        })
        
	})	
</script>

<style>
	.table-title {        
		padding-bottom: 15px;
		background: #6FA5A6;
		color: #000000;
		padding: 16px 30px;
		margin: -20px -25px 10px;
		border-radius: 3px 3px 0 0;
    }
</style>
</head>
<body>
<div class="container">
        <div class="table-wrapper">
			<div class="table-title">
        		<div class="row">
                    <div class="col-sm-4">
						<h2>Administrar <b>Códigos Postales</b></h2>
					</div>
                    <div class="col-sm-1">
                        <a href="javascript: history.go(-1)"><img src="imagenes/flecha-izquierda.png" width="50" height="50" title="Pagina anterior"/></a>
                    </div>
<!--
					<div class="col-sm-1">
						<a href="<?php echo ($_SESSION['menuinicio'])?>"><img src="imagenes/logoClaro.png" width="100" height="50" title="Pagina inicio"/></a>
					</div>
-->                    
					<div class="col-sm-1">
					</div>
					<?php if ($hayRequest){ ?>
						<div class="col-sm-1">
							<a href="index_ciudades.php?
									 pais=<?php echo $_REQUEST['pais']?>
									 &region=<?php echo $_REQUEST['region']?>
									 &municipio=<?php echo $_REQUEST['municipio']?>
									 &comarca=<?php echo $_REQUEST['comarca']?>
									 &provincia=<?php echo $_REQUEST['provincia']?>" ><img src="imagenes/tierra.png" width="50" height="50"  title="UbicGeo/CP"/></a>
						</div>

                        <div class="col-sm-4">
							<a href="codigosPostales.php?
										pais=<?php echo $_REQUEST['pais']?>
										&region=<?php echo $_REQUEST['region']?>
										&municipio=<?php echo $_REQUEST['municipio']?>
										&comarca=<?php echo $_REQUEST['comarca']?>
										&provincia=<?php echo $_REQUEST['provincia']?>
										&cod_localidad=<?php echo $cod_localidad?>
										&cod_descripcion=<?php echo $cod_descripcion?>" 
							   class="btn btn-success"><i class="material-icons">&#xE147;</i> 
							   <span>Agregar Nuevo Código Postal</span></a>
						</div>
					<?php } else { ?>
						<div class="col-sm-4">
							<label for="localidad">Localidad o parte del nombre, o CP</label>
                            <div class="selLocalidad">
							    <input type="text" id="localidad" name="localidad"></input>
                            </div>
					    </div>
					<?php } ?>
                </div>
            </div>
			<div class='col-sm-6 text-left'> 
				<h3><?php if (!empty($cod_descripcion)){
					echo($cod_descripcion); } ?></h3>
			</div>
    <!--
			<div class='col-sm-4 pull-right'>
				<div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="text" class="form-control" placeholder="Buscar"  id="q" onkeyup="load(1);" />
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="button" onclick="load(1);">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                </div>
			</div>
    -->
			<div class='clearfix'></div>
			<hr>
			<div id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'></div><!-- Carga de datos ajax aqui -->
        </div>
    </div>

	<?php include("html/modal_delete_cp.php");?>

	<?php if ($hayRequest){ ?>
		<script id="par_pais" src="js/scriptCP.js?cod_localidad=<?php echo $cod_localidad ?>&cod_descripcion=<?php echo $cod_descripcion?>"></script>
	<?php } ?>
<!--    
    <script>
        $(document).ready(function () {
          $('.navegacion').load('<?php echo $_SESSION['menuinicio'] ?>');
        });
   </script>
-->
</body>
</html>