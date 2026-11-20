<?php
    session_start();
// Verificas si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtienes los valores de las variables enviadas desde JavaScript
    $_SESSION['moneda'] = $_POST['moneda'];
    $_SESSION['moneda1'] = $_POST['moneda1'];
    $_SESSION['codPais'] = $_POST['codPais'];

    echo $_SESSION['codPais']."<br>";
    echo $_SESSION['moneda']."<br>";
    echo $_SESSION['moneda1'."<br>"];
    echo $_SESSION['menuinicio']."<br>";
}
?>
