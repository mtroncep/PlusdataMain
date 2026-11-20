jQuery(document).ready(Function($) {

    Dropzone.options.myDrop = {
        uploadMultiple: true,
        maxFilesSize: 3,
        acceptedFiles: 'image/*',
        
        init: function init() {
            this.on('error', function(){
                alert('Error al subir la imagen');
            });
        }
    }
});