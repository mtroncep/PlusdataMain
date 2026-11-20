<!doctype html>
<?php
	session_start();

    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    if (isset($_REQUEST['laBusqueda'])){
        $nroBusqueda=$_REQUEST['laBusqueda'];
    } else {
        return;
    }

    $select = "select * from busquedasguardadas where nrobusqueda = ".$nroBusqueda;
    $query = mysqli_query($con,$select);
    $cantAvisos = mysqli_num_rows($query);
    if ($cantAvisos == 0){
			$alertStr="<script>alert('Anuncio no existe: ".$nroanuncio."');</script>";
			echo $alertStr;
            return;
//            echo ('<script> window.location.href = "index.php"; </script>');
    }
    $row = mysqli_fetch_array($query);
//    $_POST = json_decode($row['criteriobusqueda']);

//    $datosAnuncio=Funciones::armarDatosAnuncio($row['datosasociados'], json_decode($row['criteriobusqueda'], true), $con);
    $datosAnuncio=Funciones::armarDatosAnuncio($row['datosasociados'], $row['criteriobusqueda'], $con);
    $anuncioVistaPrevia=$nroBusqueda."#".$row['anunciante'].'#'.$datosAnuncio.'|';
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

    <script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script>
    <script src="trad/traductor.js"></script>

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
    .enmascarar { 
        border: 10px solid blue;
        border-radius: 1em;
        background-color: #bfffff;
        margin-bottom: 20px;
        }
        
    .titulo {
        text-align: center;
        font-size: 30px;
        font-weight: 800;
        }

    .nroBusqueda {
        text-align: center;
        font-size: 20px;
        }
    </style>

    </head>

<body>
    <a href="#"> <img src="imagenes/logoPlusdataSinFondo.png" width=10% title="Pagina inicio"/></a>

    <a href="indexBusquedas.php">
    <img src="imagenes/flechaizquierdabusquedas.png" width="30" height="30" title="Volver"></img></a>

        
<!--        <input class="alert-success" onClick="window.location.href='indexBusquedas.php'" type="button" value="Volver"></input> -->
<!--        <input class="alert-success" onClick="window.location.href='crearAnuncioPDF.php'" type="button" value="Reporte"></input> -->
        
        <?php
        if (!empty($anuncioVistaPrevia)){
            error_log("++++++++++++++++ INGRESO A PROCESAR EL ANUNCIO",0);
            $listaAnuncios = explode("|", $anuncioVistaPrevia);
            foreach ($listaAnuncios as $unAnuncio) {
                if (empty($unAnuncio)){
                    continue;
                } else {?>
                <div class="container" style="padding-bottom: 800px;">
                <?php
                    $anunId=0;
                    $elAnuncio=explode("#",$unAnuncio);
                    $anuncio=$elAnuncio[0];
                    $anunciante=$elAnuncio[1];?>
                    <?php
                    $contenido="<div data-trad='CRITERIOS DE BÚSQUEDA' class='titulo'>CRITERIOS DE BÚSQUEDA</div>";
                    $contenido.="<div class='nroBusqueda'><span data-trad='BÚSQUEDA'>BÚSQUEDA</span>: ".Funciones::formato_numero($anuncio, 6, 3)."</div><br>".$elAnuncio[2];

                    $volver_a="procesarVistaBusquedaGuardada.php¿laBusqueda=".$anuncio;
                    $datosPDF[1]=$contenido;
                    $_SESSION['contenido']=$datosPDF;
                    echo ('<div class="col-6 enmascarar">'.$contenido.
                          '</div>');  ?>
                    </div>
                    <?php
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