<?php
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);

session_start();
//session_destroy();

require_once("conexion.php");
require_once("cargarDatosFormulario.php");

/*
if (!isset($_SESSION['idiomapermanente'])) {
    $_SESSION['idiomapermanente'] = 'es';
}

$_SESSION['idioma'] = $_SESSION['idiomapermanente'];

//$_SESSION['idiomapermanente'] = 'es';
//$_SESSION['idioma'] = $_SESSION['idiomapermanente'];
*/
?>

<?php

$texto = "select * from paises where estado = 1 order by DESCRIPCION";
$query = mysqli_query($con, $texto);
?>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Preview - Plusdata Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>


<!--
<script>    
    window.addEventListener("pagehide", () => {
        navigator.sendBeacon("logout.php");
});
</script>
-->
<link rel="stylesheet" href="css/homeNew.css" type="text/css">

<script src="trad/traducciones.php"></script>
<script src="trad/traductor.js"></script>

<?php
    if (!isset($_SESSION['idiomapermanente'])) {
        $_SESSION['idiomapermanente'] = 'es';
    }

    $_SESSION['idioma'] = $_SESSION['idiomapermanente'];
?>

<script>
    function cambiarIdioma(idioma) {
        $.ajax({
            type: 'POST',
            url: 'cambiarIdioma.php',
            data: {
                idioma: idioma
            },
            success: function() {
                location.reload();
            }
        });
    }

const codPais = <?php echo (isset($_SESSION['codPais']) ? (int)$_SESSION['codPais'] : 0 )?>;

function abrirModal() {
    if (codPais <= 0) {
        alert("Falta seleccionar un país");
        return;
    }

    document.getElementById('emailReg').value = "";

    const miModal = new bootstrap.Modal(
        document.getElementById('registroModal')
    );

    miModal.show();
}
    
function cerrarModal() {
    document.getElementById("registroModal").style.display = "none"; // Ocultar el modal
}
    
function desactivarBoton(boton) {
  boton.disabled = true;
  boton.innerText = 'Ingresando...'; // Opcional: cambia el texto
  document.getElementById('formLogin').submit();
}

function LimpiarModal(){
    document.getElementById('MiFormulario').reset();
}
    
function verOficina(){
    document.getElementById("id_oficina").style.display = "block";
    return true;
}
    
function validarInmobiliaria(){
    var codigoInmobRef = document.getElementById("nroInmobiliaria");
    var codigoInmobVal = document.getElementById("nroInmobiliaria").value;
    var nombreInmobiliaria = document.getElementById("nombreinmob");

    if (codigoInmobVal.length === 0){
        return;
    }
    var parametros = {
        "nroInmob": codigoInmobVal
    };

    $.ajax({
        url: 'validarInmobiliaria.php',
        data: parametros,
        dataType: "json",
        beforeSend: function(objeto) {},
        success: function(data) {
            if (data.status === "0") {
//                    nombreInmobiliaria.value = data.result
                var mensajeInmob = document.getElementById('mensajeInmob');
                nombreInmobiliaria.value="";
                mensajeInmob.innerText = "❌ " + data.result;
                mensajeInmob.style.color = "red";
                mensajeInmob.style.display = "block";
                codigoInmobRef.focus();
                setTimeout(() => {
                    mensajeInmob.style.display = "none";
                    codigoInmobRef.value="";
                    nombreInmobiliaria.value="";
                }, 3000); // Oculta el mensaje después de 3 segundos
                } else {
                    nombreInmobiliaria.value=data.result;
                }
            }
        });
}

