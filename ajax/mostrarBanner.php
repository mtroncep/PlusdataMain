<?php
	session_start();
    require_once ("../conexion.php");
    require_once("../cargarDatosFormulario.php");
?>
<?php
    if ($_SESSION['clientetipo'] <> "P" && $_SESSION['clientetipo'] <> "Q"){
        $imagen[] = "";
        $imagen = Funciones::obtenerImgBanner($_SESSION['codPais'],$_SESSION['anunciante'],1,$con);

        $linea='<div class="row">';
        $linea.='<div class="col-12 col-md-8 col-lg-6">';
        $linea.='<div class="property-entry h-100">';
        if ($imagen[0]){
            $linea.="<img src=$imagen[1] class=img-fluid border=2 onClick=borrarBanner();>";
        } else {
            $linea.="<img src=$imagen[1] class=img-fluid border=2>";
        }
        $linea.="</div>";
        $linea.="</div>";
        $linea.="</div>";

        if ($imagen[0]){
            $linea.='<div class="col-xs-9">';
            $linea.='<div class="Mostrar">';
            $linea.='<p>Clic en la imagen para borrarla</p>';
            $linea.='</div>';
            $linea.='</div>';
        }

        echo $linea;
    }
?>
