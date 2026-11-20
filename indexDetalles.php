<?php
	session_start();
	unset($_SESSION['anuncio']);
	unset($_SESSION['datos']);
	unset($_SESSION['operacionANU']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administración de Detalles de Formularios</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/custom_new.css">

<style>
	.table-title {        
		padding-bottom: 15px;
		background: #C1994F;
		color: #fff;
		padding: 16px 30px;
		margin: -20px -25px 10px;
		border-radius: 3px 3px 0 0;
    }
body,td,th {
    font-family: "Varela Round", sans-serif;
}
</style>
</head>
<body>
<div class="container-fluid">
        <div class="table-wrapper">
			<div class="table-title">
        		<div class="row">
                    <div class="col-sm-5">
						<h2>Administrar <b>Detalles de Formularios</b></h2>
					</div>
					<div class="col-sm-1">
						<a href="indiceNavParticular.php"><img src="imagenes/logoClaro.png" width="100" height="50" alt="Inicio"/></a>
					</div>
					<div class="col-sm-6">
						<a href="ingresarDetalle.php" class="btn btn-success"><i class="material-icons">&#xE147;</i> 
						   <span>Ingresar Nuevo Detalle</span></a>
					</div>
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
	
 		<?php //include("html/modal_edit_detalle.php");?>

</div>
	<script src="js/scriptDetalles.js"></script>
</body>
</html>