// JavaScript Document
function asignarMensajes(){
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
//-----------------------
}
asignarMensajes();

/*
function asignarManejoComentarios(){
    const menu = document.getElementById('menuContextual');
    let labelActual = null;

    document.addEventListener('contextmenu', function (e) {
      const label = e.target.closest('label.custom-label');
      if (label) {
        e.preventDefault();
        labelActual = label;
//        console.log("For: "+labelActual.getAttribute('for'));
        // Mostramos el menÃƒÆ’Ã‚Âº contextual personalizado
        menu.style.top = `${e.pageY}px`;
        menu.style.left = `${e.pageX}px`;
        menu.style.display = 'block';
      } else {
        menu.style.display = 'none';
      }
    });

    document.addEventListener('click', () => {
      menu.style.display = 'none';
    });

    document.getElementById('agregarComentario').addEventListener('click', function () {
//          console.log("Detecte el click en el item del menu!!!");
      if (labelActual) {
        const valorFor = labelActual.getAttribute('for');
        const valorIdLabel = "label_"+valorFor;
        const label = document.getElementById(valorIdLabel);
        const small = label.querySelector('small');
        const contenidoSmall = small.textContent;
        var   comentario = '';
        var comentario = prompt(`Comentario para "${contenidoSmall}":`);

        const nota = document.createElement('div');
        nota.style.paddingLeft = '3px';
        nota.textContent = '📝';
        nota.dataset.comentario = comentario;
        labelActual.appendChild(nota);
        document.getElementById(valorFor).checked = true;
//        nota.click;

        // Evento para editar el comentario con textarea
        nota.addEventListener('click', (e) => {
          e.stopPropagation(); // Evita que el click se propague y cierre el editor enseguida

          // Evita duplicar editores si ya hay uno abierto
          if (document.getElementById('editor-comentario')) return;

          const editor = document.createElement('div');
          editor.id = 'editor-comentario';
          editor.style.position = 'absolute';
          editor.style.background = '#fff';
          editor.style.border = '1px solid #ccc';
          editor.style.padding = '8px';
          editor.style.zIndex = 1000;
          editor.style.boxShadow = '0px 2px 10px rgba(0,0,0,0.2)';

          const rect = nota.getBoundingClientRect();
          editor.style.left = `${rect.left + window.scrollX}px`;
          editor.style.top = `${rect.bottom + window.scrollY + 5}px`;

          const textarea = document.createElement('textarea');
          textarea.value = nota.dataset.comentario || '';
          textarea.rows = 4;
          textarea.cols = 30;

          const boton = document.createElement('button');
          boton.textContent = 'Guardar';
          boton.addEventListener('click', () => {
            const nuevoComentario = textarea.value.trim();
            if (nuevoComentario) {
              nota.dataset.comentario = nuevoComentario;
            }
            editor.remove();
            grabarComentariosAnuncianteTemporal(valorFor,nuevoComentario);
          });

          editor.appendChild(textarea);
          editor.appendChild(document.createElement('br'));
          editor.appendChild(boton);
          document.body.appendChild(editor);

          // Detectar clic fuera del editor para cerrarlo
          const handleOutsideClick = (e) => {
            if (!editor.contains(e.target)) {
              editor.remove();
              document.removeEventListener('click', handleOutsideClick);
            }
          };
          setTimeout(() => {
            document.addEventListener('click', handleOutsideClick);
          }, 0);
        });
          //Para grabar los comentarios en la base de datos
          grabarComentariosAnuncianteTemporal(valorFor,comentario);
      }
    });
}
    asignarManejoComentarios();

function grabarComentariosAnuncianteTemporal(valorFor, comentario){
      fetch('ajax/actualizaMensajeAnuncioTemporal.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          idFor: valorFor, 
          comentario: comentario
          })
        })
        .then(res => res.ok ? console.log('Guardado OK') : Promise.reject())
        .catch(err => console.error('Error al guardar:', err));
}
*/
