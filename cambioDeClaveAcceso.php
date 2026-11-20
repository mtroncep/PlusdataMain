<?php
session_start();

$_SESSION['mailOlvidoClave'] = $_SESSION['emailReg'];
$_SESSION['tipoCambioClave'] = "cambio";
$menuinicio = $_SESSION['menuinicio'];
    
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link rel="stylesheet" href="css/cambioClave.css">

<script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

</head>

<body>
<div class="container">
    <div align="center"><h2 data-trad="Cambiar o Restaurar clave de acceso">Cambiar o Restaurar clave de acceso</h2></div>
    <div class="regisFrm">
        <form id="formCambioPass" method="post" action="cambiarClave.php">
            <input type="password" id="password_actual" name="password_actual" data-trad="Clave actual" placeholder="Clave actual" required>
            <span id="mensaje" style="color: red;"></span><br>
            <div class="grupo-password">
<!--                <label for="password">Clave nueva</label> -->
                <input
                    type="password"
                    id="password"
                    name="password"
                    maxlength="20"
                    autocomplete="off"
                    data-trad="Clave nueva"
                    placeholder="Clave nueva">
                <div id="password-rules" style="display:none;">
                    <div id="largo" class="error" style="font-size: 12px;">
                        ❌ Mínimo 8 caracteres
                    </div>
                    <div id="mayus" class="error" style="font-size: 12px;">
                        ❌ Al menos una letra mayúscula
                    </div>
                    <div id="minus" class="error" style="font-size: 12px;">
                        ❌ Al menos una letra minúscula
                    </div>
                    <div id="numero" class="error" style="font-size: 12px;">
                        ❌ Al menos un número
                    </div>
                    <div id="simbolo" class="error" style="font-size: 12px;">
                        ❌ Al menos un símbolo
                    </div>
                </div>
            </div>                    

            
            
            <input type="password" id="confirm_password" name="confirm_password" data-trad="Confirmar clave" placeholder="Confirmar clave" required>
            <div class="send-button">
                <input class="btn btn-warning" type="submit" name="resetSubmit" data-trad="Cambiar" value="Cambiar"></input>
            </div>
            <div class="cancelar-button">
                <input class="btn btn-warning" type="submit" name="cancelarCambio" data-trad="Cancelar" value="Cancelar" onClick="window.parent.location.href = '<?php echo($menuinicio);?>'">
            </div>
        </form>
    </div>
</div>
</body>
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
          el.innerHTML = traducir(clave);
          el.value = traducir(clave);
        }
        });
      }

      // Ejecutamos al cargar la página
    window.onload = aplicarTraducciones();
</script>

<script>
    $("#password").on("focus", function(){
        $("#password-rules").show();
    });
    
    $("#password").on("input", function(){

        let pass = $(this).val();

        validarRegla(
            pass.length >= 8,
            "#largo",
            "Mínimo 8 caracteres"
        );

        validarRegla(
            /[A-Z]/.test(pass),
            "#mayus",
            "Al menos una letra mayúscula"
        );

        validarRegla(
            /[a-z]/.test(pass),
            "#minus",
            "Al menos una letra minúscula"
        );

        validarRegla(
            /[0-9]/.test(pass),
            "#numero",
            "Al menos un número"
        );

        validarRegla(
            /[^A-Za-z0-9]/.test(pass),
            "#simbolo",
            "Al menos un símbolo"
        );

    });

function validarRegla(cumple, id, texto){

    if(cumple){

        $(id)
            .removeClass("error")
            .addClass("ok")
            .html("✔️ " + texto);

    }else{

        $(id)
            .removeClass("ok")
            .addClass("error")
            .html("❌ " + texto);

    }

}
</script>

<script type="text/javascript">
document.getElementById('password_actual').addEventListener('blur', function () {
    const claveIngresada = this.value;
    // Enviar la clave al servidor mediante fetch (AJAX)
    fetch("verificar_clave.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ clave: claveIngresada }),
    })
    .then(response => response.text())
    .then(data => {
        const mensaje = document.getElementById('mensaje');
        if (data == 'correcto'){
            mensaje.textContent = 'Contraseña actual verificada.';
            mensaje.style.color = 'green';
        } else {
        }
            mensaje.innerHTML = data;
    })
});

document.getElementById('formCambioPass').addEventListener('submit', function (event) {
    const newPassword = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (newPassword !== confirmPassword) {
        event.preventDefault();
        alert('Las contraseñas nuevas no coinciden. Inténtalo de nuevo.');
    }
});
</script>
</html>
