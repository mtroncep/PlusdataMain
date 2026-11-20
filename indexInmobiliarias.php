<?php
	session_start();
	unset($_SESSION['anuncio']);
	unset($_SESSION['datos']);
	unset($_SESSION['operacionANU']);
    if (!isset($_SESSION['codPais'])){
        echo ("<script>alert('Falta seleccionar el país.');</script>");
//        echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
        return;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administración de Inmobiliarias</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="ajax/css/custom_new.css">
<link rel="stylesheet" href="ajax/css/fuentes.css">

<style>
	.table-title {        
		padding-bottom: 15px;
		background: #C1994F;
		color: #fff;
		padding: 16px 60px;
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
                    <div class="col-sm-5 col-3">
						<h2>Administrar <b>Inmobiliarias</b></h2>
					</div>
                </div>
            </div>
			<div class='col-sm-4 pull-right'>
				<div id="custom-search-input">
                            <div class="input-group col-9">
                                <input type="text" class="form-control" placeholder="Buscar País" id="q" onkeyup="load(1);" />
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
	
		<?php include("html/modal_delete_inmobiliaria.php");?>

    </div>
	<script src="js/scriptInmobiliarias.js"></script>
</body>
</html>