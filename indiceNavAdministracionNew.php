<?php
    session_start();
    require_once("conexion.php");

    if (!isset( $_SESSION['anunciante'] )){
        header("Location: " . "accesoDenegado.html"); 
        exit();
    }

    if (isset($_SESSION['busquedaPublica'])){
        unset($_SESSION['busquedaPublica']);
    }

    $texto = "select * from paises where estado = 1 order by DESCRIPCION";
    $query = mysqli_query($con, $texto);
?>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Preview - Plusdata Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>

<script src="trad/traducciones.php"></script>
<script src="trad/traductor.js"></script>
    
<link rel="stylesheet" href="css/homeNew.css" type="text/css">

<style>
    @font-face {
      font-family: 'CooperBlack Italic';
      src: url("fonts/CooperBlack Italic.ttf") format('truetype');
}
</style>
</head>
<body>

<header id="header">

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="logo-area">
      <div class="logo-img-placeholder">
        <div class="logo-text-block">
            <img id="logo" src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 100%; max-width: 300px;">
            <p data-trad="hipermercado inmobiliario" style="font-family: 'CooperBlack Italic';
                font-size: calc(1.4 * 100%);margin: 0;">
            </p>
        </div>
      </div>
    </div>

    <div class="topbar-utils">
      <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
          <div class="ref-row">
            <span class="ref-label" data-trad="Consultar anuncio por referencia"></span>
            <div class="ref-search">
              <span class="lupa">🔍</span>
              <input type="text" placeholder="N° 0000 0000" name="elAnuncio">
            </div>
          </div>
      </form>
    </div>
  </div>


  <!-- NAVBAR HOME -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid" style="padding:0 8px;">
      <div class="collapse navbar-collapse show" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" 
                 data-trad="Anuncios"></a>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="cargarDetallesAwait.php?operacion=A" target="panel" data-trad="Publicar">Publicar</a></li>
                  <li><a class="dropdown-item" href="cargarDetallesAwait.php?operacion=B" target="panel" data-trad="Buscar">Buscar</a>
                  </li>
                  <li><a class="dropdown-item" href="indexAnuncios.php" target="panel" data-trad="Administrar anuncios">Administrar anuncios</a></li>
                  <li><a class="dropdown-item" href="indexBusquedas.php" target="panel" data-trad="Administrar búsquedas">Administrar búsquedas</a></li>
                  <li>
                  <a class="dropdown-item" href="indexDetalles.php" target="panel" data-trad="Administrar mensajes formulario"></a>
                  </li>
              </ul>
          </li>
          <li class="nav-item dropdown">
              <?php if ($_SESSION['clientetipo'] <> "O"){?>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-trad="Operaciones">Operaciones</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo ('estadoMonederoYAvisos.php?anunciante='.$_SESSION['anunciante'])?>"  target="panel" data-trad="Estado Monedero y Avisos">
                            </a></li>
                        <li><a class="dropdown-item" href="<?php echo ('indexHistInformesEstado.php')?>"  target="panel" data-trad="Historia Informes"></a></li>
                        <li><a class="dropdown-item" href="<?php echo ('indexSucursales.php')?>" target="panel" data-trad="Sucursales"></a></li>
                        <li><a class="dropdown-item" href="comprarDias.php" target="panel" data-trad="Cargar monedero"></a></li>
                        <li><a class="dropdown-item" href="Datos-Plantilla-Email.html" target="panel" data-trad="Envío de E-Mail Prueba"></a></li>
                        <li><a class="dropdown-item" href="disparaInformeGlobal.php" target="panel" data-trad="Estado Monedero y Avisos Global">
                            </a></li>
                      </ul>
                  </li>
                <?php } ?>
          </li>
              <li class="nav-item">
                <a class="nav-link" href="index_paises.php" target="panel" data-trad="UbicGeo y C.P."></a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="indexFichas.php" target="panel" data-trad="Adm.Fichas"></a>
              </li>

              <li class="nav-item">
                <a class="nav-link " href="modificarFicha.php" target="panel" data-trad="Mi Perfil"></a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="indexCP.php" target="panel" data-trad="Códigos Postales"></a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="excell/index.html" data-trad="Inmob.mailing"></a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="salir.php" data-trad="Salir">Salir</a>
              </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- COUNTRY SECTION -->
  <div class="country-section">
    <div class="label">Elija país</div>
    <div class="flags-row">
    <?php
        $finales=0;
        while($row = mysqli_fetch_array($query)){	
            $bandera=$row['banderanew'];
            $moneda=$row['moneda'];
            $moneda1=$row['moneda1'];
            $pais=$row['DESCRIPCION'];
            $codPais=$row['COD_NUM'];
    ?>
      <div class="flag-item">
        <a style="text-decoration:none" href="setEntornoNew.php?codPais=<?php echo $codPais?>&moneda=<?php echo $moneda ?>&moneda1=<?php echo $moneda1 ?>">
        <?php if (isset($_SESSION['codPais']) && $codPais === $_SESSION['codPais']){?>
            <img class="flag-img" src="<?php echo ($bandera);?> " title="<?php echo $pais?>" style="filter: drop-shadow(4px 4px 8px rgba(0,0,0,0.8)); object-fit: cover;"></a>
        <?php } else { ?>
            <img class="flag-img" src="<?php echo ($bandera);?> " title="<?php echo $pais?>"></a>
        <?php } ?>
        <span class="flag-name"><?php echo $pais?></span>
      </div>
      <?php } ?>
    </div>
  </div>

  <section id="sectorvisual" style="width: 100%; height: 100%;">
    <iframe id="panel" name="panel" scrolling="yes" width="100%" height="900px" style="border-top: 1px solid black;"></iframe>
  </section>

</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php
if (isset($_SESSION['anuncioSelSinPais'])) {
//    echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
//    return;
    $llamarA=$_SESSION['anuncioSelSinPais'];
    unset ($_SESSION['anuncioSelSinPais']);
    echo '<script> document.getElementById("panel").src = "'.$llamarA.'" ;</script>';
}
?>
</html>
