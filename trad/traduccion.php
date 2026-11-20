<?php
session_start();
// archivo: traduccion.php
require_once("../conexion.php");

//header("Content-Type: application/javascript; charset=UTF-8");

$idioma = isset($_SESSION['idiomapermanente'])
            ? $_SESSION['idiomapermanente']
            : 'es';
$sql = "SELECT clave, texto FROM traducciones WHERE idioma = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $idioma);
$stmt->execute();
$result = $stmt->get_result();

$traducciones = [];
while ($row = $result->fetch_assoc()) {
  $traducciones[$row['clave']] = $row['texto'];
}

$json = json_encode($traducciones, JSON_UNESCAPED_UNICODE);

?>
window.TRADUCCIONES = <?php echo $json; ?>;

