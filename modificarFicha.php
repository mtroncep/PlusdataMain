<!DOCTYPE html>
<?php
session_start();
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");
?>

<script src="trad/traducciones.php"></script>
<script src="trad/traductor.js"></script>

<?php
if (!isset($_SESSION['codPais'])){
    echo '<script type="text/javascript">alert(traducir("Falta seleccionar el país"))</script>';
    $_SESSION['anuncioSelSinPais'] = "modificarFicha.php";
    return;
}

$esRegisto = false;
if (!isset($_SESSION['registro'])){
    if (!isset($_SESSION['codPais'])){
        $_SESSION['codPais'] = 410;
    } else {
        $esRegisto = false;
    }
} else {
    $esRegisto = true;
//    unset( $_SESSION['registro']);
}

//$idFicha = $_REQUEST['idFicha'];
$idFicha = '';
if (isset($_REQUEST['idFicha'])){
	$idFicha=$_REQUEST['idFicha'];
} else {
    $idFicha = $_SESSION['idFicha'];
}

$anunciante="";
if (isset($_REQUEST['anunciante'])){
	$anunciante=$_REQUEST['anunciante'];
	$_SESSION['anunciante']=$anunciante;
}

$query = mysqli_query($con, "SELECT * FROM fichas where idficha=$idFicha");
$row = mysqli_fetch_array($query);
$radfacturacion = $row['radfacturacion'];
if (isset($_SESSION['clientetipo'])){
    $clientetipo = $_SESSION['clientetipo'];
} else {
    $clientetipo = $row['clientetipo'];
}

if (isset($_SESSION['clienteTipoQ']) && $_SESSION['clienteTipoQ'] === 'Q'){
    $_SESSION['clientetipo'] = 'Q';
    $clientetipo = 'Q';
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boostrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="css/fuentes.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>

    <script src="trad/traducciones.php"></script>
    <script src="trad/traductor.js"></script>

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
            else {/*elementosDelForm[i].addEventListener("change", actualizarDatos);*/}
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
/*
            var parametros = {
                "action": "ajax",
                "nro_en_form": "img",
                "titulo": "IMAGENES"
    //            "agrpradio":"N"
            };
*/
            $.ajax({
                url: 'ajax/mostrarBanner.php',
                method: 'post',
  //              data: parametros,
                beforeSend: function(objeto) {},
                success: function(data) {
                    $(".outer_div_img").html(data).fadeIn('slow');
                }
            });

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
                        document.getElementById("nombreotro").value = document.getElementById("nombrerz").value;
                        document.getElementById("calleynrootro").value = document.getElementById("calleynro").value;
                        document.getElementById("codigopostalotro").value = document.getElementById("codigopostalpartic").value;
                        document.getElementById("provinciaotro").value = document.getElementById("provinciapartic").value;
                        document.getElementById("paisotro").value = document.getElementById("paispartic").value;
                        document.getElementById("identificacionfiscalpartic").value =   document.getElementById("identificacionfiscalpartic").value;
                    } else {
                        document.getElementById("nombreotro").value = "<?php echo $row['nombreotro'] ?>";
                        document.getElementById("calleynrootro").value = "<?php echo $row['calleynrootro'] ?>";
                        document.getElementById("codigopostalotro").value = "<?php echo $row['codigopostalotro'] ?>";
                        document.getElementById("provinciaotro").value = "<?php echo $row['provinciaotro'] ?>";
                        document.getElementById("paisotro").value = "<?php echo $row['paisotro'] ?>";
  //                      document.getElementById("fiscalotro").value = "";
                    }
                }
            }
            
            function obtenerPais() {
                var paisConexion = '<?php echo (Funciones::obtenerLocalizacion()) ?>';
                console.log('País de conexion: '+paisConexion);
                document.getElementById('paispartic').value = paisConexion;
            }

            obtenerPais();

        function volverAInicio(){
              window.location.replace('<?php echo $_SESSION['menuinicio'] ?>');
            }
        
        }
        
</script>
    
<style>
  .frame {
    width: 1478px;
    height: 672 px;
    overflow: hidden;
  }

  .frame img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Ajusta la imagen sin recortarla */
  }
