<?php
	session_start();
	$yaIngresado=0;
	if (isset($_SESSION['anunciante'])){
		$_SESSION['yaingresado']=1;
		echo ('<script type="text/javascript">'.
			  "window.location.href = 'indexNav.php'</script>");
	}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.83.1">
    <title>Signin Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    

    <!-- Bootstrap core CSS -->
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>
  <body class="text-center" background="imagenes/fondoMarbella.jpg">
  <div class="container">
	  <div class="row text-center">
	      <div class="col-12">
			  <a href="busquedaPublica.php">Busqueda de Propiedades</a>
		  </div>
	      <div class="col-3">
			  <form method="post" action="ingreso.php">
				<h1 class="mb-4 fw-normal text-light">Ingreso</h1>

				<div class="form-floating">
				  <input type="email" class="form-control my-1" id="floatingInput" placeholder="name@example.com" name="emailLog" required>
				  <label for="floatingInput">E-Mail</label>
				</div>
				<div class="form-floating">
				  <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pwdLog" required>
				  <label for="floatingPassword">Clave</label>
				</div>

				<div class="checkbox mb-3">
				  <label>
					<input type="checkbox" value="remember-me"> Recordame
				  </label>
				</div>
				<button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
				<p class="mt-5 mb-3 text-muted">&copy; 2021</p>
			  </form>
		  </div>
	      <div class="col-2">
				<img class="mb-4" src="imagenes/logoClaro.png" alt="" width="150" height="75">
		  </div>
	      <div class="col-3">
			  <form method="post" action="registro.php">
				<h1 class="mb-4 fw-normal text-light">Registro</h1>

				<div class="form-floating">
				  <input type="email" class="form-control my-1" id="floatingInputReg" placeholder="name@example.com" name="emailReg" required>
				  <label for="floatingInputReg">E-Mail</label>
				</div>
				<div class="form-floating">
				  <input type="password" class="form-control" id="floatingPasswordReg" placeholder="Password" name="pwdReg" required>
				  <label for="floatingPassword">Clave</label>
				</div>

				<div class="form-floating">
				  <input type="password" class="form-control my-1" id="floatingPasswordRegReing" placeholder="Reingrese Password" name="pwdRegReing" required>
				  <label for="floatingPasswordReing">Reingresar Clave</label>
				</div>

				<div class="checkbox mb-3">
				  <label>
					<input type="checkbox" value="remember-me"> Recordame
				  </label>
				</div>
				<button class="w-100 btn btn-lg btn-primary" type="submit">Registrarse</button>
				<p class="mt-5 mb-3 text-muted">&copy; 2021</p>
			  </form>
		  </div>
	  </div>
</div>
</body>
</html>
