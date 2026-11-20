<?php
session_start();

$anunciante="";
if (isset($_SESSION['anunciante'])){
    $anunciante=$_SESSION['anunciante'];
}

$clientetipo = $_SESSION['tipocliReg'];
$emailReg=$_SESSION['emailReg'];
$telReg=$_SESSION['telReg'];
$infonoticias=$_SESSION['infonoticias'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <script type="text/javascript">

        window.onload = function () {
            /* Variables globales (por estar declaradas sin var) */
            casillaDatos = document.getElementById('datos'); //Nodo donde vamos a mostrar los datos
            radioButTrat = document.getElementsByName("radfacturacion"); //Nodos radio buttons
            checkboxElements = new Array();
            var elementosDelForm = document.getElementsByTagName('input'); //Elementos input
            var elementosSelect = document.getElementsByTagName('select');
            for(var i=0; i<elementosDelForm.length;i++) {
            if (elementosDelForm[i].type == 'radio') {elementosDelForm[i].addEventListener("click", actualizarDatos);}
            else {elementosDelForm[i].addEventListener("change", actualizarDatos);}
            if (elementosDelForm[i].type == 'checkbox') {checkboxElements.push(elementosDelForm[i]);}
            }
            for (var i=0; i<elementosSelect.length;i++) {elementosSelect[i].addEventListener("change", actualizarDatos);}

            (() => {
            'use strict';
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert("Por favor, agregue la información solicitada.")
                }
                form.classList.add('was-validated');
                }, false);
            });
            })();
        }
        
        function liberarTipoCliente(){
            $("#clienteTipoPART").attr("disabled", false);
            $("#clienteTipoPROF").attr("disabled", false);
            $("#clienteTipoOFI").attr("disabled", false);
            $("#nrocli").attr("disabled", false);
        }
        
        function actualizarDatos() {
            //alert("Detecto el cambio");
            var rutaImagen = '';
            var radioButSelValue = '';
            for (var i=0; i<radioButTrat.length; i++) {
                if (radioButTrat[i].checked == true) 
                { radioButSelValue= radioButTrat[i].value;
                  //alert("Detecto el click: "+radioButSelValue);
                }
            }
            if (radioButSelValue != ''){
                if (radioButSelValue =='1') {
                    document.getElementById("nombreotro").value = document.getElementById("nombrerz").value;;
                    document.getElementById("calleynrootro").value = document.getElementById("calleynro").value;;
                    document.getElementById("codigopostalotro").value = document.getElementById("codigopostalpartic").value;;
                    document.getElementById("provinciaotro").value = document.getElementById("provinciapartic").value;;
                    document.getElementById("regionotro").value = document.getElementById("regionpartic").value;;
                    document.getElementById("paisotro").value = document.getElementById("paispartic").value;;
                    document.getElementById("fiscalotro").value = document.getElementById("identificacionfiscalpartic").value;;
                } else {
                    document.getElementById("nombreotro").value = "";;
                    document.getElementById("calleynrootro").value = "";;
                    document.getElementById("codigopostalotro").value = "";;
                    document.getElementById("provinciaotro").value = "";;
                    document.getElementById("regionotro").value = "";;
                    document.getElementById("paisotro").value = "";;
                    document.getElementById("fiscalotro").value = "";;
                }
            }
        }
    </script>
    <style>
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
    </style>

</head>
<body>
    <div class="container">
		<div class="row">
<!--			<input type="button" class="btn btn-close" onClick="history.go(-1);"></input> -->
			<?php
			if ($anunciante == ""){
				echo('<h2 align="center">INGRESO FICHA INTERNA</h2>');
			} else {
				echo('<h2 align="center">INGRESO FICHA PERSONAL</h2>');
			}
			?>
		</div>
        <ul class="nav nav-tabs" id="formTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Cliente</button>
            </li>
<!--            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos1-tab" data-bs-toggle="tab" data-bs-target="#datos1" type="button" role="tab" aria-controls="datos1" aria-selected="false">Contacto</button>
            </li> -->
            <?php if ($clientetipo == 'R') { ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos2-tab" data-bs-toggle="tab" data-bs-target="#datos2" type="button" role="tab" aria-controls="datos2" aria-selected="false">Facturacion</button>
            </li>
            <?php } ?>
