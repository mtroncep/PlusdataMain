<?php
session_start();
require_once("conexion.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<?php
    if(!empty($_POST['password']) && !empty($_POST['confirm_password'])){
        // Comparacion de las claves ingresadas
        if($_POST['password'] !== $_POST['confirm_password']){
			 echo '<script type="text/javascript">alert("Las claves ingresadas son diferentes");</script>';
             echo '<script type="text/javascript">history.go(-1);</script>';
            }
            else
            {
            //check whether identity code exists in the database
            $mailOlvidoClave = $_SESSION['mailOlvidoClave'];
        	$count=mysqli_query($con,"SELECT idanunciante, password FROM anunciantes WHERE email='$mailOlvidoClave'");
            $row = mysqli_fetch_array($count);
            $nroAnunciante = $row['idanunciante'];

            if(mysqli_num_rows($count) > 0){
                $nuevaClave=password_hash($_POST['password'], PASSWORD_DEFAULT);
                $cambioClave=mysqli_query($con,"UPDATE anunciantes set password = '$nuevaClave' where idanunciante = $nroAnunciante");
                if ($cambioClave){
                    echo '<script>alert("La clave ha sido cambiada con exito.");</script>';
                } else {
                    echo '<script>alert("ERROR - La clave no ha sido cambiada.");</script>';
                }
//                echo "CambioTipoClave: ".$_SESSION['menuinicio'];
//                return;
                if ($_SESSION['tipoCambioClave'] === "olvido"){
                    echo ('<script type="text/javascript">'."window.location.href = 'index.php'</script>");
                } else {
                    $menuinicio = $_SESSION['menuinicio'];
//                    echo ('<script type="text/javascript">'."window.location.reload(true);</script>");
//                    return;
                    echo ('<script>'."window.parent.location.href = '$menuinicio'</script>");
//                    echo '<script type="text/javascript">alert("Continuar...");</script>';
                }
            }
        }
    }
?>
<body>
</body>
</html>