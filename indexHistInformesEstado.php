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
<title>Consultar Historico de Informaes de Estado</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="css/fuentes.css">

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/custom_new.css">

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

<!-- <script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script> -->
    
<script>
     function traducir(texto) {
      if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
      }
      return texto;
    }

    function aplicarTraducciones() {
        document.querySelectorAll('[data-trad]').forEach(el => {
          const clave = el.getAttribute('data-trad');
        // Si es un input con placeholder
        if (el.hasAttribute('placeholder')) {
          el.setAttribute('placeholder', traducir(clave));
        } else {
          // Para otros elementos, traducimos el texto interno
          el.innerText = traducir(clave);
        }
        });
      }

      // Ejecutamos al cargar la página
    window.onload = aplicarTraducciones();
</script>
    
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
			<div class="table-title">
        		<div class="row">
                    <div class="col-sm-5 col-3">
						<h2 data-trad="Historico de Informe de estado">Historico de Informe de estado</h2>
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
	<script src="js/scriptHistoricoEstados.js"></script>
</body>
</html>