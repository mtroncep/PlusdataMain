<?php
session_start();
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$email=$_GET['email'];
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link rel="stylesheet" href="css/cambioClave.css">
    
<script src="trad/traducciones.php"></script>

</head>

<body>
<div class="container">
    <h2><div align="center" data-trad="Cambiar/Restaurar clave de acceso">Cambiar/Restaurar clave de acceso</div></h2>
    <div class="regisFrm">
        <form action="cambiarClave.php" method="post">
            <input type="password" name="password" placeholder="Nueva contraseña" data-trad="Nueva contraseña" required="">
            <input type="password" name="confirm_password" placeholder="Confirmar" data-trad="Confirmar" required="">
            <div class="send-button">
                <input type="submit" name="resetSubmit"  data-trad="Cambiar contraseña" value="Cambiar contraseña">
            </div>
        </form>
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
          el.value = traducir(clave);
        }
        });
      }

      // Ejecutamos al cargar la página
    window.onload = aplicarTraducciones();
</script>

</body>
</html>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>