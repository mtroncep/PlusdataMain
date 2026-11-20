<!doctype html>
<?php
	session_start();
	if (isset($_SESSION['seleccion'])){
		$seleccion = $_SESSION['seleccion'];
	}
//	print_r($seleccion);
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
	<h1 align="center" class="text-verdemarcelo display-6">Resultado de la Selección</h1>

	<div class="container-sm mt-5">
		<form class="form form-control needs-validation" action="pruebaDetalles.php?operacion=B" method="post" novalidate>

		<div class="table-wrapper-scroll-y my-custom-scrollbar">
			<table class="table table-bordered table-striped mb-0 border-light">
			<thead>
			  <tr>
				<th scope="col">Ver</th>
				<th scope="col">Anuncio</th>
				<th scope="col">Detalles</th>
			  </tr>
			</thead>
			<tbod>
	<?php
	$tope = count($seleccion);
	if (!empty($seleccion[0])){
		for ($i = 0; $i < $tope; ++$i){
			$anuncio=substr($seleccion[$i], 0, strpos($seleccion[$i], ","));
			$contenido=substr($seleccion[$i], strpos($seleccion[$i], ",") + 1);
			echo('<tr>');
			echo ('<td><a href="pruebaActualizarDetalles.php?anuncio='.$anuncio.'&operacion=C"><img src="imagenes/ojo.png" width="20" height="20" alt="Consultar anuncio"/></a></td>');
			echo ('<td align="center">'.$anuncio.'</td>');
			echo ('<td><div>'.$contenido.'</div></td>');
			echo('</tr>');
		}		
	} else {
		echo "SIN RESULTADOS";
	}
	?>
			</tbod>
		  </table>
		</div>
		<input type="submit" value="Nueva consulta"</input>
		</form>
	</div>
<!--</div>-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>