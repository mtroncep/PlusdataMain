<?php
    session_start();
    require_once "conexion.php";
    require_once("cargarDatosFormulario.php");

    $email   = $_POST['email'];
    $codigo  = $_POST['codigo'];
    $vuelta  = $_POST['vuelta'];
    ?>
    <script src="trad/traducciones.php"></script>
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
    <?php
// Verificar código para E-Mail...
//
    $validacionEmail=0;
    $res = $con->query("select * from anunciantes 
        where email='$email' and codigo2fa='$codigo'") or die($con->error);
    if( mysqli_num_rows($res) > 0 ){
        $validacionEmail=1;
    }else{
        echo "codigo invalido";
    }

    if ($vuelta === 'IN'){
        $menuinicio=$_SESSION['menuinicio'];
    } else {
        if ($vuelta === 'CP'){
            $menuinicio="tomarCambioConrasena.php?email=$email";
            error_log("E-Mail verificar2FA: ".$menuinicio,0);
        }
    }

    if ($validacionEmail) {
        if ($vuelta === 'IN'){
            echo '<script>alert(traducir("Bienvenido"));</script>';
            session_regenerate_id(true);
            echo ('<script>'."window.location.href = '$menuinicio'</script>");
        } else {
            echo ('<script>'."window.location.href = '$menuinicio'</script>");
        }
    } else {
        echo 'Se produjo un error en la verificación. ¡Intentelo de nuevo! <a href="index.php">Inicio</a>';
    }
?>
    <script src="trad/appTraduccion.js"</script>

