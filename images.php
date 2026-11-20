<?php
function showFiles($path) {
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {
            if(is_dir($path.$current)) {
                showFiles($path.$current.'/');
            }
            else {
                $files[] = $current;
            }
        }
    }
//    echo '<h2>'.$path.'</h2>';
    echo '<div class="row">';
    for ($i=0; $i<count( $files ); $i++) {
        echo '<div class="col-xs-12 col-lg-2 text-center" style="margin-bottom:10px;">';
        echo '<span class="preview" style="width:160px;height: 160px;margin-bottom:7px;"> ';
        echo '<img src="fotosPublicaciones/'.$files[$i].'" style="max-width:160px;max-height: 160px;" />';
        echo '</span>';
        echo '<a class="btn btn-primary" href="images.php?remove='.$files[$i].'">Eliminar</a>';
        echo '</div>';
    }
    echo '</div>';
}

if (isset($_GET['remove'])) {
    if (file_exists('fotosPublicaciones/'.$_GET['remove'])) {
        unlink('fotosPublicaciones/'.$_GET['remove']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dropzone Bootstrap Demo - Imágenes guardadas</title>
<meta name="description" content="Dropzone Bootstrap Demo - Imágenes guardadas."/>
<meta name="author" content="Jose Aguilar">
<link rel="shortcut icon" href="https://www.jose-aguilar.com/blog/wp-content/themes/jaconsulting/favicon.ico" />
<link rel="stylesheet" href="css/font-awesome.min.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/styles.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
    <div style="text-align: center;">
        <h1>Imágenes guardadas</h1>
    </div>
    
    <div class="row">
        <div id="content" class="col-lg-12">
            <?php showFiles('fotosPublicaciones/'); ?>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card-body text-center mt-4 mb-4">
            <a href="indexImagenes.php" class="btn btn-primary" style="text-align: center;">
                <i class="fa fa-eye"></i> Volver
            </a>
        </div>
    </div>

</div>
</body>
</html>
