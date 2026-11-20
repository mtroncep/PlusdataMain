<!DOCTYPE html>
<?php
	session_start();
	require_once("conexion.php");
	require_once("cargarDatosFormulario.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dropzone Múltiple subida de archivos con Bootstrap Demo</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!-- <link rel="stylesheet" href="css/dropzone.css">
<script src="js/dropzone.js"></script> -->
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<style>
    #myDrop.dropzone {
      display: block;
      width: 100% !important;
      max-width: 100%;
      min-height: 150px;
      margin: 0;
      padding: 20px;
      box-sizing: border-box;
      float: none;
    }

    #myDrop .dz-message {
      width: 100%;
      margin: 1em 0;
      text-align: center;
      white-space: normal;
      word-break: normal;
    }

    #content {
      width: 100%;
      float: none;
      padding-left: 15px;
      padding-right: 15px;
    }
    
    #myDrop.dropzone {
      display: block;
      width: 100% !important;
      max-width: 100%;
      margin: 0;
      box-sizing: border-box;
    }

    #myDrop .dz-message {
      margin: 1.5em 0;
      text-align: center;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>

</head>
<body>
<div class="container">
<div class="table table-striped files" id="previews">
        <div id="template" class="file-row row">
            <!-- This is used as the file preview template -->
            <div class="col-xs-12 col-lg-3">
                <span class="preview" style="width:160px;height:160px;">
                    <img data-dz-thumbnail />
                </span>
                <br/>
                <button class="btn btn-primary start" style="display:none;">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Empezar</span>
                </button>
                <button data-dz-remove class="btn btn-warning cancel">
                    <i class="icon-ban-circle fa fa-ban-circle"></i> 
                    <span>Cancelar</span>
                </button>
                <button data-dz-remove class="btn btn-danger delete">
                    <i class="icon-trash fa fa-trash"></i> 
                    <span>Eliminar</span>
                </button>
            </div>
            <div class="col-xs-12 col-lg-9">
                <p class="name" data-dz-name></p>
                <p class="size" data-dz-size></p>
                <div>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                      <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        if ($_SESSION['clientetipo'] <> "P" && $_SESSION['clientetipo'] <> "Q" ){
    ?>
    <div class="row" style="font-style: italic; font-size: 13px; margin-top: 0; color: black">
        <div id="content" class="col-lg-4">
            <form class="dropzone" id="myDrop" action="uploadBanner.php" method="post">
                <div class="dz-default dz-message" name="file" ><span>Haz CLIC aquí (o suelta el archivo) para subir o cambiar tu banner</span></div>
                <div class="dz-default dz-message" name="texto"><span>Formato JPG o PNG – Pixeles: 340x100</span></div>
            </form>
        </div>
    </div>
    <?php } ?>
</div>
<script>
Dropzone.autoDiscover = false;

//Get the template HTML and remove it from the doument
var previewNode = document.querySelector("#template");
previewNode.id = "prevId";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

document.addEventListener("DOMContentLoaded", function() {
    var myDrop = new Dropzone("#myDrop", {
    url: "uploadBanner.php",
    maxFilesize: 2,
    acceptedFiles: "image/*",
  });

  // Evento cuando se agrega un archivo
  myDrop.on("addedfile", function(file) {
//    console.log("Archivo añadido:", file.name);
  });

  // Evento cuando el archivo se sube correctamente
  myDrop.on("success", function(file, response) {
//    console.log("Subida exitosa:", response);
    $('.dropzone')[0].dropzone.files.forEach(function(file) { 
      file.previewElement.remove(); 
    });

    $('.dropzone').removeClass('dz-started');
/*    
    console.log("Response: "+response);
    let ejecutarComando = new Function(response);
    ejecutarComando();
*/      
    window.parent.postMessage("Proceso terminado", "*");

  });


  myDrop.on("complete", function(file, errorMessage) {
//    console.log("Archivo finalizado de cargar:", errorMessage);
  });

// Evento si ocurre un error
  myDrop.on("error", function(file, errorMessage) {
//    console.log("Error al subir:", errorMessage);
  });
});

document.getElementById("myDrop").addEventListener("drop", (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;

    if (event.target === event.relatedTarget || event.target.closest('.dropzone')) {
      // El archivo se soltó dentro de la zona
//      myDrop.enqueue(files); // o Dropzone.prototype.addedfile(files);
    } else {
      // El archivo se soltó fuera de la zona
      // Implementar la lógica de manejo
      alert("El archivo no se soltó dentro de la zona");
    }
});
    
</script>
</body>
</html>
