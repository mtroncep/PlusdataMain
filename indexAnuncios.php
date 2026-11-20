<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
	unset($_SESSION['anuncio']);
	unset($_SESSION['datos']);
	unset($_SESSION['operacionANU']);

    require_once ("conexion.php");
	require_once ("cargarDatosFormulario.php");
?>
<script src="trad/traducciones.php?lang=<?= $_SESSION['idioma'] ?>"></script>
<script src="trad/traductor.js"></script>
<?php
    if (!isset($_SESSION['codPais'])){
        echo '<script type="text/javascript">alert(traducir("Falta seleccionar el país"))</script>';
        $_SESSION['anuncioSelSinPais'] = "indexAnuncios.php";
        return;
    }

    if (!Funciones::obtenerEstadoSucursal($con,$_SESSION['anunciante']) ){
        echo ("<script>alert('NO SE ENCUENTRA AUTORIZADO PARA ADMINISTRAR ANUNCIOS. POR FAVOR, COMUNIQUESE CON OFICINA CENTRAL');</script>");
        return;
    }

?>
<!DOCTYPE html>
<html lang="es"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administración de Busquedas Gurdadas</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/custom_new.css">
<link rel="stylesheet" href="css/fuentes.css">

<script src="trad/traducciones.php?lang=<?= $_SESSION['idioma'] ?>"></script>
<script src="trad/traductor.js"></script>

<style>
	.table-title {
		padding-bottom: 15px;
		background: #C1994F;
		color: #fff;
		padding: 16px 60px;
		margin: -20px -25px 10px;
		border-radius: 3px 3px 0 0;
    }

    .datos {
        color: aquamarine !important;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="table-wrapper">
			<div class="table-title">
        		<div class="row">
                    <div class="col-sm-5 col-3">
						<h2><b><span data-trad="Administrar Anuncios Publicados">Administrar Anuncios Publicados</b></h2></span>
					</div>
					<div class="col-sm-6 col-3">
						<a href="cargarDetallesAwait.php?operacion=A" class="btn btn-warning"><i class="material-icons">&#xE147;</i> 
						   <span data-trad="Publicar Anuncio">Publicar Anuncio</span></a>
					</div>
                </div>
            </div>
			<div class='col-sm-4 pull-right'>
				<div id="custom-search-input">
                            <div class="input-group col-9">
                                <input data-trad="Número de anuncio" type="text" class="form-control" placeholder="Número de anuncio" id="q" onkeyup="load(1);" />
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
			<div class="datos" id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'></div><!-- Carga de datos ajax aqui -->
        </div>
	
		<?php include("html/modal_delete_anuncio.php");?>

    </div>

    <script src="trad/traducciones.php?lang=<?= $_SESSION['idioma'] ?>"></script>
    <script src="trad/traductor.js"></script>

	<script src="js/scriptAnuncios.js"></script>

</body>
</html>