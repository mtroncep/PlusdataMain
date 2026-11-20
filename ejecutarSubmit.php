<?php
session_start();

$_SESSION['datosformulario']=$_POST;

if($_SERVER["REQUEST_METHOD"] == "POST") {
//  var_dump($_POST);
//  return;
  $check = $_POST['queSubmito'];
  if($check === "Publicar" || $check === "Buscar") {
       header("Location: actualizaAnuncioBD.php");
  }

  
   if($check == "Vista Previa") {
       header("Location: procesarVistaPreviaAnuncio.php");
  }
}
?>