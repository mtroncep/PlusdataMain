<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Webdisenia .:. Ejemplo Bootstrap</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<style>
	nav.navbar {
    	background-color: #A9EBF3;
	}
	a.nav-link{
		font-size: 150%;
		text-shadow: #555 2px 2px 4px;
		font-color: #0D3E03;
		font-style: italic;
		font: bold;
	}

	a.dropdown-item{
		font-size: 100%;
		text-shadow: #555 2px 2px 4px;
		font-color: #0D3E03;
		font-style: italic;
		font: bold;
	}
	</style>
</head>
<body>
<img src="imagenes/bannerAlicante.jpg" alt="" width=100%/>

<nav class="navbar navbar-expand-lg navbar-light navbar-default">
  <div class="container-fluid">
  <a class="navbar-brand" href="#"><img class="navbar-btn navbar-link navbar-nav" src="imagenes/logoClaro.png" width="90" height="45" alt=""/></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
	  <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link">Mi Cuenta Personal - </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Anuncios
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="pruebaDetalles.php?operacion=B">Búsqueda</a></li>
            <li><a class="dropdown-item" href="indexAnuncios.php">Administración</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index_paises.php">Ubicaciones/Códigos Postales</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="indexFichas.php">Fichas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="salir.php">Salir</a>
      </li>
    </ul>
  </div>
  </nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>