<body>
  <h1 data-trad="Admite fumadores"></h1>

  <!-- Carga dinámica del idioma -->
  <script src="./traduccion.php?lang=es"></script>
  <script>
    document.querySelectorAll('[data-trad]').forEach(el => {
      const clave = el.getAttribute('data-trad');
      if (window.TRADUCCIONES?.[clave]) {
        el.innerText = window.TRADUCCIONES[clave];
          console.log("clave: "+clave);
      }
    });
  </script>
</body>
