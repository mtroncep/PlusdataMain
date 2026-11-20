<!doctype html>
<?php
	session_start();

    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    if (isset($_SESSION['seleccionados'])){
		$seleccion = $_SESSION['seleccionados'];
    }
    $volver_a=$_SESSION['volver_a']="procesarSeleccionados_new.php";

//    $tope = count($seleccion);
//    echo ('Contador seleccionados: '.$tope.'<br>');
//    echo('Seleccionados (new): '.$seleccion);
//    return;
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Propiedades seleccionadas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css_new/bootstrap.min.css">
    <link rel="stylesheet" href="css_new/magnific-popup.css">
    <link rel="stylesheet" href="css_new/jquery-ui.css">
    <link rel="stylesheet" href="css_new/owl.carousel.min.css">
    <link rel="stylesheet" href="css_new/owl.theme.default.min.css">
    <!-- <link rel="stylesheet" href="css_new/bootstrap-datepicker.css"> -->
    <link rel="stylesheet" href="css_new/mediaelementplayer.css">
    <link rel="stylesheet" href="css_new/animate.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css_new/fl-bigmug-line.css">

    <!-- <link rel="stylesheet" href="css_new/aos.css"> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css_new/style.css">

    <script src="trad/traducciones.php"></script>
    <script src="trad/traductor.js"></script>

    <script type="text/javascript">
        function mostrarDatos(id, nroanuncio, anunciante) {
            <?php if (isset($_SESSION['anunciante'])){ ?>
                var anuncianteActivo = <?php echo $_SESSION['anunciante']; ?>;
                <?php } else {?>
                var anuncianteActivo=0;
                <?php } ?>
            
            var anunciante_id = "anunciante_id_"+id;
            if (document.getElementById(anunciante_id)){
                const elemento = document.getElementById(anunciante_id);
                if (elemento.style.display === 'inline-block'){
                    return;
                }
            }

            var anunciante_id = "anunciante_id_"+id;
            if (document.getElementById(anunciante_id)){
                var divAnun = document.getElementById(anunciante_id);
                divAnun.style.display = "inline-block";
            }

            var telefono_id = "telefono_id_"+id;
            if (document.getElementById(telefono_id)){
                var divTele = document.getElementById(telefono_id);
                divTele.style.display = "inline-block";
            }
        
            var email_id = "email_id_"+id;
            if (document.getElementById(email_id)){
                var divMail = document.getElementById(email_id);
                divMail.style.display = "inline-block";
            }

            var anuncio_id = "anuncio_id_"+id;
            if (document.getElementById(anuncio_id)){
                var divAnuncio = document.getElementById(anuncio_id);
                divAnuncio.style.display = "inline-block";
            }
            
            var banner_id = "banner_id_"+id;
            if (document.getElementById(banner_id)){
                const elemento = document.getElementById(banner_id);
                if (elemento.style.display === 'inline-block'){
                    return;
                }
            }
            var banner_id = "banner_id_"+id;
            if (document.getElementById(banner_id)){
                var divBanner = document.getElementById(banner_id);
                divBanner.style.display = "inline-block";
            }
//            alert("Anuncio: "+nroanuncio);
            if (anunciante !== anuncianteActivo){
                $.ajax({
                  url: 'actualizarDatosAnuncios.php',
                  type: 'POST',
                  data: {
                    operacion: 'CON',
                    nroanuncio: nroanuncio
                  },
                  success: function(response) {
                    console.log('Éxito:', response);
                  },
                  error: function(xhr, status, error) {
                    console.error('Error:', error);
                  }
                });
            }
        }
        
        function acumSeleccionado(nroanuncio, anunciante) {
            <?php if (isset($_SESSION['anunciante'])){ ?>
                var anuncianteActivo = <?php echo $_SESSION['anunciante']; ?>;
                <?php } else {?>
                var anuncianteActivo=0;
                <?php } ?>
            
            if (anunciante !== anuncianteActivo){
                $.ajax({
                  url: 'actualizarDatosAnuncios.php',
                  type: 'POST',
                  data: {
                    operacion: 'SEL',
                    nroanuncio: nroanuncio
                  },
                  success: function(response) {
                    console.log('Éxito:', response);
                  },
                  error: function(xhr, status, error) {
                    console.error('Error:', error);
                  }
                });
            }
        }

    </script>
    
<style>
.verDatosLink {
    color: blue;
    text-decoration: underline;
    cursor: pointer;
}

    .tituloFijar {
      position: sticky; /* o fixed */
      top: 0;
      width: 100%;
      background-color: white;
      padding: 10px;
      z-index: 100;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .contenedor-imagenes {
        width: 100%;
    }
        
    .img-superior {
        width: 100%;
        display: block;
        height: auto;
    }

    .banner-inferior {
        width: 100%;
        object-fit: cover;
        display: block;
    }

    .property-thumbnail,
    #banner_id_0 {
        width: 100%;
    }

    .property-thumbnail img,
    #banner_id_0 img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

</style>

</head>

<body>
    <div class="container">
        <div class="tituloFijar">
            <input data-trad="Nueva búsqueda" class="btn btn-warning" onClick="window.location.href='cargarDetallesAwait.php?operacion=B';" type="button" value="Nueva búsqueda"></input>
            <?php
                if ($_SESSION['cantPublicaciones'] === 0){ ?>
                    <input data-trad="Guardar búsqueda" class="alert-success" onClick='window.location.href="guardarBusqueda.php";' type="button" value="Guardar búsqueda"></input>
                <?php } ?>
