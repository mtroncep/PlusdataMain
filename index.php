<?php
session_start();
require_once("conexion.php");
require_once("cargarDatosFormulario.php");

if (isset($_COOKIE['idioma'])){
    $_SESSION['idiomapermanente']=$_COOKIE['idioma'];
} else {
    $_SESSION['idiomapermanente']="es";?>
    <script>
        document.cookie = "idioma=" + "es" + "; path=/";
        location.reload(); // recarga la página para aplicar el nuevo idioma
    </script>
<?php
}
/*
    if (isset($_SESSION['buscandoExterno']) && $_SESSION['buscandoExterno'] == 1){
        $_SESSION['buscandoExterno'] = 0;
        echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
        return;
    }
*/
?>
<html lang="es">
<head>
  <title>Home - Hipermercado Inmobiliario</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Cache-Control" content="no-store">

<!--  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>

    <script src="trad/traducciones.php"></script>
    <script src="trad/traductor.js"></script>
    
<style>
	nav.navbar {
        background-color: #4cffff ; 
        font-size: 20px;
        font-weight: bold
        ;
        
    header{
      position: fixed;
      width: 100%;
      z-index: 1000;
    }
    
    iframe {
        overflow: scroll !important;
        width: 100%;
        height: 700px;
    }    
    
    section {
        width: 100%;
        margin: auto;
        overflow: hidden;
    }

    body {
          margin: 0; /* Elimina el margen predeterminado del body */
          padding: 0; /* Elimina el relleno predeterminado del body */
        }

</style>
    
<style>
    @font-face {
      font-family: 'CooperBlack Italic';
      src: url("fonts/CooperBlack Italic.ttf") format('truetype');
}
</style>

<style>
.field {
    position: relative;
}

.field input {
    padding: 10px;
}

.field label {
    position: absolute;
    left: 10px;
    top: 8px;
    transition: 0.2s;
    pointer-events: none;
    opacity: 0.6;
}

/* Cuando el input tiene focus o es válido (tiene contenido) */
.field input:focus + label,
.field input:valid + label {
    top: -10px;
    font-size: 12px;
    opacity: 1;
}
</style>
    
<script>
    function desactivarBoton(boton) {
      boton.disabled = true;
      boton.innerText = 'Ingresando...'; // Opcional: cambia el texto
      document.getElementById('formLogin').submit();
    }
</script>

<style>
    .navbar-collapse.show {
        margin-bottom: 200px; /* Ajustá según el tamaño de tu menú */
    }
</style>

</head>

<body>
<header id="header" style="width: 100%;">
<div class="container" style="width: 100%;">
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
	
	<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" align="center">
					<h5 class="modal-title" id="registroModalLabel" data-trad="Registro en el Sistema" >Registro en el Sistema</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form method="POST" action="registro.php" onsubmit="return validarClaves_1()" id="MiFormulario" autocomplete="off">
                        <div class="mb-3 form-control">
                            <h6 data-trad="TIPO CLIENTE">TIPO CLIENTE</h6>
                            <div>
                                <label class="col-sm-2" data-trad="Particular" for="clienteTipoPART">Particular</label>
                                <input value="P" class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoPART" 
                                onChange="document.getElementById('id_oficina').style.display = 'none';" >
                            </div>
                            <div>
                                <label class="col-sm-2" for="clienteTipoPROF" data-trad="Inmobiliaria">Inmobiliaria</label>
                                <input value="R" class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoPROF"
                                onChange="document.getElementById('id_oficina').style.display = 'none';" >
                            </div>
                            
                            <div>
                                <label class="col-sm-2" for="clienteTipoOFI" data-trad="Sucursal">Sucursal</label>
                                <input value="O"  class="col-sm-4" type="radio" name="tipocliReg" id="clienteTipoOFI" onChange="verOficina();">
                                <div id="id_oficina" style="display: none;">
                                    <label class="col-sm-2" for="nroInmob" data-trad="Nro.Inmob">Nro.Inmob</label>
                                    <input type="text" class="col-sm-1" id="nroInmobiliaria" name="nroInmob" onblur="validarInmobiliaria();"/>
                                    <input type="text" class="col-sm-6" id="nombreinmob" name="nombreinmob"/>
                                    <p id="mensajeInmob" style="display: none;"></p>
                                </div>
                            </div>    
                            
                        </div>
						<div class="mb-3">
							<input type="email" value="" class="form-control" id="emailReg" name="emailReg" placeholder="E-Mail" 
                                   onfocusout="validarMailReg()" autocomplete="off" required />
                            <p id="mensajeMail" style="display: none;"></p>
                        </div>
						<div class="mb-3">
							<input type="text" class="form-control" id="nombreReg" name="nombreReg" data-trad="Razón Social o Apellido y Nombres" placeholder="Razón Social o Apellido y Nombres" required />
						</div>
						<div class="mb-4">
                          <input id="telRegEntrada" type="tel" name="telRegEntrada" onblur="validarTelReg()" autocomplete="off"                                 />
                          <input id="telReg" type="hidden" name="telReg"/>
                          <p id="mensajeTel" style="display: none;"></p>
						</div>
						<div class="mb-3">
							<input type="password" class="form-control" id="pwdReg" name="pwdReg" 
                                   placeholder="Clave de ingreso" autocomplete="off" required />
						</div>
						<div class="mb-3">
							<input type="password" class="form-control" id="pwdverifReg" name="pwdverifReg" placeholder="Reingresar Clave"  autocomplete="off" required />
						</div>
						<div class="modal-footer d-block">
							<button type="submit" class="btn btn-warning float-end" data-trad="Registrarme">Registrarme</button>
							<button type="button" class="btn btn-warning float-end" onClick="LimpiarModal()" data-trad="Limpiar">Limpiar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

    <div class="row" style="padding-top: 10px">
        <div class="col">
            <div style="display: inline-block;">
                <img id="logo" src="imagenes/logoHomePlusdataSinTexto.jpg" style="width: 100%; max-width: 300px;">
                <p data-trad="hipermercado inmobiliario" 
                    style="font-family: 'CooperBlack Italic';
                    font-size: calc(1.5 * 100%);
                    margin: 0;
                ">
                    hipermercado inmobiliario
                </p>
            </div>
        </div>

        <div class="col" align="right" style="display: flex; justify-content: flex-end; align-items: flex-end;">
            <form action="procesarVistaPreviaAnuncio.php" method="post" target="_blank">
                <div style="display: flex;">
                    <div class="col">
                        <div class="col">
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
                        <div class="row">
                            <div class="col"><strong><p data-trad="Consultar anuncio por referencia">Consultar anuncio por referencia</p></strong></div>
                        </div>
                        
                        <div class="row" style="width: 100%; margin: 0;">
                            <div style="
                                display: flex;
                                justify-content: flex-end;
                                width: 100%;
                            ">
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                ">
                                    <input type="image"
                                           id="elAnuncio"
                                           src="imagenes/lupa.png"
                                           alt="Consultar"
                                           title="Consultar"
                                           style="width: 28px; height: auto; flex-shrink: 0;">

                                    <input type="text"
                                           name="elAnuncio"
                                           placeholder="N° 0000 0000"
                                           style="
                                               width: 120px;   /* ← el tamaño que vos quieras */
                                               direction: rtl;
                                           ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
<nav class="navbar navbar-expand-lg navbar-light" style="height: 5%">
    <div class="container">

        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

<!--        <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link text-black-50" href="busquedaPublica.php" target="panel" data-trad="BUSCAR">BUSCAR</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownAnuncios"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false" data-trad="PUBLICAR">
                       PUBLICAR
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownAnuncios">
                        <li>
                            <a class="dropdown-item" href="#registroModal" data-bs-toggle="modal" 
                               data-bs-dismiss="modal" onClick="abrirModal()" data-trad="REGISTRARSE">
                               REGISTRARSE
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#loginModal" data-bs-toggle="modal" data-trad="INGRESAR">
                               INGRESAR
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item">
                    <a class="nav-link text-black-50" href="#registroModal" data-bs-toggle="modal" 
                       data-bs-dismiss="modal" onClick="abrirModal()" data-trad="REGISTRARSE">
                       REGISTRARSE
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-black-50" href="#loginModal" data-bs-toggle="modal" data-trad="INGRESAR">
                       INGRESAR
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-black-50" href="#" id="navbarDropdownAnuncios"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false" data-trad="CONTACTO">
                       CONTACTO
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownAnuncios">
                        <li>
                            <a class="dropdown-item" href="#registroModal" data-bs-toggle="modal" 
                               data-bs-dismiss="modal" onClick="abrirModal()" data-trad="REGISTRARSE">
                               REGISTRARSE
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#loginModal" data-bs-toggle="modal" data-trad="INGRESAR">
                               INGRESAR
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>

    <?php 
        include("perfil.php");
    ?>

    <section id="sectorvisual" style="width: 100%; height: 100%;">
        <iframe id="panel" name="panel" scrolling="yes" width="100%" height="900px" style="border-top: 1px solid black;"></iframe>
    </section>
</div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collapse = document.getElementById('navbarSupportedContent');
        const containerPaises = document.getElementById('containerPaises'); // el bloque que sigue a la navbar

        collapse.addEventListener('shown.bs.collapse', function () {
            containerPaises.style.marginTop = collapse.offsetHeight + 'px';
        });

        collapse.addEventListener('hidden.bs.collapse', function () {
            containerPaises.style.marginTop = '0px';
            document.activeElement.blur();
        });
    });
