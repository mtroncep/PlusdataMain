<!doctype html>
<?php
	session_start();
    require_once("conexion.php");

    $texto="select * from paises where COD_NUM =".$_REQUEST['codPais'];

    $query = mysqli_query($con, $texto);
    $row = mysqli_fetch_array($query);

    $moneda = $_SESSION['moneda'] = $row['moneda'];
    $moneda1 = $_SESSION['moneda1'] = $row['moneda1'];
    $codPais = $_SESSION['codPais'] = $row['COD_NUM'];

    if (isset($_SESSION['busquedaPublica']) && $_SESSION['busquedaPublica'] == 1){

        if (!isset($_SESSION['idiomapermanente'])) {
            $_SESSION['idiomapermanente'] = 'es';
        }

        $idioma = $_SESSION['idiomapermanente'];
        $_SESSION['idioma']=$idioma;

        $_SESSION['menuinicio']='indexNew.php';
        $menuinicio = $_SESSION['menuinicio'];
        $_SESSION['buscandoExterno'] = 1;
        echo ('<script type="text/javascript">'."window.location.href = '$menuinicio'</script>");
//        echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
        return;
    } else {
        if (!isset($_SESSION['idiomapermanente'])) {
            $_SESSION['idiomapermanente'] = 'es';
        }
        $idioma = $_SESSION['idiomapermanente'];
        $_SESSION['idioma']=$idioma;
    }

    if (!isset($_SESSION['menuinicio'])){
        $_SESSION['menuinicio']='indexNew.php';
        $menuinicio = $_SESSION['menuinicio'];
        echo ('<script type="text/javascript">'."window.location.href = '$menuinicio'</script>");
        return;
    } 

    $menuinicio = $_SESSION['menuinicio'];
//    $banderaPais = $_SESSION['banderaPais'] = $_GET['banderaPais'];
    echo ('<script type="text/javascript">'."window.location.href = '$menuinicio'</script>");
//    echo $codPais.'<br>';
//    echo $monedaPais.'<br>';
?>