function validarTelReg(){
    const salidaTel = document.getElementById("telReg");
    const phoneNumber = iti.getNumber();
    salidaTel.value=phoneNumber;
//    return;
    
    var telefono = document.getElementById("telReg").value;
    var telefonoTel = document.getElementById("telRegEntrada");
    var telefonoTelValue = document.getElementById("telRegEntrada").value;
    console.log("telReg: "+telefono+" / telRegEntrada: "+telefonoTelValue);
    if (telefonoTelValue.length === 0){
        return;
    }
    var parametros = {
        "telMovil": telefono
    };

    $.ajax({
        url: 'validarTelmovil.php',
        data: parametros,
        dataType: "json",
        beforeSend: function(objeto) {},
        success: function(data) {
            if (data.status === "1") {
                var mensajeTel = document.getElementById('mensajeTel');
                mensajeTel.innerText = "❌ " + "Teléfono en uso, por favor usar otro.";
                mensajeTel.style.color = "red";
                mensajeTel.style.display = "block";
                telefono.value="";
                var telefonoTel = document.getElementById("telRegEntrada");
                telefonoTel.value="";
                telefonoTel.focus();
                setTimeout(() => {
                    mensajeTel.style.display = "none";
                }, 3000); // Oculta el mensaje después de 3 segundos
            }
        }
    });
}

function validarMailReg(){
    var email = document.getElementById("emailReg").value;
    var emailTel = document.getElementById('emailReg');
    if (email.length === 0){
        return;
    }
    var emailinput = document.getElementById("emailReg");
    var parametros = {
        "emailReg": email
    };
    $.ajax({
        url: 'validaremailReg.php',
        data: parametros,
        dataType: "json",
        beforeSend: function(objeto) {},
        success: function(data) {
            if (data.status === "1") {
                var mensajeMail = document.getElementById('mensajeMail');
                mensajeMail.innerText = "❌ " + "Mail en uso, por favor usar otro.";
                mensajeMail.style.color = "red";
                var mensaje = document.getElementById('mensajeMail');
                mensaje.style.display = "block";
                emailinput.value="";
                emailinput.focus();
                setTimeout(() => {
                    mensaje.style.display = "none";
                }, 3000); // Oculta el mensaje después de 3 segundos
            }
        }
    });
}


</script>    

<style>
    @font-face {
      font-family: 'CooperBlack Italic';
      src: url("fonts/CooperBlack Italic.ttf") format('truetype');
    }
</style>
    
<style>
  /* Estilos para feedback visual */
  .valid { border: 2px solid green; }
  .invalid { border: 2px solid red; }
  #mensaje { font-size: 0.9em; color: red; }
</style>

</head>
<body>

<header id="header">

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="logo-area">
      <div class="logo-img-placeholder">
        <div class="logo-text-block">
            <img id="logo" src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 100%; max-width: 300px;">
            <p data-trad="hipermercado inmobiliario" style="font-family: 'CooperBlack Italic';
                font-size: calc(1.5 * 100%);margin: 0;">
            </p>
        </div>
      </div>
    </div>

    <div class="topbar-utils">
      <div class="lang-row">
        <label for="selector-idioma">🌐 Idioma:</label>
        <select id="selector-idioma" onchange="cambiarIdioma(this.value)">
            <?php
            $result = $con->query("SELECT * FROM idiomas ORDER BY descripcionidioma");
            if ($result->num_rows > 0) {
                while($row = mysqli_fetch_array($result)){
                $idiomaPerm = $_SESSION['idiomapermanente'];
                if ($row['codigoidioma'] === $idiomaPerm){ ?>
                    <option value="<?php echo $row['codigoidioma'];?>" selected><?php echo $row['descripcionidioma'];?></option>
                <?php
                    } else { ?>
                    <option value="<?php echo $row['codigoidioma'];?>"><?php echo $row['descripcionidioma'];?></option>
                <?php
                }
                }
                ?>
            <?php }?>
        </select>
      </div>
      <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
          <div class="ref-row">
            <span class="ref-label" data-trad="Consultar anuncio por referencia"></span>
            <div class="ref-search">
              <span class="lupa">🔍</span>
              <input type="text" placeholder="N° 0000 0000" name="elAnuncio">
            </div>
          </div>
      </form>
    </div>
  </div>

