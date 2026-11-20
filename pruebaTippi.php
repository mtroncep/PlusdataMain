<html>
  <head>
    <title>Tippy</title>
  </head>
  <body>
    <p class="item-misa" data-id="lectura-evangelio">Lectura del Evangelio...</p>
    <p class="item-misa" data-id="lectura-evangelio1">Lectura del Evangelio1...</p>
    <p class="item-misa" data-id="lectura-evangelio2">Lectura del Evangelio2...</p>
    <div id="custom-menu" style="display:none; position:absolute; background:#eee; padding:10px;">
      <button id="add-comment">Agregar comentario</button>
    </div>
    <script>
        const menu = document.getElementById('custom-menu');
        let currentElement = null;

        document.addEventListener('contextmenu', e => {
          if (e.target.classList.contains('item-misa')) {
            e.preventDefault();
            currentElement = e.target;
            const menu = document.getElementById('custom-menu');
            menu.style.left = `${e.pageX}px`;
            menu.style.top = `${e.pageY}px`;
            menu.style.display = 'block';
          } else {
            document.getElementById('custom-menu').style.display = 'none';
          }
        });

        document.addEventListener('click', function () {
          menu.style.display = 'none';
        });

        document.getElementById('add-comment').addEventListener('click', () => {
          const id = currentElement.dataset.id;
          console.log(id);
          const comentario = prompt('Escribí tu comentario:');
          if (comentario) {
              const nota = document.createElement('div');
              nota.style.background = '#ffeeba';
              nota.style.marginTop = '5px';
              nota.style.padding = '4px';
              nota.textContent = `📝 ${comentario}`;
              currentElement.appendChild(nota);
//              nota.style.display = 'none';
          }
          document.getElementById('custom-menu').style.display = 'none';
        });
    </script>
</body>
</html>