<!--            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos3-tab" data-bs-toggle="tab" data-bs-target="#datos3" type="button" role="tab" aria-controls="datos3" aria-selected="false">Idiomas</button>
            </li> -->
            <?php if ($clientetipo <> 'O' && $clientetipo <> 'P') { ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="datos4-tab" data-bs-toggle="tab" data-bs-target="#datos4" type="button" role="tab" aria-controls="datos4" aria-selected="false">Medios de Pago</button>
            </li>
            <?php } ?>
        </ul>
		<br>
        <form class="row needs-validation" method="POST" action="insertarFichaBD.php" novalidate>
            <div class="tab-content">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-primary" onClick="liberarTipoCliente()">Enviar</button>
                        </div>
                    </div>

                    <h5>CLIENTE</h5>
                    <div class="form-group row">
                        <label for="nrocli" class="col-sm-2 col-form-label col-form-label-sm">Código identificación</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="nrocli" value="<?php echo $anunciante;?>" disabled>
                        </div>

                        <div class="form-group">
                            <div class="form-check col-sm-3">
                                <label class="col-sm-2" for="clienteTipoPART">Particular</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoPART" value="P" 
									   <?php
										if (isset($_SESSION['tipocliReg']) && $_SESSION['tipocliReg'] == "P"){
											echo " checked";
										} else {
											echo " ";
										}
                                        echo " disabled"
									   ?>>
                            </div>
                            <div class="form-check col-sm-3">
                                <label class="col-sm-2" for="clienteTipoPROF">Profesional</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoPROF" value="R"
									   <?php
										if (isset($_SESSION['tipocliReg']) && $_SESSION['tipocliReg'] == "R"){
											echo " checked";
										} else {
											echo " ";
										}
                                        echo " disabled"
									   ?>>
                            </div>
                            <div class="form-check col-sm-3">
                                <label class="col-sm-2" for="clienteTipoOFI">Oficina</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoOFI" value="O"
									   <?php
										if (isset($_SESSION['tipocliReg']) && $_SESSION['tipocliReg'] == "O"){
											echo " checked";
										} else {
											echo " ";
										}
                                        echo " disabled"
									   ?>>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="input-group form-outline">
                                <label for="nombrerz" class="col-sm-5 col-form-label col-form-label-sm">Nombre o Razón Social</label>
                                <input type="text" class="form-control form-control-sm" id="nombrerz" name="nombrerz" aria-describedby="inputGroupPrepend" value="<?php echo $_SESSION['nombreReg']?>" required>
                                <div class="invalid-feedback">
                                    Ingrese Nombre o Razón Social.
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5>DOMICILIO PARTICULAR</h5>
                    <!-- COMERCIALES -->
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <label for="noTrack" class="m-0">Acepto recibir noticias comerciales</label>
                        <label class="switch m-0">
                            <input type="checkbox" id="noTrack" name="infonoticias">
                            <span class="slider"></span>
                        </label>
                    </div>
                        
                    <div class="form-group">
                        <div class="row mb-3">
                            <label for="calleynro" class="col-sm-2 col-form-label col-form-label-sm">Calle y número</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="calleynro" name="calleynumeropartic" >
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="codigopostalpartic" class="col-sm-2 col-form-label col-form-label-sm ">Código postal y localidad</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="codigopostalpartic" name="codigopostalpartic" >
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="provinciapartic" class="col-sm-2 col-form-label col-form-label-sm ">Provincia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="provinciapartic" name="provinciapartic" >
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="regionpartic" class="col-sm-2 col-form-label col-form-label-sm ">Libre</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="regionpartic" name="regionpartic" >
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="paispartic" class="col-sm-2 col-form-label col-form-label-sm ">País de Residencia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="paispartic" name="paispartic" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-5">
                                <div class="form-group mb-7">
                                    <label for="tipoDOC" class="col-sm-12 col-form-label col-form-label-sm">Tipo/Nro. Documento</label>
                                    <div class="input-group col-sm-6">
                                        <div class="col col-sm-5">
                                            <select class="form-select form-select-sm" aria-label="Default select example" id="tipoDOC" name="tipodoc" >
                                                <option value="">Elija</option>
                                                <option value="DNI">DNI</option>
                                                <option value="PASAPORTE">PASAPORTE</option>
                                                <option value="OTRO">OTRO</option>
                                            </select> 	
                                        </div>
                                        <div class="col">
                                            <input name="nrodocumentopartic" id="nrodocumentopartic"  class="form-control form-control-sm mb-3">
                                        </div>
                                    </div>
                                </div>    
                            </div>	
                        </div>
                    </div>
<!--                </div> -->

                <div class="tab-pane" id="datos1" role="tabpanel" aria-labelledby="datos1-tab">
