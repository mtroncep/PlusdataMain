<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link rel="stylesheet" href="css/cambioClave.css">

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

</head>
<body>
    <div class="container">
        <h2><div align="center" data-trad="Ingrese el correo electrónico para restablecer su clave">Ingrese el correo electrónico para restablecer su clave</div></h2>

        <div class="regisFrm">
            <form action="userAccount.php" method="POST">
                <input type="email" name="email" placeholder="E-Mail" required="">
                <div class="send-button">
                    <input type="submit" name="forgotSubmit" data-trad="Continúe" value="Continúe">
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
    <script src="trad/appTraduccion.js"></script>
