<!doctype html>
<?php
	session_start();
?>

    <script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script>

<?php
    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    if (isset($_REQUEST['paisproceso'])){
        $_SESSION['codPais'] = $_REQUEST['paisproceso'];
    } else {
        if (!isset($_SESSION['codPais'])){
            $_SESSION['codPais'] = 410;
        }
    }

    if (isset($_REQUEST['elAnuncio'])){
        if ($_REQUEST['elAnuncio'] <> ""){
            $nroanuncio=$_REQUEST['elAnuncio'];
        } else {
            echo ("<script>alert('Falta informar el número de aviso.');</script>");
            echo ('<script> closeCurrentWindow(); </script>');
        }
    } else {
        return;
    }

    if (isset($nroanuncio) <> "") {
        $select = "select nroanunciante, datosasociados from datosbusquedasformulario where estado = 1 and nroanuncio = ".$nroanuncio;
        $query = mysqli_query($con,$select);
        $cantAvisos = mysqli_num_rows($query);
        if ($cantAvisos === 0){
            echo ('<script> closeCurrentWindow(); </script>');
        } else {
            $row = mysqli_fetch_array($query);
            $datosAsociados="";
            if (isset( $_REQUEST['datosAsociados']) ){
                $datosAsociados=$_REQUEST['datosAsociados'];
            }
            $datosAnuncio=armarDatosAnuncio($row['datosasociados'], $datosAsociados);
            $anuncioVistaPrevia=$nroanuncio."#".$row['nroanunciante'].'#'.$datosAnuncio.'|';
//            echo $row['datosasociados'];
        }
    }

