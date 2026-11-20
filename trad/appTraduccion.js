// JavaScript Document
function traducirTexto(texto) {
    if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
    }
        return texto;
}
        
function aplicarTraduccionesTexto() {
  const atributos = ["placeholder", "title", "alt", "aria-label", "value"];

  document.querySelectorAll("[data-trad]").forEach(el => {
    const clave = el.dataset.trad;
    const texto = traducirTexto(clave);

    atributos
      .filter(attr => el.hasAttribute(attr))
      .forEach(attr => el.setAttribute(attr, texto));

    ["INPUT", "TEXTAREA"].includes(el.tagName) ||
      (el.innerHTML = texto);
  });
}

window.onload = aplicarTraduccionesTexto;
