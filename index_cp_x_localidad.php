<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ABM de ubicaciones geograficas - CP x LOCALIDAD - MySQL - jQuery AJAX </title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/custom_new.css">
</head>
<body>
     <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-5">
						<h2>Administrar <b>Códigos Postales</b></h2>
					</div>
					<div class="col-sm-1">
						<a href="indiceNavParticular.php"><img src="imagenes/logoClaro.png" width="100" height="50" alt="Inicio"/></a>
					</div>
					<div class="col-sm-6">
						<?php $cod_localidad=$_GET['cod_localidad'];
						      $cod_descripcion=$_GET['cod_descripcion'];
						?>
						<a href="codigosPostales.php?cp=&cod_localidad=<?php echo($cod_localidad)?>&cod_descripcion=<?php echo($cod_descripcion)?>"  
						   class="btn btn-success" data-toggle="modal">
						<i class="material-icons">&#xE147;</i> <span>Agregar Código Postal</span></a>
					</div>
                </div>
            </div>
			
			<hr>

			<div class='col-sm-6 pull-left'>
				<div class='col-lg-4' align="left">
					<div align="left"><h4><?php echo($cod_descripcion)?></h4></div>
				</div>
			</div>
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
			<div class='clearfix'></div>
			<hr>
			<div id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'></div><!-- Carga de datos ajax aqui -->
        </div>
    </div>
	<!-- Edit Modal HTML -->
	<?php include("html/modal_add_comarca.php");?>
	<!-- Edit Modal HTML -->
	<?php include("html/modal_edit_comarca.php");?>
	<!-- Delete Modal HTML -->
	<?php include("html/modal_delete_comarca.php");?>
	
	<?php
		$cod_localidad=$_GET['cod_localidad'];
		$cod_descripcion=$_GET['cod_descripcion'];
	?>
	<script id="par_cp" src="js/script_cp.js?cod_localidad=<?php echo($cod_localidad)?>&cod_descripcion=<?php echo($cod_descripcion)?>"></script>
</body>
</html>