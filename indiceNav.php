<?php
	session_start();
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Webdisenia .:. Ejemplo Bootstrap</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<style>
	nav.navbar {
    	background-color: #A9EBF3;
	}
	</style>
</head>
<body>
<img src="imagenes/bannerAlicante.jpg" alt="" width=100%/>

<nav class="mobile-offcanvas navbar navbar-expand-lg navbar-light navbar-toggler">
  <div class="container-fluid">
  <a class="navbar-brand" href="#"><img class="navbar-btn navbar-link navbar-nav" src="imagenes/logoClaro.png" width="90" height="45" alt=""/></a>
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarId" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>
  	<div class="collapse navbar-collapse" id="navBarId">
	  <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link">Mi Cuenta Personal | </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pruebaDetalles.php?operacion=A">Publicar | </a>
      </li>
      <li><a class="nav-link" href="pruebaDetalles.php?operacion=B">Búsquedas | </a>
	  </li>
      <li><a class="nav-link" href="indexAnuncios.php">Administrar | </a>
	  </li>
      <li class="nav-item">
        <a class="nav-link" href="modificarFicha.php">Ficha | </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="salir.php">Salir</a>
      </li>
    </ul>
  </div>
  </nav>

<?php //include("perfil.php");?>

<?php
    $query = mysqli_query($con,"select * from paises order by descripcion");
?>
    <div class="container-fluid">
        <div class="row col-2">
            <div class="table-wrapper-scroll-y position-relative text-center">
                <table>
<!--                    <tbod> -->
                        <?php
                        $finales=0;
                        while($row = mysqli_fetch_array($query)){	
                            $bandera=$row['bandera'];
                            $moneda=$row['moneda'];
                            $moneda1=$row['moneda1'];
                            $pais=$row['DESCRIPCION'];
                            $codPais=$row['COD_NUM'];
                        ?>
                        <tr>
                            <td><a href="setEntorno.php?codPais=<?php echo $codPais?>&moneda=<?php echo $moneda ?>&moneda1=<?php echo $moneda1 ?>"><img src = data:image/png;base64,<?php echo base64_encode($bandera);?> width = "20px" height = "15px" title="<?php echo $pais?>"</a></td>
                        </div>
                        <td> 
                          <?php if (isset($_SESSION['codPais']) && $codPais === $_SESSION['codPais']){?>
                                    <img src="imagenes/tilde.jpg" width="10" height="7" alt="Ubicación actual"/>
                                <?php } ?>
                        </td>
                        </tr>
                        <?php } ?>
<!--                    </tbod> -->
              </table>
            </div>
       </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>