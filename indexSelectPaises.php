<?php
	session_start();
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Select Personalizado</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap" rel="stylesheet"> 
	<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

	<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
	
	<div class="contenedor">
		<form action="">
			<div class="selectbox">
				<div class="select" id="select">
					<div class="contenido-select">
						<h1 class="titulo" align="center">¿Pais?</h1>
						<p class="CodPais"></p>
					</div>
					<i class="fas fa-angle-down"></i>
				</div>

				<div class="opciones" id="opciones">
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/mexico.png" alt="">
							<div class="textos">
								<h1 class="titulo">Mexico</h1>
								<p class="CodPais" style = "display: none">801</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/spain.png" alt="">
							<div class="textos">
								<h1 class="titulo">España</h1>
								<p class="CodPais" style = "display: none">410</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/colombia.png" alt="">
							<div class="textos">
								<h1 class="titulo">Colombia</h1>
								<p class="CodPais" style = "display: none">940</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/argentina.png" alt="">
							<div class="textos">
								<h1 class="titulo">Argentina</h1>
								<p class="CodPais" style = "display: none">113</p>
							</div>
						</div>
					</a>
					<a href="#" class="opcion">
						<div class="contenido-opcion">
							<img src="img/chile.png" alt="">
							<div class="textos">
								<h1 class="titulo">Chile</h1>
								<p class="CodPais" style = "display: none">333</p>
							</div>
						</div>
					</a>
				</div>
			</div>

			<input type="hidden" name="pais" id="inputSelect" value="">
		</form>

	</div>

    <script type="text/javascript">

        const select = document.querySelector('#select');
        const opciones = document.querySelector('#opciones');
        const contenidoSelect = document.querySelector('#select .contenido-select');
        const hiddenInput = document.querySelector('#inputSelect');

        document.querySelectorAll('#opciones > .opcion').forEach((opcion) => {
            opcion.addEventListener('click', (e) => {
                e.preventDefault();
                contenidoSelect.innerHTML = e.currentTarget.innerHTML;
                select.classList.toggle('active');
                opciones.classList.toggle('active');
                hiddenInput.value = e.currentTarget.querySelector('.CodPais').innerText;
                alert(hiddenInput.value);
                var parametros = {
                    "pais": hiddenInput.value
                };
                $.ajax({
                    url: 'setPais.php',
                    data: parametros,
                    success: function( data ) {
                        alert ( "Volviendo del servidor...");
                    }
                })
            });
        });

        select.addEventListener('click', () => {
            select.classList.toggle('active');
            opciones.classList.toggle('active');
        });
    </script>
    
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>