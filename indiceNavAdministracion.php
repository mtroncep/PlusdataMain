<?php
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    require_once("conexion.php");

    if (!isset( $_SESSION['anunciante'] )){
        header("Location: " . "accesoDenegado.html"); 
        exit();
    }

    if (isset($_SESSION['busquedaPublica'])){
        unset($_SESSION['busquedaPublica']);
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Webdisenia .:. Ejemplo Bootstrap</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" src="css/fuentes.css" type="text/css">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<style>
    nav.navbar {
        background-color: #4cffff !important; 
        font-size: 22px;
        font-weight: bold
    }

    header{
      position: fixed;
      width: 100%;
      z-index: 1000;
    }
    
    iframe {
        overflow: scroll !important;
        width: 100%;
        height: 600px;
        padding-bottom: 200px;
    }    
    
    section {
        width: 100%;
        height: auto;
        margin: auto;
        overflow: hidden;
    }

</style>
    <script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script>
</head>

<body>
<header id="header" style="width: 100%;">
    <div class="container" style="width: 100%;">
        <div class="row">
            <div class="col" style="width: fit-content; padding-left: 0px" align="left" >
                <img src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 90%; height: auto;"></img>
                <p data-trad="hipermercado inmobiliario" style="font-family: 'Ag_cooper', sans-serif; font-size: 45px; white-space: nowrap; ">
                  hipermercado inmobiliario
                </p>
            </div>

            <div class="col" align="right" style="display: flex; justify-content: flex-end; align-items: flex-end;">
                <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
                    <div style="display: flex;">
                        <div class="col">
                            <div class="row">
                                <div class="col" style="align-content: flex-end; padding-right: 0px; padding-bottom: 0px;"><strong><p data-trad="Consultar anuncio por referencia">Consultar anuncio por referencia</p></strong></div>
                            </div>
                            <div class="row">
                                <div class="col" style="padding-bottom: 5px; padding-right: 5px;">
                                    <input type="image" id="elAnuncio" 
                                           style="border: none;" alt="Consultar" 
                                           src="imagenes/lupa.png" width="11%" height="auto" title="Consultar">
                                    </input>
                                </div>
                                <div class="col col-5" style="padding-bottom: 3px; padding-left: 0px">
                                    <input type="text" name="elAnuncio" placeholder="N° 0000 0000" style="direction: rtl;">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-light navbar-toggler" style="height: 50%">
<!--        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0"> -->
<!--          <a class="nav-item" data-toggle="collapse" data-target="#navbarSupportedContent" onClick="window.location.reload();"><img src="imagenes/logoHomePlusdata.png" class="nav-item" title="Pagina inicio" width="30%" height="auto"/></a> -->

          <div class="container-fluid">
        
            <div class="menu-hamburguesa">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBarId" aria-controls="navBarId" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
            </div>

          <div class="collapse navbar-collapse" id="navBarId" data-toggle="collapse" 
          data-target="#navBarId.show">
              <ul class="navbar-nav mr-auto">
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownAnuncios" role="button" 
                         data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" data-trad="Anuncios">Anuncios</a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdownAnuncios">
                        <a class="dropdown-item" href="pruebaDetalles.php?operacion=A" target="panel" data-trad="Publicar">Publicar</a>
                        <a class="dropdown-item" href="pruebaDetalles.php?operacion=B" target="panel" data-trad="Buscar">Buscar</a>
                        <a class="dropdown-item" href="indexAnuncios.php" target="panel" data-trad="Administrar anuncios">Administrar anuncios</a>
                        <a class="dropdown-item" href="indexBusquedas.php" target="panel" data-trad="Administrar búsquedas">Administrar búsquedas</a>
                        <a class="dropdown-item" href="indexDetalles.php" target="panel" data-trad="Administrar mensajes formulario">Administrar mensajes formulario</a>
                      </div>
                  </li>

                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownOperaciones" role="button" 
                         data-toggle="dropdown" aria-expanded="false" data-trad="Operaciones">Operaciones</a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownOperaciones">
                        <li><a class="dropdown-item" href="<?php echo ('estadoMonederoYAvisos.php?anunciante='.$_SESSION['anunciante'])?>"  target="panel" data-trad="Estado Monedero y Avisos">
                            Estado Monedero y Avisos</a></li>
                        <li><a class="dropdown-item" href="<?php echo ('indexHistInformesEstado.php')?>" target="panel" data-trad="Historia Informes">Historia Informes</a></li>
                        <li><a class="dropdown-item" href="<?php echo ('indexSucursales.php')?>" target="panel" data-trad="Sucursales">Sucursales</a></li>
                        <li><a class="dropdown-item" href="<?php echo ('indexPrecios.php')?>" target="panel" data-trad="Actualización de precios">Actualización de Precios</a></li>
                        <li><a class="dropdown-item" href="comprarDias.php" target="panel" data-trad="Cargar monedero">Cargar monedero</a></li>
                        <li><a class="dropdown-item" href="Datos-Plantilla-Email.html" target="panel" data-trad="Envío de E-Mail Prueba">Envío de E-Mail Prueba</a></li>
                          
                      </ul>
                  </li>
                  

                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="cambioDeClaveAcceso.php" target="panel" data-trad="Cambiar clave">Cambiar clave</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="index_paises.php" target="panel" data-trad="UbicGeo y C.P.">UbicGeo y C.P.</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="indexFichas.php" target="panel" data-trad="Adm.Fichas">Adm.Fichas</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="modificarFicha.php" target="panel" data-trad="Mi Perfil">Mi Perfil</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="indexCP.php" target="panel" data-trad="Códigos Postales">Códigos Postales</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link text-black-50" href="excell/index.html" data-trad="Inmob.mailing">Inmob.mailing</a>
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
      <section>
        <iframe name="panel" scrolling="yes" style="border-top: 1px solid black;"></iframe>
      </section>
    </div>
</header>

<!--    <script src="trad/traduccion.php?lang=<?php //echo $_SESSION['idioma'];?>"></script> -->

    <script>
        document.querySelectorAll('[data-trad]').forEach(el => {
          const clave = el.getAttribute('data-trad');
          if (window.TRADUCCIONES?.[clave]) {
            el.innerText = window.TRADUCCIONES[clave];
          }
        });
    </script>

    <script>
        function cambiarIdioma(lang) {
          const script = document.createElement('script');
          script.src = `trad/traduccion.php?lang=${lang}`;
          script.onload = () => {
            document.querySelectorAll('[data-trad]').forEach(el => {
              const clave = el.getAttribute('data-trad');
              if (window.TRADUCCIONES?.[clave]) {
                el.innerText = window.TRADUCCIONES[clave];
              }
            });
          };
          document.head.appendChild(script);
        }
    </script>    

    <script>
/*        const navLinks = document.querySelectorAll('.nav-item a');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        navLinks.forEach(link => {
          link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
              navbarCollapse.classList.remove('show');
            }
          });
        }); */
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>