</script>

 <script type="text/javascript">

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
/*
    document.getElementById("registroModal").addEventListener("shown.bs.modal", function () {
        alert('Entramos a registroModal');
        var input = document.querySelector("#telRegEntrada");
        console.log(window.intlTelInput);
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
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js"
        });

        window.iti = iti;
        
        document.getElementById('MiFormulario').reset();
        
    });
*/   
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
     
        function asignarParticular(tipoCli){
            if (tipoCli === 'P'){
                document.getElementById('nombreReg').value='DUEÑO';
                document.getElementById('nombreReg').readOnly = true;
            } else {
                document.getElementById('nombreReg').value='';
                document.getElementById('nombreReg').readOnly = false;
            }
        }

        function abrirModal() {
            document.getElementById('emailReg').value = ""; // Limpiar el campo antes de abrir el modal
            document.getElementById('registroModal').style.display = "block"; // Mostrar el modal
        }

        function cerrarModal() {
            document.getElementById("registroModal").style.display = "none"; // Ocultar el modal
        }

       function validarClaves(){
            var pwd = $('#pwdReg').val();
            var pwdVerif = $('#pwdverifReg').val();
            if (pwd != pwdVerif){
                alert("Las claves no coinciden, intenta de nuevo");
                return false;
            }
            return true;
        }

        function validarClaves_1(){
            if (document.querySelector('input[name="tipocliReg"]:checked')) {
                console.log("Hay un radio button seleccionado.");
            } else {
                console.log("Ningún radio button está marcado.");
                alert("Falta informar el tipo de cliente.")
                return false;
            }

            var tipoCli = document.getElementById('clienteTipoOFI').checked;
            if (tipoCli){
                if (document.getElementById('nroInmobiliaria').value.length == 0 ){
                    alert("Debe informar el nro de cliente de la inmobiliaria.");
                    return false;
                    }
            }

            var pwd = document.getElementById('pwdReg');
            var pwdVerif = document.getElementById('pwdverifReg');
            if (pwd.value != pwdVerif.value){
                alert("Las claves no coinciden, intenta de nuevo");
                return false;
            }
            
            $("#telRegEntrada").attr("disabled", true);
        }

        function verOficina(){
            document.getElementById("id_oficina").style.display = "block";
            return true;
        }

        function LimpiarModal(){
            document.getElementById('MiFormulario').reset();
        }

    </script>

    <script>
        function cambiarIdioma(idioma) {
            document.cookie = "idioma=" + idioma + "; path=/";
            location.reload(); // recarga la página para aplicar el nuevo idioma
        }
    </script>    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.navbar-collapse .nav-link');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false
                    });
                    bsCollapse.hide();
                });
            });
        });
    </script>

<!--    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    document.getElementById("registroModal").addEventListener("shown.bs.modal", function () {
        var input = document.querySelector("#telRegEntrada");
        console.log(window.intlTelInput);
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
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js"
        });

        window.iti = iti;
        
        document.getElementById('MiFormulario').reset();
        
    });
</script>
    
    <script src="trad/appTraduccion.js"></script>
</body>
<?php

if (isset($_SESSION['buscandoExterno']) && $_SESSION['buscandoExterno'] == 1){
    $_SESSION['buscandoExterno'] = 0;
//    echo ('<script type="text/javascript">'."window.location.href = 'pruebaDetalles.php?operacion=B'</script>");
//    return;
    echo '<script> document.getElementById("panel").src = "pruebaDetalles.php?operacion=B";</script>';
    }
?>
</html>