<!-- REGISTRO -->
<style>
    #bloqueCaptcha {
        opacity: 0;
        transition: opacity 0.6s ease-in;
    }
    #bloqueCaptcha.mostrar {
        opacity: 1;
    }

    /* Contenedor del switch */
    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 20px;
    }

    /* Ocultar el checkbox original */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* El deslizable */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 26px;
    }

    /* La bolita */
    .slider:before {
      position: absolute;
      content: "";
      height: 15px;
      width: 15px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    /* Cuando está activado */
    input:checked + .slider {
      background-color: #4CAF50; /* verde */
    }

    /* Movimiento de la bolita */
    input:checked + .slider:before {
      transform: translateX(20px);
    }

/* Contenedor general */
#contenedorHTML {
    font-family: "Arial", sans-serif;
    font-size: 15px;
    line-height: 1.55;
    color: #222;
    max-width: 780px;        /* ancho tipo PDF */
    margin: 0 auto;          /* centrado */
    padding: 20px;
}

/* Títulos principales (h1) */
#contenedorHTML h1 {
    font-size: 20px;
    font-weight: bold;
    color: #003366;          /* azul legal */
    margin-top: 35px;
    margin-bottom: 15px;
    border-left: 5px solid #003366;
    padding-left: 10px;
}

/* Subtítulos (si los hubiera) */
#contenedorHTML h2 {
    font-size: 18px;
    font-weight: bold;
    color: #004a8f;
    margin-top: 30px;
    margin-bottom: 10px;
}

/* Párrafos */
#contenedorHTML p {
    margin-bottom: 12px;
    text-align: justify;     /* estilo PDF */
}

/* Listas */
#contenedorHTML ul,
#contenedorHTML ol {
    margin-left: 25px;
    margin-bottom: 15px;
}

#contenedorHTML li {
    margin-bottom: 8px;
}

/* Negritas */
#contenedorHTML b {
    font-weight: 600;
}

/* Enlaces */
#contenedorHTML a {
    color: #0055aa;
    text-decoration: underline;
}

/* Línea separadora tipo PDF */
#contenedorHTML .separador {
    border-bottom: 2px solid #ccc;
    margin: 30px 0;
}

/* Encabezado centrado (título del documento) */
#contenedorHTML .titulo-central {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 25px;
    color: #003366;
}

/* Ajustes para celular */
@media (max-width: 600px) {
    #contenedorHTML {
        font-size: 16px;
        padding: 15px;
        line-height: 1.6;
    }

    #contenedorHTML h1 {
        font-size: 19px;
        border-left-width: 4px;
    }

    #contenedorHTML h2 {
        font-size: 17px;
    }
}

    #password-rules{
        margin-top:10px;
    }

    #password-rules div{
        margin:4px 0;
    }

    .error{
        color:#d9534f;
    }

    .ok{
        color:#198754;
        font-weight:bold;
    }
    
</style>

