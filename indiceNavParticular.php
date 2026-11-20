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
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Indice navegacion particular</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="css/fuentes.css">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<script src="trad/traducciones.php"></script>
<script src="trad/traductor.js"></script>

<style>
    nav.navbar {
        background-color: #4cffff !important; 
        font-size: 22px;
    }

    header{
      position: fixed;
      width: 100%;
      z-index: 1000;
    }
    
    section {
        width: 100%;
        height: auto;
        margin: auto;
        overflow: hidden;
    }

    @font-face {
      font-family: 'CooperBlack Italic';
      src: url("fonts/CooperBlack Italic.ttf") format('truetype');
    }

</style>
</head>

<body>
<header id="header" style="width: 100%;">
    <div class="container" style="width: 100%;">
    <div class="row" style="padding-left: 15px;">
        <div style="display: inline-block; padding-top: 10px">
            <img id="logo" src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 100%; max-width: 300px;">
            <p data-trad="hipermercado inmobiliario" 
               style="font-family: 'CooperBlack Italic';
                font-size: calc(1.5 * 100%);
                margin: 0;
            ">
                hipermercado inmobiliario
            </p>
        </div>

        <div class="col" align="right" style="display: flex; justify-content: flex-end; align-items: flex-end;">
            <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
                <div style="display: flex;">
                    <div class="col">
                        <div class="row">
                            <div class="col"><strong><p data-trad="Consultar anuncio por referencia">Consultar anuncio por referencia</p></strong></div>
                        </div>
                        
                        <div class="row" style="width: 100%; margin: 0;">
                            <div style="
                                display: flex;
                                justify-content: flex-start; padding-left: 10px; padding-bottom: 10px;
                                width: 100%;
                            ">
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                ">
                                    <input type="image"
                                           id="elAnuncio"
                                           src="imagenes/lupa.png"
                                           alt="Consultar"
                                           title="Consultar"
                                           style="width: 28px; height: auto; flex-shrink: 0;">

                                    <input type="text"
                                           name="elAnuncio"
                                           placeholder="N° 0000 0000"
                                           style="
                                               width: 120px;   /* ← el tamaño que vos quieras */
                                               direction: rtl;
                                           ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!--
        <div class="row">
            <div class="col" style="width: fit-content; padding-left: 15px" align="left" >
                <img src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 90%; height: auto;"></img>
                <p data-trad="hipermercado inmobiliario" style="font-family: 'CooperBlack Italic', sans-serif; font-size: 50px; white-space: nowrap; ">
                  hipermercado inmobiliario
                </p>
            </div>

            <div class="col" align="right" style="display: flex; justify-content: flex-end; align-items: flex-end;">
                <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
                    <div style="display: flex;">
                        <div class="col">
                            <br>
                            <div class="row">
                                <div class="col" style="align-content: flex-end; padding-right: 0px; padding-bottom: 0px;"><strong><p data-trad="Consultar anuncio por referencia">Consultar anuncio por referencia</p></strong></div>
                            </div>

                            <div class="row">
                                <div class="col" style="padding-bottom: 5px; padding-right: 10px;">
                                    <input type="image" id="elAnuncio" 
                                           style="border: none;" alt="Consultar" 
                                           src="imagenes/lupa.png" width="11%" height="auto" title="Consultar">
                                    </input>
                                </div>
                                <div class="col col-5" style="padding-bottom: 3px; padding-left: 0px; padding-right: 5px">
                                    <input type="text" name="elAnuncio" placeholder="N° 0000 0000" style="direction: rtl;">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
-->
        <nav class="navbar navbar-expand-lg navbar-light navbar-toggler" style="height: auto">
<!--    <nav class="navbar navbar-expand-lg navbar-light" style="height: 5%"> -->

    <div class="container">

<!--        <nav class="navbar navbar-expand-lg navbar-light navbar-toggler" style="height: 50%"> -->
<!--        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0"> -->
<!--
            <div class="menu-hamburguesa">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarId" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
            </div>
-->
<!--          <div class="container-fluid"> -->
              
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"  
                  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent" data-toggle="collapse" 
                 data-target="#navbarSupportedContent.show">
              <ul class="navbar-nav mr-auto">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownAnuncios" role="button" 
                         data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" data-trad="Anuncios">Anuncios</a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdownAnuncios">
                        <a class="dropdown-item" href="pruebaDetalles.php?operacion=A" target="panel" data-trad="Publicar">Publicar</a>
                        <a class="dropdown-item" href="pruebaDetalles.php?operacion=B" target="panel" data-trad="Buscar">Buscar</a>
                        <a class="dropdown-item" href="indexAnuncios.php" target="panel" data-trad="Administrar anuncios">Administrar anuncios</a>
                        <a class="dropdown-item" href="indexBusquedas.php" target="panel" data-trad="Administrar búsquedas">Administrar búsquedas</a>
                      </div>
                  </li>
                  <?php if ($_SESSION['clientetipo'] <> "O"){?>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownOperaciones" role="button" 
                             data-toggle="dropdown" aria-expanded="false" data-trad="Operaciones">Operaciones</a>
                          <ul class="dropdown-menu" aria-labelledby="navbarDropdownOperaciones">
                            <li><a class="dropdown-item" href="<?php echo ('estadoMonederoYAvisos.php?anunciante='.$_SESSION['anunciante'])?>"  target="panel" data-trad="Estado Monedero y Avisos">
                                Estado Monedero y Avisos</a></li>
                            <li><a class="dropdown-item" href="<?php echo ('indexHistInformesEstado.php')?>"  target="panel" data-trad="Historia Informes">Historia Informes</a></li>
                            <li><a class="dropdown-item" href="<?php echo ('indexSucursales.php')?>" target="panel" data-trad="Sucursales">Sucursales</a></li>
                            <li><a class="dropdown-item" href="comprarDias.php" target="panel" data-trad="Cargar monedero">Cargar monedero</a></li>
                          </ul>
                      </li>
                    <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="modificarFicha.php" target="panel" data-trad="Mi Perfil">Mi Perfil</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="cambioDeClaveAcceso.php" target="panel" data-trad="Cambiar contraseña">Cambiar contraseña</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="mailContacto.html" target="panel" data-trad="Contacto">Contacto</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="salir.php" data-trad="Salir">Salir</a>
                  </li>
            </ul>
          </div>
         </div>
        </nav>
      <?php 
        include("perfil.php");
      ?>
<!--      <section>
        <iframe name="panel" scrolling="auto" style="border-top: 1px solid black;"></iframe>
      </section>
        <section style="width: 100%; height: 100%;">
            <iframe id="panel" name="panel" scrolling="yes" width="100%" height="100%" style="border-top: 1px solid black;"></iframe>
        </section>
-->
        <section style="width:100%; height:100vh; margin:0; padding:0;">
            <iframe id="panel" name="panel"
                    style="width:100%; height:100%; border-top:1px solid black; padding-bottom: 200px"
                    scrolling="yes"></iframe>
        </section>

    </div>
</header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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