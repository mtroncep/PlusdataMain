<?php
	session_start();
//	session_unset();

	$_SESSION['busquedaPublica'] = 1;

	echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
?>