<?php
	session_start();
    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");
?>
	<h6><?php echo($_REQUEST['titulo'])?></h6>
<?php
    $imagen = Funciones::obtenerImgAnuncio($_SESSION['codPais'],$_SESSION['anuncio'],1,$con);
    $carousell = "indexImagenes.php";
    echo ('<div class="row">
           <div class="col-4">
            <p> </p>
           </div>
           <div class="col-4">
            <div class="property-entry h-100">
              <a href="'.$carousell.'" class="property-thumbnail">
                <img src='.$imagen.' alt="Primera imagen" class="img-fluid" border=10>
              </a>
            </div>
           </div>
           <div class="col-4">
            <p> </p>
           </div>

           <div class="row">
            <p class="col-4" align="center"> </p>
            <p class="col-4" align="center">Clic en la imagen para actualizar</p>
            <p class="col-4" align="center"> </p>
           </div>
        </div>');
?>