<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <!-- BOTONES -->
                <div class="modal-footer d-block" style="margin-top:10px;">
                    <button type="button" id="btnRegistro" class="btn btn-warning float-end"
                            onclick="preValidacion()" data-trad="Continuar">Continuar</button>
                    <button type="button" class="btn btn-warning float-end"
                            onClick="LimpiarModal()" data-trad="Limpiar">Limpiar</button>
                </div>
                <h5 class="modal-title" id="registroModalLabel" data-trad="Crear una cuenta">Crear una cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="registroValidacion.php" id="MiFormulario" autocomplete="off">
                    <!-- TIPO CLIENTE -->
                    <div class="mb-3 form-control">
                        <h6 data-trad="TIPO CLIENTE">TIPO CLIENTE</h6>

                        <div>
                            <label class="col-sm-2" data-trad="Particular" for="clienteTipoPART">Particular</label>
                            <input value="P" class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoPART"
                                   onChange="document.getElementById('id_oficina').style.display = 'none';">
                        </div>

                        <div>
                            <label class="col-sm-2" for="clienteTipoPROF" data-trad="Profesional">Profesional</label>
                            <input value="R" class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoPROF"
                                   onChange="document.getElementById('id_oficina').style.display = 'none';">
                        </div>

                        <div>
                            <label class="col-sm-2" for="clienteTipoOFI" data-trad="Vinculado">Vinculado</label>
                            <input value="O" class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoOFI" onChange="verOficina();">

                            <div id="id_oficina" style="display: none;">
                                <label class="col-sm-2" for="nroInmob" data-trad="Nro.Inmob">Nro.Inmob</label>
                                <input type="text" class="col-sm-1" id="nroInmobiliaria" name="nroInmob" onblur="validarInmobiliaria();"/>
                                <input type="text" class="col-sm-6" id="nombreinmob" name="nombreinmob"/>
                                <p id="mensajeInmob" style="display: none;"></p>
                            </div>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <input type="email" class="form-control" id="emailReg" name="emailReg" placeholder="E-Mail"
                               onfocusout="validarMailReg()" autocomplete="off" required />
                        <p id="mensajeMail" style="display: none;"></p>
                    </div>

                    <!-- NOMBRE -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="nombreReg" name="nombreReg"
                               data-trad="Razón Social o Apellido y Nombres"
                               placeholder="Razón Social o Apellido y Nombres" required />
                    </div>

                    <!-- TELÉFONO -->
                    <div class="mb-4">
                        <input id="telRegEntrada" type="tel" name="telRegEntrada" onblur="validarTelReg()" autocomplete="off"/>
                        <input id="telReg" type="hidden" name="telReg"/>
                        <p id="mensajeTel" style="display: none;"></p>
                    </div>

                    <!-- PASSWORD -->
<!--                    <div class="mb-3">
                        <input type="password" class="form-control" id="pwdReg" name="pwdReg"
                               placeholder="Clave de ingreso" autocomplete="off" required />
                        <div id="mensaje"></div>
                    </div>
-->

                    <div class="grupo-password">
                        <label for="pwdReg">
                            Clave de ingreso
                        </label>
                        <input
                            type="password"
                            id="pwdReg"
                            name="pwdReg"
                            maxlength="20"
                            autocomplete="new-password">
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
                    
                        <div class="mb-4">
                            <label for="pwdverifReg">
                                Reingresar
                            </label>
                            <input type="password" id="pwdverifReg" name="pwdverifReg" autocomplete="off" required</>
                        </div>
                    </div>                    

                    <!-- LEGALES -->
                    <button type="button" class="btn btn-warning mb-2" id="btnAbrirTerminos">
                        Leer Condiciones de Uso
                    </button>

                    <!-- COMERCIALES -->
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label for="noTrack" class="m-0" data-trad="Deseo recibir comunicaciones comerciales de Plusdata">Deseo recibir comunicaciones comerciales de Plusdata</label>

                        <label class="switch m-0">
                            <input type="checkbox" id="noTrack" name="infonoticias">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <!-- CAPTCHA DE PREGUNTAS -->
                    <div class="mb-3" id="bloqueCaptcha" style="display:none;">
                        <label id="preguntaCaptcha" style="font-weight:bold;"></label>
                        <input type="text" class="form-control" id="respuestaCaptcha" autocomplete="off">
                        <p id="mensajeCaptcha" style="color:red; display:none;"></p>
                    </div>

                    <!-- TOKEN reCAPTCHA -->
                    <input type="hidden" name="tokenRecaptcha" id="tokenRecaptcha">

                </form>
            </div>
        </div>
    </div>
</div>
    
