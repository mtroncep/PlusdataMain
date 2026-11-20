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
<link rel="stylesheet" href="css/dropzone.css">
<link rel="stylesheet" href="css_new/style.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="js/dropzone.js"></script>
<!-- <script src="js/app.js"></script> -->
</head>
<body>
<div class="container">
    <h1 class="text-center"> Imagenes de la Propiedad</h1>
<!--    <h2 class="lead text-center">Seleccionar imagenes o depositarlas en el recuadro</h2> -->
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

    <div class="row">
        <div id="content" class="col-lg-12">
            <form class="dropzone" id="myDrop" action="upload.php" method="post">
                <div class="dz-default dz-message"><span>Deposite acá las imagenes o Clic para seleccionar</span></div>
            </form>
        </div>
    </div>

    <div class="footer-content row">
        <div class="col-lg-12">
            <div class="card-body text-center mt-4 mb-4">
                <?php
                    $volver_a="pruebaActualizarDetalles.php?operacion=M*anuncio=".$_SESSION['anuncio'];
                    unset($_SESSION['windowCerrar']);
                ?>
                <a href="indexCarouselMarca.php?elAnuncio=<?php echo $_SESSION['anuncio'];?>&volver_a=<?php echo $volver_a ?>" class="btn btn-primary" style="text-align: center;">
                    <i class="fa fa-eye"></i> Ver Imágenes
                </a>
                <a href="pruebaActualizarDetalles.php?operacion=M&anuncio=<?php echo $_SESSION['anuncio'];?>&volver_a=<?php echo $volver_a ?>" class="btn btn-primary" style="text-align: center;">
                    <i class="fa fa-eye"></i> Volver
                </a>
            </div>
        </div>
    </div>
    
</div>
<script>
//Get the template HTML and remove it from the doument
var previewNode = document.querySelector("#template");
previewNode.id = "prevId";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

/*
var myDropzone = new Dropzone(document.body, {
    url: "upload.php",
    paramName: "file",
    acceptedFiles: 'image/*',
    maxFilesize: 2,
    maxFiles: 3,
    thumbnailWidth: 160,
    thumbnailHeight: 160,
    thumbnailMethod: 'contain',
    previewTemplate: previewTemplate,
    autoQueue: true,
    previewsContainer: "#previews",
    clickable: "#previews"
//    clickable: ".fileinput-button"
});

myDropzone.on("addedfile", function(file) {
    $('.dropzone-here').hide();
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
});

myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
    //document.querySelector("#total-progress").style.opacity = "0";
});

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
*/
/*
document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
};
    
$('#previews').sortable({
    items:'.file-row',
    cursor: 'move',
    opacity: 0.5,
    containment: "parent",
    distance: 20,
    tolerance: 'pointer',
    update: function(e, ui){
        //actions when sorting
    }
})
*/
;
</script>
</body>
</html>
