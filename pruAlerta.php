<!doctype html>
<?php
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

session_start();
if (isset($_SESSION['seleccion'])){
	$seleccion = $_SESSION['seleccion'];
}
$locBuscar = $_REQUEST['locBuscar'];
?>

<html>
<head>
	<meta charset="utf-8">
	<title>Documento sin título</title>
	<meta charset="utf-8">
	<title>Consulta de Publicación</title>
   	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" href="dist/css/bootstrap.css">

	<style>
		.my-custom-scrollbar {
			position: relative;
			height: 300px;
			width:  530px;
			overflow: auto;
			background: #C7F4D4;
		}
		.table-wrapper-scroll-y {
			display: block;
		}
	</style>

</head>
<body>
	<br>
	<p align="center" class="text-verdemarcelo display-6">Lista de Códigos Postales por Localidad Encontrados</p>

	<div class="container-sm mt-5" align="center">
		<form class="form form-control needs-validation" action="pruebaDetalles.php?operacion=A" method="post" novalidate>

		<div class="table-wrapper-scroll-y my-custom-scrollbar">
			<table class="table table-striped">
				<thead>
				<tr align="center">
					<th scope="col">Provincia</th>
					<th scope="col">Localidad</th>
					<th scope="col">C.P.</th>
					<th scope="col">Seleccionar</th>
				</tr>
				</thead>
				<tbod>
				<?php
				$camino [][] = '';
				$camino = Funciones::obtenerCaminoLocalidad($locBuscar, $con);	
				$tope = count($camino);
				if ($camino){
					for ($i = 0; $i < $tope; ++$i){
						echo ('<tr>');
						echo ('<td><div align="center">'.$camino[$i]['PRO_DESCRIP'].'</td>');
						echo ('<td><div align="center">'.$camino[$i]['LOC_DESCRIP'].'</div></td>');
						echo ('<td><div align="center">'.$camino[$i]['CP'].'</div></td>');
						echo ('<td align="left"><a href="#"><img src="imagenes/apuntando-a-la-izquierda.png" width="30" height="30" alt="Consultar anuncio"/></a></td>');
						echo ('</tr>');
					}		
				} else {
					echo "SIN RESULTADOS";
				}
				?>
			 </tbod>
		  </table>
		</div>
		<input type="submit" value="Nueva consulta"></input>
		</form>
	</div>
<!--</div>-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>