</style>

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
      bottom: 2.5px;
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
    .icono-mensaje {
        width: 40px;
        height: 40px;
        object-fit: contain;    
    }
    
    .outer_div_img {
      width: 100%;
    }

    .outer_div_img img {
      display: block;
      width: 100% !important;
      max-width: 100%;
      height: auto !important;
    }

    .outer_div_img img {
      max-width: 600px;
    }

    #imagenBanner {
      display: block;
      width: 100%;
    }

    #imagenBanner img {
      width: 100% !important;
      max-width: 600px;
      height: auto !important;
    }

    #imagenBanner,
    #panelCargaIMG {
      max-width: 300px; /* cambialo por el ancho que quieras */
    }
    
    #imagenBanner .row {
      margin-left: 0;
      margin-right: 0;
    }

    #imagenBanner .row > div {
      flex: 0 0 100%;
      max-width: 100%;
    }

    #imagenBanner img {
      width: 100%;
      max-width: 100%;
      height: auto;
    }
    
    .tituloFijar {
      position: sticky; /* o fixed */
      top: 0;
      width: 100%;
      background-color: white;
      padding: 10px;
      z-index: 100;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

</style>

</style>

</head>
<body>
    <div class="tituloFijar row col-12">
        <div class="row">
            <?php 
            if ($anunciante == ""){
                echo ("<h2 align='center' data-trad='ACTUALIZAR PERFIL'>ACTUALIZAR PERFIL</h2>");
            } else {
                echo ("<h2 align='center' data-trad='ACTUALIZAR PERFIL'>ACTUALIZAR PERFIL</h2>");
            }
            ?>
            <br>
            <div class="row>" align="center">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-warning" data-trad="Guardar Datos" form="formulario">Guardar Datos</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <ul class="nav nav-tabs" id="formTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" data-trad="Cliente">Cliente</button>
            </li>
        </ul>
        <br>
        <form class="row needs-validation" id="formulario" method="POST" action="modificarFichaBD.php" onSubmit="return habilitarClienteTipo()"  novalidate>
            <div class="tab-content">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <h5 data-trad="CLIENTE">CLIENTE</h5>
                    <div class="form-group">

                        <div class="row" hidden>
                            <label for="idficha" class="col-sm-1 col-form-label col-form-label-sm" data-trad="Ficha">Ficha</label>
                            <div class="col-sm-1">
                                <input class="form-control form-control-sm" id="idficha" name="idficha" value="<?php echo $row['idficha']?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row" style="">
                            <label for="anunciante" class="col-auto col-form-label col-form-label-sm" 
                            data-trad="Usuario">Usuario</label>
                            <div class="col-auto">
                                <input class="form-control form-control-sm" id="anunciante" name="anunciante" value="<?php echo $row['anunciante']?>" readonly>
                            </div>
                        </div>

                        <?php if ($row['clientetipo'] == "O"){?>
                        <div class="row">
                            <div class="col-auto">
                                <input class="form-control form-control-sm" id="anuncianteinmob" name="anuncianteinmob" 
                                value="<?php echo $row['anuncianteinmob']?>" hidden></input
                            ></div>
                        </div>
                        <?php }
                        ?>

                        <div class="form-group">
                            <div>
                                <p></p>
                            </div>
                            <div class="form-check col-sm-4">
                                <label class="col-sm-2" for="clienteTipoPART" data-trad="Particular">Particular</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoPART" value="P" 
									   <?php
										if (isset($row['clientetipo']) && ($row['clientetipo'] == "P"  || $clientetipo == 'Q')){
											echo " checked";
										} else {
											echo " ";
										}
                                        echo " disabled"
									   ?>>
                            </div>
                            <div class="form-check col-sm-4">
                                <label class="col-sm-2" for="clienteTipoPROF" data-trad="Profesional">Profesional</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoPROF" value="R"
									   <?php
										if (isset($row['clientetipo']) && $row['clientetipo'] == "R"){
											echo " checked";
										} else {
											echo " ";
										}
                                        echo " disabled"
									   ?>>
                            </div>
                            <div class="form-check col-sm-4">
                                <label class="col-sm-2" for="clienteTipoOFI" data-trad="Vinculado">Vinculado</label>
                                <input class="col-sm-4" type="radio" name="clientetipo" id="clienteTipoOFI" value="O" 
									   <?php
										if (isset($row['clientetipo']) && $row['clientetipo'] == "O"){
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
                                <label for="nombrerz" class="col-sm-5 col-form-label col-form-label-sm" data-trad="Nombre o Razón Social">Nombre o Razón Social</label>
                                <input type="text" class="form-control form-control-sm" id="nombrerz" name="nombrerz" aria-describedby="inputGroupPrepend" value="<?php echo $row['nombrerz']?>" readonly>
                            </div>
                        </div>
                        
                        <div>
                            <p></p>
                        </div>
                        <!-- TERMINOS -->
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <?php
                            $fechaHoraAcept=Funciones::obtenerFechaHoraAcept($row['anunciante'], $con);
                            ?>
                            <label for="noTrack" class="m-0" data-trad="Condiciones de Uso leídas y aceptadas el">Condiciones de Uso leídas y aceptadas el</label>
                            <label class="mb-0">
                                <input type="text" class="form-control form-control-sm" id="fechaterminos" name="fechaterminos" value="<?php echo $fechaHoraAcept; ?>" disabled >
                            </label>
                        </div>

                        <!-- COMERCIALES -->
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <?php
                            if ($row['infonoticias'] == 0){?>
                                <label for="noTrack" class="m-0" data-trad="Acepto recibir comunicaciones comerciales de Plusdata">Acepto recibir comunicaciones comerciales de Plusdata</label>
                                <label class="switch m-0">
                                    <input type="checkbox" id="noTrack" name="infonoticias">
                                    <span class="slider"></span>
                                </label>
                            <?php } else {?>
                                <label for="noTrack" class="m-0" data-trad="Acepto recibir comunicaciones comerciales de Plusdata">Acepto recibir comunicaciones comerciales de Plusdata</label>
                                <label class="switch m-0">
                                    <input type="checkbox" id="noTrack" name="infonoticias" checked>
                                    <span class="slider"></span>
                                </label>
                                
                            <?php }?>
                        </div>
                    </div>

                    <br>
                    <h5 data-trad="DATOS FACTURACIÓN">DATOS FACTURACIÓN</h5>
                    <div class="form-group">
                        <div class="row mb-3">
                            <label for="calleynro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Calle y número">Calle y número</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="calleynro" name="calleynumeropartic" value="<?php echo $row['calleynumeropartic']?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="codigopostalpartic" class="col-sm-2 col-form-label col-form-label-sm " data-trad="Código postal y localidad">Código postal y localidad</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="codigopostalpartic" name="codigopostalpartic" value="<?php echo $row['codigopostalpartic']?>" required >
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="provinciapartic" class="col-sm-2 col-form-label col-form-label-sm" data-trad"Provincia">Provincia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="provinciapartic" name="provinciapartic" value="<?php echo $row['provinciapartic']?>" >
                            </div>
                        </div>
<!--
                        <div class="row mb-3">
                            <label for="regionpartic" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Libre">Libre</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="regionpartic" name="regionpartic" value="" readonly>
                            </div>
                        </div>
-->
                        <div class="row mb-3">
                            <label for="paispartic" class="col-sm-2 col-form-label col-form-label-sm" data-trad="País de Residencia">País de Residencia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="paispartic" name="paispartic" value="<?php echo $row['paispartic']?>" required >
                            </div>
                        </div>
                        <div class="row">
                            <label for="identificacionfiscalpartic" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Número Indentificación Fiscal">Número DE Identificación Fiscal</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control form-control-sm" id="identificacionfiscalpartic" name="identificacionfiscalpartic" 
                                value="<?php echo $row['identificacionfiscalpartic']?>" required >
                            </div>
                        </div>
<!--
                        <div class="row">
                            <div class="col-xs-12 col-sm-5">
                                <div class="form-group mb-7">
                                    <label for="tipoDOC" class="col-sm-12 col-form-label col-form-label-sm" data-trad="Tipo/Nro. Documento">Tipo/Nro. Documento</label>
                                    <div class="input-group col-sm-6">
                                        <div class="col col-sm-5">
                                            <select class="form-select form-select-sm" aria-label="Default select example" id="tipoDOC" name="tipodoc">
                                                <option selected value="<?php echo $row['tipodoc']?>"><?php echo $row['tipodoc']?></option>
                                                <option value="DNI">DNI</option>
                                                <option value="PASAPORTE" data-trad="PASAPORTE">PASAPORTE</option>
                                                <option value="OTRO" data-trad="OTRO">OTRO</option>
                                            </select> 	
                                        </div>
                                        <div class="col">
                                            <input name="nrodocumentopartic" id="nrodocumentopartic" class="form-control form-control-sm mb-3" value="<?php echo $row['nrodocumentopartic']?>">
                                        </div>
                                    </div>
                                </div>    
                            </div>	
                        </div>
                    </div>
-->
                    </div>
                    <br>
                    <h5 data-trad="TELEFONOS">TELEFONOS</h5>
                    <div class="form-group">
                        <div class="row mb-3">
                            <label for="telfijoFuera" class="col-sm-2 col-form-label col-form-label-sm"><img src="./imagenes/telFijo.png" width="40" id="icotelfijo" title="FIJO"></label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" type="tel" id="telfijoFuera" name="telfijoFuera" value="<?php echo $row['telfijo']?>">
                                <input type="hidden" id="telfijo" name="telfijo">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="telmovilFuera" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Móvil registro">Móvil registro</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" type="tel" id="telmovilFuera" name="telmovilFuera" value="<?php echo $row['telmovil']?>" required readonly>
                                <input type="hidden" id="telmovil" name="telmovil">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nrofax" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Móvil alternativo">Móvil alternativo</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" type="tel" id="nrofaxFuera" name="nrofaxFuera" value="<?php echo $row['nrofax']?>">
                                <input type="hidden" id="nrofax" name="nrofax">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="nrowspFuera" class="col-sm-2 col-form-label col-form-label-sm p-0">
                                <img src="./imagenes/WSP.png" id="icoWSP" title="WhatsApp" class="icono-mensaje">
                            </label>

                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" 
                                       type="tel" 
                                       id="nrowspFuera" 
                                       name="nrowspFuera" 
                                       value="<?php echo $row['nrowsp']?>">
                                <input type="hidden" id="nrowsp" name="nrowsp">
                            </div>
                        </div>  

                        <div class="row mb-3 align-items-center">
                            <label for="telegramFuera" class="col-sm-2 col-form-label col-form-label-sm p-0">
                                <img src="./imagenes/telegram.png" id="icoTLGM" title="Telegram" class="icono-mensaje">
                            </label>

                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" 
                                       type="tel" 
                                       id="telegramFuera" 
                                       name="telegramFuera" 
                                       value="<?php echo $row['telegram']?>">
                                <input type="hidden" id="telegram" name="telegram">
                            </div>
                        </div>  
                        <div class="row mb-3">
                            <label for="otramensFuera" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Otra Mensajería">Otra Mensajería</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" type="tel" id="otramensFuera" name="otramensFuera" value="<?php echo $row['otramens']?>">
                                <input type="hidden" id="otramens" name="otramens">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 data-trad="CORREO ELECTRONICO">CORREO ELECTRONICO</h5>
                    <div class="form-group">
                        <div class="row mb-3">
                            <label for="emailpersonal" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Correo Personal">Correo Personal</label>
                            <div class="col-sm-3">
                                <input type="email" class="form-control form-control-sm" id="emailpersonal" name="emailpersonal" value="<?php echo $row['emailpersonal']?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="emaillaboral" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Correo de registro">Correo de registro</label>
                            <div class="col-sm-3">
                                <input type="email" class="form-control form-control-sm" id="emaillaboral" name="emaillaboral" value="<?php echo $row['emaillaboral']?>" required readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="emailalternativo" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Correo Alternativo">Correo Alternativo</label>
                            <div class="col-sm-3">
                                <input type="email" class="form-control form-control-sm" id="emailalternativo" name="emailalternativo" value="<?php echo $row['emailalternativo']?>">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div>
                        <h5 style="margin-bottom: 0" data-trad="DATOS DE CONTACTO PARA TUS ANUNCIOS">DATOS DE CONTACTO PARA TUS ANUNCIOS</h5>
                        <h6 style="font-style: italic; font-size: 13px; margin-top: 0; color: darkgreen" data-trad="Aparecerán en tus anuncios y en el cartel de oferta. Son obligatorios.">Aparecerán en tus anuncios y en el cartel de oferta. Son obligatorios.</h6>
                    </div>
                    <?php 
                        $readOnly = "";
                        if ($clientetipo === 'P' || $clientetipo === 'Q' ) {
                            $readOnly = "readonly";
                        }
                    ?>

                    <div class="row mb-3">
                        <label for="contactonombre" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Nombre">Nombre</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control form-control-sm" id="contactonombre" name="contactonombre"
                                   value="<?php echo $row['contactonombre']?>" required <?php echo $readOnly?> >
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="contactotelFuera" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Teléfono">Teléfono</label>
                        <div class="col-sm-3">
                            <input class="form-control form-control-sm" type="tel" id="contactotelFuera" name="contactotelFuera" 
                                   value="<?php echo $row['contactotel']?>">
                            <input type="hidden" id="contactotel" name="contactotel">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="contactoemail" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Correo Electrónico">Correo Electrónico</label>
                        <div class="col-sm-3">
                            <input type="email" class="form-control form-control-sm" id="contactoemail" name="contactoemail" 
                                   value="<?php echo $row['contactoemail']?>">
                        </div>
                    </div>
<!--
                    <br>
                    <h5 data-trad="DATOS FACTURACIÓN">DATOS FACTURACIÓN</h5>
                    <div class="form-group">
                        <div class="form-check col-sm-6">
                            <label class="col-sm-3" for="radfacturacion" data-trad="Mismos datos">Mismos datos</label>
                            <input class="col-sm-3" type="radio" name="radfacturacion" id="radfacturacionpart" value="1"
								   	<?php
//										if (isset($row['radfacturacion']) && $row['radfacturacion'] == "1"){
										if ($radfacturacion == "1"){
											echo " checked";
										} else {
											echo " ";
										}
									?>>
                        </div>
                        <div class="form-check col-sm-6">
                            <label class="col-sm-3" for="radfacturacion" data-trad="Cambiar datos">Cambiar datos</label>
                            <input class="col-sm-3" type="radio" name="radfacturacion" id="radfacturacionprof" value="2"
								   	<?php
										if ($radfacturacion == "2"){
											echo " checked";
										} else {
											echo " ";
										}
									?>>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <label for="nombreotro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Nombre o Razón Social">Nombre o Razón Social</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="nombreotro" name="nombreotro" value="<?php echo $row['nombreotro']?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="calleynrootro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Calle y número">Calle y número</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="calleynrootro" name="calleynrootro" value="<?php echo $row['calleynrootro']?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="codigopostalotro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Código postal y localidad">Código postal y localidad</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="codigopostalotro" name="codigopostalotro" value="<?php echo $row['codigopostalotro']?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="provinciaotro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Provincia">Provincia</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="provinciaotro" name="provinciaotro" value="<?php echo $row['provinciaotro']?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="paisotro" class="col-sm-2 col-form-label col-form-label-sm" data-trad="País">País</label>
                            <div class="col-sm-3">
                                <input class="form-control form-control-sm" id="paisotro" name="paisotro" value="<?php echo $row['paisotro']?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="identificacionfiscalpartic" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Número Indentificación Fiscal">Número Indentificación Fiscal</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control form-control-sm" id="identificacionfiscalpartic" name="identificacionfiscalpartic" 
                                value="<?php echo $row['identificacionfiscalpartic']?>" required >
                            </div>
                        </div>

                    </div>
-->
                    <?php if (!$esRegisto && $_SESSION['clientetipo'] <> "P" && $_SESSION['clientetipo'] <> "Q") { ?>
                    <br>
                    <h5 data-trad="BANNER">BANNER</h5>
                    <div class="row mb-12">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="frame">
                                <label for="imagenBanner" class="col-form-label col-form-label-sm" data-trad="Así verán tu banner los usuarios">Así verán tu banner los usuarios</label>
                                <p class="outer_div_img mb-0" id="imagenBanner"></p>
                            </div>

                            <section class="mb-5">
                                <iframe id="panelCargaIMG" name="panelCargaIMG" scrolling="yes"
                                        class="iframe-borde mb-5"
                                        style="width: 100%; min-height: 260px; border: 0 solid #ccc;"
                                        src="indexBanners.php"></iframe>
                            </section>
                        </div>
                    </div>                    
                    <?php } ?>
                </div>
<!--

			    <div class="tab-pane" id="datos3" role="tabpanel" aria-labelledby="datos3-tab">
						<div class="row">
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-warning" data-trad="Guardar Datos">Guardar Datos</button>
                            </div>
                        </div>
                    <br>
                    <h5 data-trad="IDIOMAS QUE ATIENDE">IDIOMAS QUE ATIENDE</h5>
                    <div class="row form-control">
                        <div class="col-sm-9">
                            <img src="./imagenes/bandera_españa.png" class="img-thumbnail" width="40" id="fotoespana">   
                            <label for="fotoespana" class="col-sm-1 col-form-label col-form-label-sm">ESPAÑOL</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkespanol" name="checkespanol" <?php //if (isset($row['checkespanol']) && $row['checkespanol'] == '1') {
													//echo " checked";
											//	} ?>>

                            <img src="./imagenes/bandera_catala.png" class="img-thumbnail" width="40" id="fotocatala">                
                            <label for="fotocatala" class="col-sm-1 col-form-label col-form-label-sm">CATALÁ</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkcatala" name="checkcatala" <?php //if (isset($row['checkcatala']) && $row['checkcatala'] == '1') {
													//echo " checked";
											//	} ?>>

                            <img src="./imagenes/bandera_gallega.png" class="img-thumbnail" width="40" id="fotogallega">                
                            <label for="fotogallega" class="col-sm-1 col-form-label col-form-label-sm">GALLEGO</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkgallego" name="checkgallego" <?php //if (isset($row['checkgallego']) && $row['checkgallego'] == '1') {
													//echo " checked";
											//	} ?>>

                            <img src="./imagenes/bandera_eusquera.png" class="img-thumbnail" width="40" id="fotoeuskera">                
                            <label for="fotoeuskera" class="col-sm-1 col-form-label col-form-label-sm">EUSKERA</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkeusquera" name="checkeusquera" <?php //if (isset($row['checkeusquera']) && $row['checkeusquera'] == '1') {
													//echo " checked";
								//  } ?>>

                            <img src="./imagenes/bandera_francesa.png" class="img-thumbnail" width="40" id="fotofrancia">                
                            <label for="fotofrancia" class="col-sm-1 col-form-label col-form-label-sm">FRANÇAIS</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkfrances" name="checkfrances" <?php //if (isset($row['checkfrances']) && $row['checkfrances'] == '1') {
													//echo " checked";
												//} ?>>

                            <img src="./imagenes/bandera_inglesa.png" class="img-thumbnail" width="40" id="fotoinglesa">                
                            <label for="fotoinglesa" class="col-sm-1 col-form-label col-form-label-sm">ENGLISH</label>
                            <input class="form-check-input col-sm-1 col-form-label col-form-label-sm" type="checkbox" value="1" id="checkingles" name="checkingles" <?php //if (isset($row['checkingles']) && $row['checkingles'] == '1') {
												//	echo " checked";
								//				} ?>>

                        </div>
                    </div>
                </div>
-->
<!--
                <div class="tab-pane" id="datos4" role="tabpanel" aria-labelledby="datos3-tab">
						<div class="row">
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-warning" data-trad="Guardar Datos">Guardar Datos</button>
                            </div>
                        </div>
                    <br>
                    <h5 data-trad="MEDIOS DE PAGO">MEDIOS DE PAGO</h5>
                    <div class="form-group">
                            <div class="col-sm-2">
                                <label for="checktarj" class="col-form-label col-form-label-sm" data-trad="Trajeta Crédito o Débito">Trajeta Crédito o Débito</label>
                                <input class="form-check-input col-form-label col-form-label-sm" type="checkbox" value="1" id="checktarj" name="checktarj"
   								   	<?php
										/*if (isset($row['checktarj']) && $row['checktarj'] == "1"){
											echo " checked";
										} else {
											echo " ";
										} */
									?>>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control form-control-sm" id="nombretarjetasel" name="nombretarjetasel" >
                                    <option selected value="<?php //echo $row['nombretarjetasel']?>"><?php // echo $row['nombretarjetasel']?></option>
                                    <option value="VISA">VISA</option>
                                    <option value="MASTERCARD">MASTERCARD</option>
                                    <option value="AMERICAN EXPRESS">AMERICAN EXPRESS</option>
                                </select> 	
                            </div>
                            <br>
                            <div class="row mb-3">
                                <label for="nrotarjeta" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Número">Número</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="nrotarjeta" name="nrotarjeta" value="<?php //echo $row['nrotarjeta']?>"> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nombretarjeta" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Nombre como en tarjeta">Nombre como en tarjeta</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="nombretarjeta" name="nommbretarjeta" value="<?php //echo $row['nommbretarjeta']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="vtotarjeta" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Vencimiento">Vencimiento</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="vtotarjeta" name="vtotarjeta" value="<?php //echo $row['vtotarjeta']?>">
                            	</div>
							</div>
                            <div class="row mb-3">
                               <label for="codsegtarjeta" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Código de seguridad">Código de seguridad</label>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control form-control-sm" id="codsegtarjeta" name="codsegtarjeta" value="<?php// echo $row['codsegtarjeta']?>">
                                </div>
                            </div>
                            <h5 data-trad="DATOS BANCARIOS">DATOS BANCARIOS</h5>
                            <div class="row mb-3">
                                <label for="transfbancaria" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Pago por Transf Bancaria">Pago por Transf Bancaria</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="transfbancaria" name="transfbancaria" value="<?php // echo $row['transfbancaria']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="iban" class="col-sm-2 col-form-label col-form-label-sm" data-trad="IBAN Cuenta Bancaria">IBAN Cuenta Bancaria</label>
                                <div class="col-sm-3">
                                    <input class="form-control form-control-sm" id="iban" name="iban" value="<?php //echo $row['iban']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="factacum" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Facturación Acumulada">Facturación Acumulada</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" id="factacum" name="factacum" value="<?php // echo $row['factacum']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="factacum" class="col-sm-2 col-form-label col-form-label-sm" data-trad="Saldo monedero">Saldo monedero</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm" id="avisosdisponibles" name="avisosdisponibles" value="<?php // echo $row['avisosdisponibles']?>" readonly>
                                </div>
                            </div>
                        </div>
                        <br>
          		</div>
-->
            </div>    
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
 
    <script>
        $(document).ready(function () {
            const input = document.getElementById("telmovilFuera");
            const iti = window.intlTelInput(input, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti.setNumber(input.value);
            window.iti = iti;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti.getNumber();
                document.querySelector("#telmovil").value = phoneNumber;
            });

            document.querySelector("#telmovilFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("telmovil");
                const phoneNumber = iti.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

// -------------------------------------------------------------------

            const input1 = document.getElementById("telfijoFuera");
            const iti1 = window.intlTelInput(input1, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            // Establecer un número en formato internacional
            iti1.setNumber(input1.value);
            window.iti1 = iti1;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti1.getNumber();
                document.querySelector("#telfijo").value = phoneNumber;
            });

            document.querySelector("#telfijoFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("telfijo");
                const phoneNumber = iti1.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------

            const input2 = document.getElementById("nrowspFuera");
            const iti2 = window.intlTelInput(input2, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti2.setNumber(input2.value);
            window.iti2 = iti2;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti2.getNumber();
                document.querySelector("#nrowsp").value = phoneNumber;
            });

            document.querySelector("#nrowspFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("nrowsp");
                const phoneNumber = iti2.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------
            const input3 = document.getElementById("nrofaxFuera");
            const iti3 = window.intlTelInput(input3, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti3.setNumber(input3.value);
            window.iti3 = iti3;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti3.getNumber();
                document.querySelector("#nrofax").value = phoneNumber;
            });

            document.querySelector("#nrofaxFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("nrofax");
                const phoneNumber = iti3.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------

            const input4 = document.getElementById('telegramFuera');
            const iti4 = window.intlTelInput(input4, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti4.setNumber(input4.value);
            window.iti4 = iti4;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti4.getNumber();
                document.querySelector("#telegram").value = phoneNumber;
            });

            document.querySelector("#telegramFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("telegram");
                const phoneNumber = iti4.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------

            const input5 = document.getElementById('otramensFuera');
            const iti5 = window.intlTelInput(input5, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti5.setNumber(input5.value);
            window.iti5 = iti5;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti5.getNumber();
                document.querySelector("#otramens").value = phoneNumber;
            });

            document.querySelector("#otramensFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("otramens");
                const phoneNumber = iti5.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------

            const input6 = document.getElementById('contactotelFuera');
            const iti6 = window.intlTelInput(input6, {
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto", // Detecta el país automáticamente
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
                  .then(response => response.json())
                  .then(data => callback(data.country))
                  .catch(error => console.error(error));
              },
              nationalMode: false, // Usa el formato internacional
              separateDialCode: true,
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" // Necesario para formateo
            });

            // Establecer un número en formato internacional
            iti6.setNumber(input6.value);
            window.iti6 = iti6;

            document.querySelector("#formulario").addEventListener("submit", function(event) {
                var phoneNumber = iti6.getNumber();
                document.querySelector("#contactotel").value = phoneNumber;
            });

            document.querySelector("#contactotelFuera").addEventListener("blur", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById("contactotel");
                const phoneNumber = iti6.getNumber();
                salidaTel.value=phoneNumber;
                console.log("Para grabar: "+phoneNumber);
            });

//----------------------------------------------------------------------------
        });
        function habilitarClienteTipo(){
            if (document.getElementById('contactonombre').value == '' &&
                document.getElementById('contactotelFuera').value == '' &&
                document.getElementById('contactoemail').value == ''){ 
                } else {
                    if (document.getElementById('contactonombre').value == '' ||
                        document.getElementById('contactotelFuera').value == '' ||
                        document.getElementById('contactoemail').value == ''){ 
                            alert("ERROR!!! Tiene que informar todos los datos de contacto...");
                            event.preventDefault();
                            return false;
                    }
                }
            document.getElementById("clienteTipoPART").disabled = false;
            document.getElementById("clienteTipoPROF").disabled = false;
            document.getElementById("clienteTipoOFI").disabled = false;
            document.getElementById("anunciante").disabled = false;
            document.getElementById("idficha").disabled = false;
//            document.getElementById("anuncianteinmob").disabled = false;
//            document.getElementById("avisosdisponibles").disabled = false;

            document.getElementById("telfijoFuera").disabled = true;
            document.getElementById("telmovilFuera").disabled = true;
            document.getElementById("nrofaxFuera").disabled = true;
            document.getElementById("nrowspFuera").disabled = true;
            document.getElementById("nrofaxFuera").disabled = true;
            document.getElementById("telegramFuera").disabled = true;
            document.getElementById("otramensFuera").disabled = true;
            document.getElementById("contactotelFuera").disabled = true;
        }

    function cargarBanner(){
/*
        var parametros = {
            "action": "ajax",
            "nro_en_form": "img",
            "titulo": "IMAGENES"
//            "agrpradio":"N"
        };
*/
       $.ajax({
            url: 'ajax/mostrarBanner.php',
//            data: parametros,
            beforeSend: function(objeto) {},
            success: function(data) {
                $(".outer_div_img").html(data).fadeIn('slow');
            }
        });
    }
            
    function borrarBanner(){
        var result = confirm('¿Borrar Banner?');
         if(result){
            window.open("BorrarBanner.php", "_self");
        }
        return result;
    }

    </script>

    <script>
        window.addEventListener("message", function(event) {
          if (event.data === "Proceso terminado") {
            console.log("El proceso dentro del iframe ha finalizado.");
            cargarBanner();
          }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>

</body>
</html>
