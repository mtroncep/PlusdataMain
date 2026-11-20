<?php 
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$email = $_GET['email']; 
if (Funciones::verActivada($email, $con) === "SI"){
    echo '<script type="text/javascript">alert("LA CUENTA YA SE ENCUENTRA CONFIRMADA, NO HACE FALTA CONFIRMACION")</script>';
	echo ('<script type="text/javascript">'."window.location.href = 'indexNew.php'</script>");
//    header("Location: https://www.plusdata.info/testing/indexNew.php");
    exit;
}

if(isset($_GET['email'] )){
    $email = $_GET['email'];
}else{
    header('Location: ./indexNew.php');
    exit;
}

$telReg="No informado...";
if (isset($_GET['telReg'])){
    $telReg = $_GET['telReg'];
}
error_log("$telReg en confirm.php: ".$telReg,0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifiar Cuenta</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container" align="center">
        <div class="row" style="margin-top:15%">
            <form action="verificar.php" method="POST">
                <h2 class="col-5">Validación Correo Electronico</h2>
                <div class="mb-3 col-2">
                    <label for="c" class="form-label">Código validar</label>
                    <input type="number" class="form-control" id="c" name="codigo" required>
                    <input type="hidden" class="form-control" id="email" name="email" value="<?php echo $email;?>">
                </div>
               
                <?php
                if (Funciones::obtenerValorParametro($con,'ENVIO_SMS')){ ?>
                    <h2 class="col-5">Validación Número de Movil</h2>
                    <div class="mb-3 col-2" >
                        <label for="c" class="form-label">Código validar</label>
                        <input type="number" class="form-control" id="cm" name="codigom" required>
                        <input type="hidden" class="form-control" id="movil" name="movil" value="<?php echo $telReg;?>">
                    </div>
                <?php } ?>
                
                <button type="submit" class="btn btn-primary">Verificar</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>