<!--						<div class="row">
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
-->

                    <h5>TELEFONOS</h5>
                    <div class="row mb-3">
                        <label for="telfijo" class="col-sm-2 col-form-label col-form-label-sm">Fijo</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="telfijo" name="telfijo">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="telmovil" class="col-sm-2 col-form-label col-form-label-sm">Móvil</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="telmovil" name="telmovil" value="<?php echo $telReg ?>" required readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nrofax" class="col-sm-2 col-form-label col-form-label-sm">Número de Fax</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="nrofax" name="nrofax">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nrowsp" class="col-sm-2 col-form-label col-form-label-sm">Emsajeria Wathsapp</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="nrowsp" name="nrowsp">
                        </div>
                    </div>

					<div class="row mb-3">
                        <label for="telegram" class="col-sm-2 col-form-label col-form-label-sm">Mesajeria Wathsapp</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="telegram" name="telegram">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="otramens" class="col-sm-2 col-form-label col-form-label-sm">Otra Mensajería</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="otramens" name="otramens">
                        </div>
                    </div>
                    <h5>CORREO ELECTRONICO</h5>
                    <div class="row mb-3">
                        <label for="emailpersonal" class="col-sm-2 col-form-label col-form-label-sm">Correo Personal</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="emailpersonal" name="emailpersonal">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="emaillaboral" class="col-sm-2 col-form-label col-form-label-sm">Correo Laboral</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="emaillaboral" name="emaillaboral" value="<?php echo $emailReg?>" required readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="emailalternativo" class="col-sm-2 col-form-label col-form-label-sm">Correo Alternativo</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" id="emailalternativo" name="emailalternativo">
                        </div>
                    </div>
                </div>
                <h5>DATOS CONTACTO PUBLICACION</h5>
                <div class="row mb-3">
                    <label for="contactonombre" class="col-sm-2 col-form-label col-form-label-sm">Nombre</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control form-control-sm" id="contactonombre" name="contactonombre"
                               value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="contactotel" class="col-sm-2 col-form-label col-form-label-sm">Teléfono</label>
                    <div class="col-sm-3">
                        <input class="form-control form-control-sm" id="contactotel" name="contactotel" 
                               value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="contactoemail" class="col-sm-2 col-form-label col-form-label-sm">Correo Electrónico</label>
                    <div class="col-sm-3">
                        <input type="email" class="form-control form-control-sm" id="contactoemail" name="contactoemail" 
                               value="">
                    </div>
                </div>
                </div>

                <?php if ($clientetipo == 'R') { ?>
				<div class="tab-pane" id="datos2" role="tabpanel" aria-labelledby="datos2-tab">
						<div class="row">
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary" onClick="liberarTipoCliente()">Enviar</button>
                            </div>
                        </div>
                    
                    <h5>DATOS FACTURACION</h5>
                    <div class="form-group">
                        <div class="form-check col-sm-6">
                            <label class="col-sm-3" for="radfacturacion">Domicilio Particular</label>
                            <input class="col-sm-3" type="radio" name="radfacturacion" id="radfacturacionpart" value="1">
                        </div>
                        <div class="form-check col-sm-6">
                            <label class="col-sm-3" for="radfacturacion">Otro Domicilio</label>
                            <input class="col-sm-3" type="radio" name="radfacturacion" id="radfacturacionprof" value="2" checked>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <label for="nombreotro" class="col-sm-2 col-form-label col-form-label-sm">Nombre o Razón Social</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="nombreotro" name="nombreotro">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="calleynrootro" class="col-sm-2 col-form-label col-form-label-sm">Calle y número</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="calleynrootro" name="calleynrootro">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="codigopostalotro" class="col-sm-2 col-form-label col-form-label-sm ">Código postal y localidad</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="codigopostalotro" name="codigopostalotro">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="provinciaotro" class="col-sm-2 col-form-label col-form-label-sm ">Provincia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="provinciaotro" name="provinciaotro">
                            </div>
                        </div>
                        <!--
                        <div class="row mb-3">
                            <label for="regionotro" class="col-sm-2 col-form-label col-form-label-sm ">Libre</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="regionotro" name="regionotro">
                            </div>
                        </div>
                        -->
                        <div class="row mb-3">
                            <label for="paisotro" class="col-sm-2 col-form-label col-form-label-sm ">País</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="paisotro" name="paisotro">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fiscalotro" class="col-sm-2 col-form-label col-form-label-sm">Número Indentificación Fiscal</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control form-control-sm" id="fiscalotro" name="fiscalotro">
                            </div>
                        </div>
                    </div>
                </div>

			<div class="tab-pane" id="datos3" role="tabpanel" aria-labelledby="datos3-tab">
						<div class="row">
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary" onClick="liberarTipoCliente()">Enviar</button>
                            </div>
                        </div>

                    <h5>IDIOMAS QUE ATIENDE</h5>
                    <div class="row form-control">
                        <div class="col-sm-9">
                            <img src="./imagenes/bandera_españa.png" class="img-thumbnail" width="40" id="fotoespana">   
                            <label for="fotoespana" class="col-sm-1 col-form-label col-form-label-sm">ESPAÑOL</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkespanol" name="checkespanol">

                            <img src="./imagenes/bandera_catala.png" class="img-thumbnail" width="40" id="fotocatala">                
                            <label for="fotocatala" class="col-sm-1 col-form-label col-form-label-sm">CATALÁ</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkcatala" name="checkcatala">

                            <img src="./imagenes/bandera_gallega.png" class="img-thumbnail" width="40" id="fotogallega">                
                            <label for="fotogallega" class="col-sm-1 col-form-label col-form-label-sm">GALLEGO</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkgallego" name="checkgallego">

                            <img src="./imagenes/bandera_eusquera.png" class="img-thumbnail" width="40" id="fotoeuskera">                
                            <label for="fotoeuskera" class="col-sm-1 col-form-label col-form-label-sm">EUSKERA</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkeusquera" name="checkeusquera">

                            <img src="./imagenes/bandera_francesa.png" class="img-thumbnail" width="40" id="fotofrancia">                
                            <label for="fotofrancia" class="col-sm-1 col-form-label col-form-label-sm">FRANÇAIS</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkfrances" name="checkfrances">

                            <img src="./imagenes/bandera_inglesa.png" class="img-thumbnail" width="40" id="fotoinglesa">                
                            <label for="fotoinglesa" class="col-sm-1 col-form-label col-form-label-sm">ENGLISH</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkingles" name="checkingles">

                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="tab-pane" id="datos4" role="tabpanel" aria-labelledby="datos3-tab">
						<div class="row">
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary" onClick="liberarTipoCliente()">Enviar</button>
                            </div>
                        </div>

                    <h5>MEDIOS DE PAGO</h5>
                    <div class="form-group">
                            <div class="col-sm-2">
                                <label for="checktarj" class="col-form-label col-form-label-sm">¿Trajeta Crédito o Débito?</label>
                                <input class="form-check-input col-form-label col-form-label-sm" type="checkbox" value="1" id="checktarj" name="checktarj">
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control form-control-sm" id="nombretarjetasel" name="nombretarjetasel" >
                                    <option value="">Elija</option>
                                    <option value="VISA">VISA</option>
                                    <option value="MASTERCARD">MASTERCARD</option>
                                    <option value="AMERICAN EXPRESS">AMERICAN EXPRESS</option>
                                </select> 	
                            </div>
                            <br>
                            <div class="row mb-3">
                                <label for="nrotarjeta" class="col-sm-2 col-form-label col-form-label-sm ">Número</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="nrotarjeta" name="nrotarjeta">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nombretarjeta" class="col-sm-2 col-form-label col-form-label-sm">Nombre como en tarjeta</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="nombretarjeta" name="nommbretarjeta">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="vtotarjeta" class="col-sm-2 col-form-label col-form-label-sm">Vencimiento</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="vtotarjeta" name="vtotarjeta">
                                </div>
                            </div>
                            <div class="row mb-3">
                               <label for="codsegtarjeta" class="col-sm-2 col-form-label col-form-label-sm">Código de seguridad</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="codsegtarjeta" name="codsegtarjeta">
                                </div>
                            </div>
                            <h5>DATOS BANCARIOS</h5>
                            <div class="row mb-3">
                                <label for="transfbancaria" class="col-sm-2 col-form-label col-form-label-sm">Pago por Transf Bancaria</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="transfbancaria" name="transfbancaria">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="iban" class="col-sm-2 col-form-label col-form-label-sm">IBAN Cuenta Bancaria</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="iban" name="iban">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="factacum" class="col-sm-2 col-form-label col-form-label-sm">Facturación Acumulada</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="factacum" name="factacum">
                                </div>
                            </div>
                        </div>
                        <br>
						<div class="row">
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary" onClick="liberarTipoCliente()">Enviar</button>
                            </div>
                        </div>
          		</div>
        	</div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>
