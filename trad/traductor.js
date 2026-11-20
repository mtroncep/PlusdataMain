function traducir(texto) {
    if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
    }
    return texto;
}

function aplicarTraducciones() {
    document.querySelectorAll('[data-trad]').forEach(el => {
        const clave = el.getAttribute('data-trad');

        if (el.hasAttribute('placeholder')) {
            el.setAttribute('placeholder', traducir(clave));
        } else {
            if (el.value !== undefined && el.tagName === 'INPUT') 
            {
                el.value = traducir(clave);
            } else {
                el.innerText = traducir(clave);
        }}
    });
}
    
window.addEventListener('DOMContentLoaded', aplicarTraducciones);