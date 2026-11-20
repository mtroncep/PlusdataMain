<?php
	session_start();
	$yaIngresado=0;
	if (isset($_SESSION['anunciante'])){
		$_SESSION['yaingresado']=1;
		echo ('<script type="text/javascript">'."window.location.href = 'indiceNav.php'</script>");
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Home - Hipermercado Inmobiliario</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<!--  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" /> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!--  <link rel="stylesheet" href="https://www.markuptag.com/bootstrap/5/css/bootstrap.min.css" /> -->
	
<style>
	nav.navbar {
    	background-color: #A9EBF3;
	}

</style>
</head>
<body>
	<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Inicio</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form method="POST" action="ingreso.php">
						<div class="mb-3">
							<label class="form-label">E-Mail</label>
							<input type="text" class="form-control" id="emailLog" name="emailLog" placeholder="E-Mail" />
						</div>
						<div class="mb-3">
							<label class="form-label">Clave</label>
							<input type="password" class="form-control" id="password" name="pwdLog" placeholder="Clave de ingreso" />
						</div>
						<div class="modal-footer d-block">
							<button type="submit" class="btn btn-warning float-end">Ingresar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<img src="imagenes/bannerAlicante.jpg" alt="" width=100%/>
	<nav class="mobile-offcanvas navbar navbar-expand-lg navbar-light navbar-toggler">
		  <div class="container-fluid">
			  <a class="navbar-brand" href="indiceNavInicio.php"><img class="navbar-btn navbar-link navbar-nav" src="imagenes/logoClaro.png" width="90" height="45" alt=""/></a>
			  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarId" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navBarId">
			   <ul class="navbar-nav">
				  <li class="nav-item">
						<a class="nav-link" href="#loginModal" data-bs-toggle="modal"  class="btn btn-success" data-toggle="modal" data-target="#loginModal">Mi Cuenta</a>
				  <li class="nav-item">
						<a class="nav-link" href="#">Registrarme</a>
				  </li>
				  <li class="nav-item">
						<a class="nav-link" href="#">Contacto</a>
				  </li>
				  <li class="nav-item">
						<a class="nav-link" href="#">Publicar</a>
				  </li>
				  <li class="nav-item">
						<a class="nav-link" href="#">Buscar</a>
				  </li>
			   </ul>
			  </div>
		</div>
	</nav>
	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!--	<script src="https://www.markuptag.com/bootstrap/5/js/bootstrap.bundle.min.js"></script>   -->
</body>
</html>