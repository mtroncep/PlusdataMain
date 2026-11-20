<?php

session_start();
require_once("conexion.php");
$password = "";

try {
    // Obtener la clave ingresada (enviada desde JavaScript)
    $data = json_decode(file_get_contents("php://input"), true);
    $claveIngresada = $data['clave'];

    $emailReg=$_SESSION['emailReg'];
    $resultado=mysqli_query($con,"SELECT password FROM anunciantes WHERE email='$emailReg'");
    $row = mysqli_fetch_array($resultado);
    
    if ($resultado) {
        $claveHashAlmacenada = $row['password'];
        // Verificar la clave ingresada con la almacenada
        if (password_verify($claveIngresada, $claveHashAlmacenada)) {
            echo "<span style='color: green;'>Clave correcta</span>";
        } else {
            echo "<span style='color: red;'>Clave incorrecta</span>";
        }
    } else {
        echo "<span style='color: red;'>Usuario no encontrado</span>";
    }
} catch (PDOException $e) {
    echo "<span style='color: red;'>Error en el servidor: " . $e->getMessage() . "</span>";
}
?>