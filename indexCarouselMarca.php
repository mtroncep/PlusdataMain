<?php
$title="Carousel Bootstrap 3 con PHP y MySQL";
/* Llamar la Cadena de Conexion*/ 
include ("conexion.php");
$active="active";

session_start();

if (isset($_GET['volver_a'])){
    $volver_a = $_GET['volver_a'];
//    echo "Recibo: ".$volver_a.'<br>';
    $volver_a = str_replace("*", "&", $volver_a);
    $volver_a = str_replace("¿", "?", $volver_a);
//    echo "Convertido: ".$volver_a.'<br>';
//    $url = urldecode($_GET['volver_a']);
//    echo "La URL recibida es: " . htmlspecialchars($url) . "<br>";
//    return;
} else {
    $volver_a="index.php";
}

if (!isset($_REQUEST['noBorrar'])){
    $noBorrar="0";
} else {
    $noBorrar=$_REQUEST['noBorrar'];
}

$anuncio = $_REQUEST['elAnuncio'];

/*
if (isset($_REQUEST['pais'])){
    $pais = $_REQUEST['pais'];
    $anuncio = $_REQUEST['elAnuncio'];
}
*/

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $title;?></title>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<style>
    #carousel-example-captions {
       display:grid; 
       grid-template-columns:1fr;
       gap:1rem;
    }
    .carousel-inner {
       display:grid;
       justify-content: center;
       align-content: center;
    }
    .item {
       border:5px solid #333;
       display: grid;
       grid-template-columns: 1fr;
       grid-template-rows: 1fr;
    }
    .item img {
       width: 100%;
       height: 100%;
       object-fit: cover;
       object-position: 50% 50%;
    }
</style>

<script>
    function confirmarBorrar(imagenBorrar){
        var result = confirm('¿Borrar Imagen?');
         if(result){
            window.open("BorrarImagen.php?imagen=" + imagenBorrar, "_self");
        }
        return result;
    }
</script>
</head>
<body>
  <hr>
    <div class="row-fluid">
      <div class="container">
        <div class="col-xs-9">
            <div class="Salir">
                <?php
                if (isset($_SESSION['windowCerrar'])){ ?>
                    <a href="#" class="close" onClick="window.close();">Volver</a>
                <?php
                } else { ?>
                    <a href="<?php echo $volver_a?>" class="close">Volver</a>
                <?php }
                ?>
            </div>
        </div>
          
        <div class="col-xs-9">
			<div id="carousel-example-captions" class="carousel slide" data-ride="carousel"> 
				<?php
					$sql_slider=mysqli_query($con,
                    "select * from slider where nroanuncio=".$anuncio." and estado=1 order by secanuncio");
					$nums_slides=mysqli_num_rows($sql_slider);
				?>
                <div class="carousel-inner"> 
				<?php
					$active="active";
					while ($rw_slider=mysqli_fetch_array($sql_slider)){
				    if ($noBorrar === "0") {?>	
                        <div class="item <?php echo $active;?>">
                            <?php
                                $imagen="fotosPublicaciones/".$rw_slider['urlimagen'];
                            ?>
                            <img src="prueba_lista.php?imagen=<?php echo $imagen?>"
                                 onClick="confirmarBorrar('<?php echo $rw_slider['urlimagen'];?>')">
                            </img>
<!--                        <img src="fotosPublicaciones/<?php echo $rw_slider['urlimagen'];?>"> -->
<!--                            <img src="fotosPublicaciones/<?php echo $rw_slider['urlimagen'];?>" 
                                 onClick="confirmarBorrar('<?php echo $rw_slider['urlimagen'];?>')">
-->
                        </div>
					<?php } else { ?>
                    <div class="item <?php echo $active;?>">
                        <?php
                            $imagen="fotosPublicaciones/".$rw_slider['urlimagen'];
                        ?>
                        <img src="prueba_lista.php?imagen=<?php echo $imagen?>"></img>
<!--                        <img src="fotosPublicaciones/<?php echo $rw_slider['urlimagen'];?>"> -->
                    </div>
                    <?php }
                    $active="";
					}
				?>
				</div> 
				<a class="left carousel-control" href="#carousel-example-captions" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> 
				<a class="right carousel-control" href="#carousel-example-captions" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> 
			</div>
        </div>

        <?php if ($noBorrar == "0") { ?>
            <div class="col-xs-9">
                <div class="Mostrar">
                    <p>Clic en la imagen para borrarla</p>
                </div>
            </div>
        <?php } ?>          

    </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>