<?php 
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

if(isset($_GET['email'])){
    $email = $_GET['email'];
}else{
    header('Location: ./index.php');
}

if(isset($_GET['vuelta'])){
    $vuelta = $_GET['vuelta'];
}else{
    header('Location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifiar Cuenta Segundo Factor de Acceso</title>
    <!-- CSS only -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container" align="center">
        <div class="row" style="margin-top:15%">
            <form action="verificar2FA.php" method="POST">
                <div class="col" align="center">
                    <img src="imagenes/logoHomePlusdata.png" style="width: 20%; height: auto; padding-bottom: 30px"></img>
                </div>
                
                <h1 class="col-5"><strong>Hemos enviado el código de acceso por mail</strong></h1>
                <p><br></p>
                <h2 class="col-5">Validación Correo Electronico</h2>
                <div class="mb-3 col-2">
                    <label for="c" class="form-label">Código validar</label>
                    <input type="number" class="form-control" id="c" name="codigo" required>
                    <input type="hidden" class="form-control" id="email" name="email" value="<?php echo $email;?>">
                    <input type="hidden" class="form-control" id="vuelta" name="vuelta" value="<?php echo $vuelta;?>">
                </div>
                <button type="submit" class="btn btn-primary">Verificar</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>