<!--            <div class="row mb-2"></div> -->
            <div align="center"> 
                <h2 data-trad="Cantidad de publicaciones">Cantidad de publicaciones </h2>
                <h2><?php echo($_SESSION['cantPublicaciones']);?></h2>
            </div>
        </div>
        <div class="row mb-5">
        <?php
        $anunId = 0;
        $_SESSION['windowCerrar'] = 1;
        if (!empty($seleccion)){
            $listaAnuncios = explode("|", $seleccion);
            foreach ($listaAnuncios as $unAnuncio) {
                if (empty($unAnuncio)){
                    continue;
                } else {
                    $anunId++;
                    $elAnuncio=explode("#",$unAnuncio);
                    $anuncio=$elAnuncio[0];
                    $anunciante=$elAnuncio[1];?>
                    <script>
                        acumSeleccionado(<?php echo $anuncio ?>, <?php echo $anunciante ?>);
                    </script>
                    <?php
                    $contenido="";
                    if (isset($_SESSION['busquedaPublica']) && $_SESSION['busquedaPublica'] == 1){
                        $contenido='<a>REGISTRARSE PARA CONTACTAR</a>';
                        $contenido.= "<br>".$elAnuncio[2];
                    }{
                        $datosContacto[] = '';
                        $datosContacto = Funciones::obtenerDatosContacto($anunciante, $con);
                        $tipoDatoContacto = $datosContacto[0];
                        if ($datosContacto[0] === 'T'){
                        $contenido='<div class="p-4 property-body d-flex align-items-center gap-4">
                                        <b><span data-trad="ANUNCIANTE">ANUNCIANTE:<p> </p></span></b>
                                            <a class="verDatosLink" onclick="mostrarDatos('.$anunId.','.$anuncio.','.$anunciante.')">
                                                <span data-trad="Ver datos">Ver datos</span>&nbsp;&nbsp;
                                            </a>
                                    </div>';

                        $contenido.="<div id=anunciante_id_".$anunId." style='display: none;'>".$datosContacto[1]."</div><br>";
                        $contenido.="<div id=telefono_id_".$anunId." style='display: none;'>"." TE: ".$datosContacto[2]."</div><br>";
                        $contenido.="<div id=email_id_".$anunId." style='display: none;'>"." Email: ".$datosContacto[3]."</div><br>";
                        $contenido.="<div id=anuncio_id_".$anunId." style='display: none;'>".
                            "<span data-trad='ANUNCIO'>ANUNCIO</span> ".Funciones::formato_numero($anuncio, 8, 4)."</div><br>";
                    } else {
                        $contenido='<div class="p-4 property-body d-flex align-items-center gap-4">
                                        <b><span data-trad="ANUNCIANTE">ANUNCIANTE:</span></b>&nbsp;&nbsp;
                                            <a class="verDatosLink" onclick="mostrarDatos('.$anunId.','.$anuncio.','.$anunciante.')">
                                                <span data-trad="Ver datos">Ver datos</span>
                                            </a>
                                    </div>';

                        $contenido.= "<div id=banner_id_".$anunId." style=display:none;".">";
                        $contenido.= '<img id="bannerInferior" src='.$datosContacto[1].' alt="Banner" class="banner-inferior" style="border: 2px solid #000000;">';
                        $contenido.= "</div>";
                        $contenido.="<div id=anuncio_id_".$anunId." style='display: none;'>".
                            "<span data-trad='ANUNCIO'>ANUNCIO</span> ".Funciones::formato_numero($anuncio, 8, 4)."</div><br>";
                    }
                    $contenido.=$elAnuncio[2];
//                    $contenido.= $elAnuncio[2];
                    if (!isset($_SESSION['codPais'])){
                        $_SESSION['codPais']=410;
                    }
                    
                    $imagen = Funciones::obtenerImgAnuncio($_SESSION['codPais'],$anuncio,1,$con);
                    echo ('<div class="col-md-6 col-lg-4 mb-4">
                            <div class="property-entry h-100">
                                <a target="_blank"
                                   href="indexCarouselMarca.php?pais='.$_SESSION['codPais'].'&elAnuncio='.$anuncio.'&noBorrar=1&volver_a='.$volver_a.'"
                                   class="property-thumbnail">
                                    <div class="offer-type-wrap">
                                        <span class="offer-type bg-danger">Compra-Venta</span>
                                    </div>
                                    <div class="property-thumbnail contenedor-imagenes">
                                        <img id="imgSuperior" src="'.$imagen.'" alt="Image" class="img-superior">
                                    </div>
                                </a>
                                '.$contenido.'
                            </div>
                        </div>'); 
                    }
                }   
            }
        } else {
            echo "SIN RESULTADOS";
        }
        ?>
        </div>
    </div>

    <script>
         function traducir(texto) {
          if (window.TRADUCCIONES?.[texto]) {
            return window.TRADUCCIONES[texto];
          }
          return texto;
        }

        function aplicarTraducciones() {
            document.querySelectorAll('[data-trad]').forEach(el => {
              const clave = el.getAttribute('data-trad');
            // Si es un input con placeholder
            if (el.hasAttribute('placeholder')) {
              el.setAttribute('placeholder', traducir(clave));
            } else {
              // Para otros elementos, traducimos el texto interno
              el.innerText = traducir(clave);
            }
            });
          }

          // Ejecutamos al cargar la página
        window.onload = aplicarTraducciones();
    </script>
</body>
</html>