function  armarDatosAnuncio($datos, $POST){
    $items = explode("|",$datos);
    $datosMostrar='';
    $coma='';
    $sector='';
    $pos_ubic_ant=0;
    $pos_01_ant=0;
    $pos_02_ant=0;
    $pos_02c_ant=0;
    $pos_03_ant=0;
    $pos_04_ant=0;
    $pos_05_ant=0;
    $pos_06_ant=0;
    $pos_07_ant=0;
    $pos_06A_ant=0;
    $pos_08_ant=0;
    $pos_09_ant=0;
    $pos_10_ant=0;
    $pos_11_ant=0;
    $pos_13_ant=0;
    $pos_09=0;
    
    $secLab=0;
    
    $no_mostrar_precio = Funciones::buscarItemEnFormulario("checkbox_02A_precio_oculto",$datos);

    foreach($items as $item){
        $claveValor=explode(",",$item);
//        var_dump($claveValor);

        $pos_01 = strpos($claveValor[0], "_01_");
        if ($pos_01 <> 0){
            if ($pos_01_ant == 0){
                $datosMostrar.="<b><span data-trad="."Operación".">Operación".":</span></b> ";
                $pos_01_ant = $pos_01;
            }
        }

        $pos_ubic = strpos($claveValor[0], "bic_");
        if ($pos_ubic <> 0){
            if ($pos_ubic_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Ubicación".">Ubicación:</span></b> ";
                $pos_ubic_ant = $pos_ubic;
                $coma="";
            }
        }

        $pos_03 = strpos($claveValor[0], "_03_");
        if ($pos_03 <> 0){
            if ($pos_03_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Estar".">Estar:</span></b> ";
                $pos_03_ant = $pos_03;
                $coma="";
            }
        }

        $pos_04 = strpos($claveValor[0], "_04_");
        if ($pos_04 <> 0){
            if ($pos_04_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Baño y Cocina"."'>Baño y Cocina:</span></b> ";
                $pos_04_ant = $pos_04;
                $coma="";
            }
        }

        $pos_05 = strpos($claveValor[0], "_05_");
        if ($pos_05 <> 0){
            if ($pos_05_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Otras dependencias"."'>Otras dependencias:</span></b> ";
                $pos_05_ant = $pos_05;
                $coma="";
            }
        }

        $pos_06 = strpos($claveValor[0], "_06_");
        if ($pos_06 <> 0){
            if ($pos_06_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Detalles".">Detalles:</span></b> ";
                $pos_06_ant = $pos_06;
                $coma="";
            }
        }

        $pos_06A = strpos($claveValor[0], "_06A_");
        if ($pos_06A <> 0){
            if ($pos_06A_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Orientación".">Orientación:</span></b> ";
                $pos_06A_ant = $pos_06A;
                $coma="";
            }
        }

        $pos_07 = strpos($claveValor[0], "_07_");
        if ($pos_07 <> 0){
            if ($pos_07_ant == 0){
                $datosMostrar.="<br><b><span data-trad="."Servicios".">Servicios:</span></b> ";
                $pos_07_ant = $pos_07;
                $coma="";
            }
        }

        $pos_08 = strpos($claveValor[0], "_08_");
        if ($pos_08 <> 0){
            if ($pos_08_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Estado del inmueble"."'>Estado del inmueble:</span></b> ";
                $pos_08_ant = $pos_08;
                $coma="";
            }
        }

        $pos_09 = strpos($claveValor[0], "_09_");
        if ($pos_09 <> 0){
            if ($pos_09_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Servicios regionales"."'>Servicios regionales:</span></b> ";
                $pos_09_ant = $pos_09;
                $coma="";
            }
        }

        $pos_10 = strpos($claveValor[0], "_10_");
        if ($pos_10 <> 0){
            if ($pos_10_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Situación legal"."'>Situación legal:</span></b> ";
                $pos_10_ant = $pos_10;
                $coma="";
            }
        }

        $pos_11 = strpos($claveValor[0], "_11_");
        if ($pos_11 <> 0){
            if ($pos_11_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Equipamiento rural"."'>Equipamiento rural:</span></b> ";
                $pos_11_ant = $pos_11;
                $coma="";
            }
        }

        $pos_13 = strpos($claveValor[0], "_13_");
        if ($pos_13 <> 0){
            if ($pos_13_ant == 0){
                $datosMostrar.="<br><b><span data-trad='"."Datos adicionales"."'>Datos adicionales:</span></b> ";
                $pos_13_ant = $pos_13;
                $coma="";
            }
        }

        if (count($claveValor) > 1){
        if ($claveValor[1] === 'v'){
            if ($claveValor[0] <> 'valor_checkbox_09_32'){
                if (strpos($claveValor[2],".") === false){
                    $claveValor[2] = number_format($claveValor[2],0);
                } else {
                    $claveValor[2] = number_format($claveValor[2],2,',');
                }
                }
            }
            if ($claveValor[0] === 'ubicd_calle_y_numero_id'){
                $datosMostrar.=$coma."<br><span data-trad="."Calle".">Calle:</span> ".$claveValor[2];
                $coma=' - ';
            } else {
                if ($claveValor[0] === 'ubicd_planta_id') {
                    $datosMostrar.=$coma." "."<span data-trad=Planta".">Planta:</span> ".$claveValor[2];
                    $coma=' - ';
                } else {
                    if ($claveValor[0] === 'ubicd_numero_id') {
                    } else {
                    if ($claveValor[0] === 'valor_checkbox_02A_precio' || $claveValor[0] === 'valor_checkbox_02A_1'){
                        if($no_mostrar_precio === 0){
                            $datosMostrar.=": ".$claveValor[2];
                            $coma=' - ';
                        } else {
                            $datosMostrar.=$coma."<b><span data-trad=Consultar>"."Consultar</span></b>";
                        }
                        } else {
                            if ($no_mostrar_precio === 1 && $claveValor[2] === "Publicar con precio oculto"){
                            } else {
                                if ($claveValor[0] === "ubic_pais_id" || $claveValor[0] === "ubic_region_id" || $claveValor[0] ===   "ubic_provincia_id" || $claveValor[0] === "ubic_comarca_id" || $claveValor[0] === "ubic_municipio_id") {
                                } else {
                                    if ($claveValor[1] === 'v'){
                                        $coma=": ";
                                    }   if ($claveValor[1] === 'v' && strpos($claveValor[2], ".") !== false){
                                            $texto = str_replace(".",",",$claveValor[2]);
                                            $coma=": ";
    //                                        $texto = number_format($claveValor[2], 2, ',', '.');
                                            $datosMostrar .= $coma . "<span>" . $texto . "</span>";
                                        } else {
                                            if ($claveValor[1] === 'v'){
                                                $texto=$claveValor[2];
                                                $datosMostrar .= $coma . "<span>" . $texto . "</span>";
                                            } else {
                                                $textoOriginal = $claveValor[2];
                                                $textoHTML = htmlspecialchars($textoOriginal, ENT_QUOTES, 'UTF-8');
//                                                echo "Con apostrofe HTML: ".$textoHTML."<br>";
    
                                                if (strpos($claveValor[2],"'") === false ){
                                                    $datosMostrar .= $coma .
                                                        '<span data-trad="' . $textoOriginal . '">' . $textoHTML . '</span>';    
                                                } else {
                                                    $datosMostrar .= $coma .
                                                        '<span>' .  $textoOriginal . '</span>';    
                                                }
                                                $coma=' - ';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                }}}
        }
    // Fin foreach
    return $datosMostrar;
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Vista Previa de Publicación</title>
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

    <script type="text/javascript">
      function closeCurrentWindow() {
        window.close();
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
/*
    #banner_id_0 {
        display: block !important;
    }
*/        
    </style>

    <style>
    .banner-zoom {
        position: relative;
        width: 340px;   /* tu tamaño normal */
        height: 100px;
        overflow: visible; /* deja que la imagen salga del marco */
    }

    .banner-zoom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        position: relative;
        z-index: 1; /* nivel normal */
        transform-origin: center center;
    }
        
    .banner-zoom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        position: relative;
        z-index: 1; /* nivel normal */
        transform-origin: center center;
    }
        
    .banner-zoom:hover img {
        transform: scale(2.0); /* zoom al 160% */
        z-index: 999; /* se pone arriba de todo */
    }
    </style>
    
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
                    console.log('Éxito 1:', response);
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
                    console.log('Éxito 2:', response);
                  },
                  error: function(xhr, status, error) {
                    console.error('Error:', error);
                  }
                });
            }
        }
    </script>
    </head>

<body>
    <div class="container">
<!--        <div class="tituloFijar"> -->
        <div>
            <div align="center">
                <div>
                    <h2>
                        <span data-trad="Consulta por Referencia">Consulta por Referencia
                        </span>
                    </h2>
                </div>
                
                <div>
                    <input data-trad="Volver" class="btn btn-warning" type="button" value="Volver" onClick="closeCurrentWindow()";>
                    </input>
                </div>
<!--
                <span data-trad="Volver"
                      style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                             pointer-events: none; color: black;">
                    Volver
                </span>
-->
            </div>
        </div>
        <?php
        if (!empty($anuncioVistaPrevia)){
            $_SESSION['windowCerrar'] = 1;
            $listaAnuncios = explode("|", $anuncioVistaPrevia);
            foreach ($listaAnuncios as $unAnuncio) {
                if (empty($unAnuncio)){
                    continue;
                } else {
                    $anunId=0;
                    $elAnuncio=explode("#",$unAnuncio);
                    $anuncio=$elAnuncio[0];
                    $anunciante=$elAnuncio[1];?>
                    <script>
                        acumSeleccionado(<?php echo $anuncio ?>, <?php echo $anunciante ?>);
                    </script>
                    <?php

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

                        $contenido.= "<div class='banner-zoom' id=banner_id_".$anunId." style=display:none;".">";
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
                    $volver_a="procesarVistaPreviaAnuncio.php¿elAnuncio=".$anuncio;
                    $datosPDF[0]=$imagen;
                    $datosPDF[1]=$contenido;
                    $_SESSION['contenido']=$datosPDF;
                    echo '<div class="col-md-6 col-lg-4 mb-4">
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
                        </div>';                }
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
            const elementos = document.querySelectorAll('[data-trad]');

            elementos.forEach(el => {
                const clave = el.getAttribute('data-trad');
                if (clave.indexOf("'") !== -1) 
                { /* Caracter encontrado */ 
                    el.textContent = clave;
                } else {
                    const traduccion = traducir(clave);

                    if (el.hasAttribute('placeholder')) {
                        el.setAttribute('placeholder', traduccion);
                    } else {
                        el.textContent = traduccion;
                    }
                }
            });
        }

        window.addEventListener('load', aplicarTraducciones);
    </script>

<script>

function ajustarBanner() {
    const imgSup = document.getElementById("imgSuperior");
    const banner = document.getElementById("bannerInferior");

    if (!imgSup || !banner) return;

    // Esperar a que la imagen superior cargue
    if (!imgSup.complete || imgSup.naturalHeight === 0) {
        imgSup.onload = ajustarBanner;
        return;
    }

    // Medidas reales en pantalla
    const altoSuperior = imgSup.clientHeight;

    // Cálculo del banner
    const altoBanner = altoSuperior / 3;

    // Aplicar dimensiones SIN romper el responsive
    banner.style.height = altoBanner + "px";
    banner.style.objectFit = "cover";
}
    // Ejecutar al cargar la página
//    window.addEventListener("load", ajustarBanner);

    // Ejecutar también cuando el banner aparece dinámicamente
//    const observer = new MutationObserver(ajustarBanner);
//    observer.observe(document.body, { childList: true, subtree: true });
</script>

</body>

</html>