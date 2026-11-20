<!doctype html>
<?php
    session_start();
    if (!isset($_SESSION['codPais'])){
        echo ("<script>alert('Falta seleccionar el país.');</script>");
//        echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
        echo ('<script type="text/javascript">'."window.close();"."</script>");
    }
?>
<html>
<head>
    <title>Compra de dias de publicacion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body>
<form id="loginform" method="post" action="cargarImporteMonederoBD.php">
    <div>
        Importe:
        <input type="text" name="importe" id="importe" required /> 
        <input type="submit" name="loginBtn" id="loginBtn" value="Cargar" />
    </div>
</form>
</body>
</html>