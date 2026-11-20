<?php
$title="Carousel Bootstrap 3 con PHP y MySQL";
/* Llamar la Cadena de Conexion*/ 
include ("conexion.php");
$active="active";

session_start();

if (isset($_REQUEST['volver_a'])){
    $volver_a = $_REQUEST['volver_a'];
//    echo $volver_a."<br>";
//    return;
} else {
    $volver_a="index.php";
}

if (!isset($_REQUEST['noBorrar'])){
    $noBorrar="0";
} else {
    $noBorrar=$_REQUEST['noBorrar'];
}

if (isset($_REQUEST['pais'])){
    $pais = $_REQUEST['pais'];
    $anuncio = $_REQUEST['anuncio'];
} else {
    $pais = "410";
    $anuncio = $_REQUEST['anuncio'];
}

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
    .carousel-item{
          width: 100%;
          height: 650px;
          background-repeat: no-repeat;
          background-color: #FFFFFF;
          background-position: center;
    }
    .image-cover{
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }

    .image-contain{
         -webkit-background-size: contain;
          -moz-background-size: contain;
          -o-background-size: contain;
          background-size: contain;
    }
    
    .image-custom1{
        background-size: 100%;
    }
    
    .Salir{
        background-color: indigo;
        
    }
    
    .carousel-inner img {
    width: 100%;
    max-height: 500px;
    }

    .carousel-inner{
     height: 500px;
    }
    
    body {
        background-color: "#B4DEE8";
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
                <a href="indexImagenes.php" class="close">Volver</a>
            </div>
        </div>
          
        <div class="col-xs-9">
			<div id="carousel-example-captions" class="carousel slide" data-ride="carousel"> 
				<?php
					$sql_slider=mysqli_query($con,
                    "select * from slider where pais=".$pais." and nroanuncio=".$anuncio." and estado=1 order by secanuncio");
					$nums_slides=mysqli_num_rows($sql_slider);
				?>
                <div class="carousel-inner" role="listbox"> 
				<?php
					$active="active";
					while ($rw_slider=mysqli_fetch_array($sql_slider)){
				    if ($noBorrar === "0") {?>	
                        <div class="item <?php echo $active;?>">
                            <img src="fotosPublicaciones/<?php echo $rw_slider['urlimagen'];?>" 
                                 onClick="confirmarBorrar('<?php echo $rw_slider['urlimagen'];?>')">
                        </div>
					<?php } else { ?>
                    <div class="item <?php echo $active;?>">
                        <img src="fotosPublicaciones/<?php echo $rw_slider['urlimagen'];?>">
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