<script>
    document.getElementById("noTrack").addEventListener("change", function() {
        console.log("Estado:", this.checked);
    });

    $("#pwdReg").on("focus", function(){
        $("#password-rules").show();
    });
    
    $("#pwdReg").on("input", function(){

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
<!-- MODAL TÉRMINOS -->
<div class="modal fade" id="modalTerminos" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Términos y Condiciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- CONTENEDOR PDF (desktop) -->
                <div id="contenedorPDF"
                     style="display:none; height:75vh;">
                </div>

                <!-- CONTENEDOR HTML (celular) -->
                <div id="contenedorHTML"
                     style="display:none; height:75vh; overflow-y:auto; padding:20px;">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    he leído y acepto
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function esMobile() {
    return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
}

document.addEventListener("DOMContentLoaded", function() {

    var modal = document.getElementById('modalTerminos');

    modal.addEventListener('shown.bs.modal', function () {

        const contPDF  = document.getElementById("contenedorPDF");
        const contHTML = document.getElementById("contenedorHTML");

        if (esMobile()) {

            // Mostrar HTML
            contHTML.style.display = "block";
            contPDF.style.display  = "none";

            fetch('legales/Legales-05.htm')
                .then(r => r.text())
                .then(html => {
                    contHTML.innerHTML = html;
                });

        } else {

            // Mostrar PDF dentro de iframe
            contPDF.style.display  = "block";
            contHTML.style.display = "none";

            contPDF.innerHTML =
                '<iframe src="legales/Legales-05.pdf" ' +
                'width="100%" height="100%" style="border:none;"></iframe>';
        }

    });

});
    
</script>

<!-- CAPTCHA EXTRA ACTIVADO POR BACKEND -->
<?php if (!empty($_SESSION['captcha_extra'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    obtenerCaptcha();
    document.getElementById("bloqueCaptcha").style.display = "block";
    document.getElementById("bloqueCaptcha").classList.add("mostrar");

    let btn = document.getElementById("btnRegistro");
    btn.onclick = validarCaptcha;
    btn.innerText = "Confirmar Alta";
});
</script>
<?php endif; ?>
    
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM listo');
    const btn = document.getElementById('btnAbrirTerminos');

    // Forzar habilitado al cargar
    if (btn) {
        btn.disabled = false;
    }

    btn.addEventListener('click', function () {
        console.log('click en btnAbrirTerminos');
        let modalTerminos = new bootstrap.Modal(document.getElementById('modalTerminos'));
        modalTerminos.show();
        window.terminosLeidos = true;
    });

    document.getElementById('modalTerminos').addEventListener('hidden.bs.modal', function () {
        console.log('modal cerrado -> deshabilito boton');
        btn.disabled = true;
    });
});
</script>
    
<!-- CAPTCHA DE PREGUNTAS -->
<script>
let captchaPregunta = "";
let captchaRespuesta = "";

function obtenerCaptcha() {
    fetch("captcha/captchaAjax.php")
        .then(r => r.json())
        .then(data => {
            captchaPregunta = data.pregunta;
            captchaRespuesta = data.respuesta;

            document.getElementById("preguntaCaptcha").innerText = "Captcha: " + captchaPregunta;
            document.getElementById("bloqueCaptcha").style.display = "block";
        });
}

function validarCaptcha() {
    let respuesta = document.getElementById("respuestaCaptcha").value.trim().toLowerCase();

    if (respuesta !== captchaRespuesta.toLowerCase()) {
        document.getElementById("mensajeCaptcha").innerText = "Respuesta incorrecta. Intentá nuevamente.";
        document.getElementById("mensajeCaptcha").style.display = "block";
        obtenerCaptcha();
        return;
    }

    document.getElementById("MiFormulario").submit();
}

function preValidacion() {
    let tipo = document.querySelector('input[name="tipocliReg"]:checked');
    let email = document.getElementById("emailReg").value.trim();
    let nombre = document.getElementById("nombreReg").value.trim();
    let tel = document.getElementById("telReg").value.trim();
    let pwd = document.getElementById("pwdReg").value.trim();
    let pwd2 = document.getElementById("pwdverifReg").value.trim();

    var clave = document.getElementById('pwdReg').value;
    let passwordValida = clave.length >= 8
        && /[A-Z]/.test(clave)
        && /[a-z]/.test(clave)
        && /[0-9]/.test(clave)
        && /[^A-Za-z0-9]/.test(clave);

    $("#btnRegistro").prop("disabled", !passwordValida);
    
    if (!window.terminosLeidos) {
        alert("Por favor, leer los terminos y condiciones.");
    return;
    }

    if (!tipo || !email || !nombre || !tel || !pwd || !pwd2) {
        alert("Por favor, completá todos los datos antes de continuar.");
        return;
    }

    if (pwd !== pwd2) {
        alert("Las claves no coinciden.");
        return;
    }

    let btn = document.getElementById("btnRegistro");
    btn.onclick = validarCaptcha;
    btn.innerText = "Confirmar Alta";

    obtenerCaptcha();
    document.getElementById("bloqueCaptcha").style.display = "block";
    document.getElementById("bloqueCaptcha").classList.add("mostrar");
}
</script>

<!-- reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=6LcMM-QtAAAAAOprVE5eyZUSgm8nUNGIMr1lm5bm"></script>

<script>
function regenerarToken() {
    grecaptcha.execute('6LcMM-QtAAAAAOprVE5eyZUSgm8nUNGIMr1lm5bm', {action: 'registro'})
    .then(function(token) {
        document.getElementById('tokenRecaptcha').value = token;
    });
}

var modalRegistro = document.getElementById('registroModal');

modalRegistro.addEventListener('shown.bs.modal', function () {
    $("#password-rules").hide();

    grecaptcha.ready(function() {
        regenerarToken();
    });

});

</script>
    
<!-- LOGIN -->
    	<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel" data-trad="Ingreso a Mi Cuenta">Ingreso a Mi Cuenta</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form method="POST" action="ingreso.php" id="formLogin">
						<div class="mb-3">
							<input type="text" value="" class="form-control" id="emailLog" name="emailLog" placeholder="E-Mail" data-trad="E-Mail" />
						</div>
						<div class="mb-3">
                            <div class="field">
                                <input type="password" value="" class="form-control" id="password" name="pwdLog" required>
                                <label for="password" data-trad="Clave de ingreso">Clave de ingreso</label>
                            </div>
						</div>
                        <div align="center">
                            <a href="olvideMiPassword.php" data-trad="Olvidé mi contraseña">Olvidé mi contraseña</a>
                        </div>
						<div class="modal-footer d-block">
							<button type="button" class="btn btn-primary" data-trad="Ingresar" onclick="desactivarBoton(this)">Ingresar")</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

  <!-- NAVBAR HOME -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid" style="padding:0 8px;">
      <div class="collapse navbar-collapse show">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" data-trad="BUSCAR" href="busquedaPublica.php" target="panel"></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-trad="PUBLICAR"></a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#registroModal" data-bs-toggle="modal" data-bs-dismiss="modal" onClick="abrirModal()"  data-trad="REGISTRARSE"></a></li>
              <li><a class="dropdown-item" href="#loginModal" data-bs-toggle="modal"  data-trad="INGRESAR"></a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="abrirModal(); return false;" data-trad="REGISTRARSE">
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#loginModal" data-bs-toggle="modal" data-trad="INGRESAR"></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-trad="CONTACTO"></a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#registroModal" data-bs-toggle="modal" data-bs-dismiss="modal" onClick="abrirModal()"  data-trad="REGISTRARSE"></a></li>
              <li><a class="dropdown-item" href="#loginModal" data-bs-toggle="modal"  data-trad="INGRESAR"></a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- COUNTRY SECTION -->
  <div class="country-section">
    <div class="label">Elija país</div>
    <div class="flags-row">
    <?php
        $finales=0;
        while($row = mysqli_fetch_array($query)){	
            $bandera=$row['banderanew'];
            $moneda=$row['moneda'];
            $moneda1=$row['moneda1'];
            $pais=$row['DESCRIPCION'];
            $codPais=$row['COD_NUM'];
    ?>
      <div class="flag-item">
        <a style="text-decoration:none" href="setEntornoNew.php?codPais=<?php echo $codPais?>&moneda=<?php echo $moneda ?>&moneda1=<?php echo $moneda1 ?>">
        <?php if (isset($_SESSION['codPais']) && $codPais === $_SESSION['codPais']){?>
            <img class="flag-img" src="<?php echo ($bandera);?> " title="<?php echo $pais?>" style="filter: drop-shadow(4px 4px 8px rgba(0,0,0,0.8)); object-fit: cover;"></a>
        <?php } else { ?>
            <img class="flag-img" src="<?php echo ($bandera);?> " title="<?php echo $pais?>"></a>
        <?php } ?>
        <span class="flag-name"><?php echo $pais?></span>
      </div>
      <?php } ?>
    </div>
  </div>

  <section id="sectorvisual" style="width: 100%; height: 100%;">
    <iframe id="panel" name="panel" scrolling="yes" width="100%" height="900px" style="border-top: 1px solid black;"></iframe>
  </section>

</header>

<script>
    document.getElementById("registroModal").addEventListener("shown.bs.modal", function () {
        var input = document.querySelector("#telRegEntrada");
        var iti = window.intlTelInput(input, {
        separateDialCode: true,
        preferredCountries: ["es", "ar", "uy", "ad"],
        initialCountry: "auto",
        geoIpLookup: function(callback) {
        fetch('https://ipinfo.io/json')
              .then(response => response.json())
              .then(data => callback(data.country))
              .catch(error => console.error(error));
        },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js"
        });

        iti.setNumber(input.value);
        window.iti = iti;

        const btnA = document.getElementById('btnAbrirTerminos');

        // Forzar habilitado al cargar
        if (btnA) {
            btnA.disabled = false;
        }

        window.terminosLeidos = false;
        document.getElementById("bloqueCaptcha").style.display = "none";

        
        document.getElementById('MiFormulario').reset();
    });

    window.onload = function() {
		(() => {
//            console.log("Entre a reset formulario registro!!!");
            document.getElementById('MiFormulario').reset();
            document.getElementById('clienteTipoPART').checked=false;
            document.getElementById('clienteTipoPROF').checked=false;
            document.getElementById('clienteTipoOFI').checked=false;
            document.getElementById("emailReg").value="";
            document.getElementById("nombreReg").value="";
            document.getElementById("telRegEntrada").value="";
            document.getElementById("telReg").value="";
            document.getElementById("pwdReg").value="";
            document.getElementById("pwdverifReg").value;
            var formularior = document.getElementById('MiFormulario');
//            console.log(formularior);
        })()
    
    document.getElementById("loginModal").addEventListener("show.bs.modal", function () {
        document.getElementById("formLogin").reset();
    });

    document.getElementById("loginModal").addEventListener("hidden.bs.modal", function () {
        document.getElementById("formLogin").reset();
    });

    document.querySelector("#MiFormulario").addEventListener("submit", function(event) {
        var phoneNumber = iti.getNumber();
        document.querySelector("#telReg").value = phoneNumber;
    });

    // Selecciona el modal y los inputs
    document.getElementById("loginModal").addEventListener("show.bs.modal", function () {
        document.getElementById("formLogin").reset();
    });

    document.getElementById("loginModal").addEventListener("hidden.bs.modal", function () {
        document.getElementById("formLogin").reset();
    });


    document.getElementById("loginModal").addEventListener("show.bs.modal", function () {
      this.querySelectorAll("input, textarea").forEach((campo) => {
        campo.value = ""; // Borra el contenido de los campos antes de que aparezca el modal
      });
    });

    document.querySelector("#MiFormulario").addEventListener("submit", function(event) {
        var phoneNumber = iti.getNumber();
        document.querySelector("#telReg").value = phoneNumber;
    });

    }
</script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<?php
if (isset($_SESSION['buscandoExterno']) && $_SESSION['buscandoExterno'] == 1){
    $_SESSION['buscandoExterno'] = 0;
    echo '<script> document.getElementById("panel").src = "pruebaDetalles.php?operacion=B";</script>';
    }
?>
<script src="pdfjs/pdf.js"></script>
<script src="pdfjs/pdf.worker.js"></script>
</body>
</html>
