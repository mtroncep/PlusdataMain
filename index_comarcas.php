<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ABM de ubicaciones geograficas - COMARCAS - MySQL - jQuery AJAX </title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/custom_new.css">
</head>
<body>
     <a href="<?php echo ($_SESSION['menuinicio'])?>"><img src="imagenes/logoPlusdataSinFondo.png" width=10% title="Pagina inicio"/></a>

     <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
					<div class="col-sm-1 text-left">
						<a href="index_regiones.php?pais=<?php echo $_REQUEST['pais']?>&region=<?php echo $_REQUEST['region']?>"><img src="imagenes/flecha-izquierda.png" width="50" height="50" alt="Paises"/></a>
					</div>
                    <div class="col-sm-3">
						<h2>Administrar <b>Comarcas</b></h2>
					</div>
					<div class="col-sm-1 text-left"> 
						<a href="index_paises.php"><img src="imagenes/home.png" width="55" height="50" alt="Inicio"/></a>
					</div>
<!--
					<div class="col-sm-1">
						<a href="indiceNavParticular.php"><img src="imagenes/logoClaro.png" width="100" height="50" alt="Inicio"/></a>
					</div> -->
					<div class="col-sm-7">
						<?php $pais=$_REQUEST['pais'];
						      $region=$_REQUEST['region'];
						      $provincia=$_REQUEST['provincia'];
						?>
						<a href="#addComarcaModal" data-add_pais="<?php echo $pais;?>"  data-add_region="<?php echo $region;?>" 
						   data-add_provincia="<?php echo $provincia;?>" class="btn btn-success" data-toggle="modal">
						<i class="material-icons">&#xE147;</i> <span>Agregar nueva comarca</span></a>
					</div>
                </div>
            </div>
			
			<hr>
			<div class='col-sm-4 fa-pull-left'>
				<div id="ubicacion">
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
		$pais=$_REQUEST['pais'];
		$region=$_REQUEST['region'];
		$provincia=$_REQUEST['provincia'];
	?>
	<script id="par_pais" src="js/script_comarcas.js?pais=<?php echo $pais ?>&region=<?php echo $region?>
			&provincia=<?php echo $provincia?>"></script>
	
</body>
</html>