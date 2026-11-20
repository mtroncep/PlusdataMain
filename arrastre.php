<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<style>
    .container {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 10px;
    }

    .box {
      border: 1px solid #666;
      cursor: move;
    }
    
    .box.over {
      border: 1px dotted #666;
    }
</style>
    
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', (event) => {
    function handleDrop(e) {
      e.stopPropagation();

      if (dragSrcEl !== this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
      }

      return false;
    }

    function handleDragStart(e) {
      this.style.opacity = '0.4';

      dragSrcEl = this;

      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', this.innerHTML);
    }
    
  function handleDragEnd(e) {
    this.style.opacity = '1';

    items.forEach(function (item) {
      item.classList.remove('over');
    });
  }

  function handleDragOver(e) {
    if (e.preventDefault) {
      e.preventDefault();
    }

    return false;
  }

  function handleDragEnter(e) {
    this.classList.add('over');
  }

  function handleDragLeave(e) {
    this.classList.remove('over');
  }

  let items = document.querySelectorAll('.container .box');
  items.forEach(function(item) {
    item.addEventListener('dragstart', handleDragStart);
    item.addEventListener('dragover', handleDragOver);
    item.addEventListener('dragenter', handleDragEnter);
    item.addEventListener('dragleave', handleDragLeave);
    item.addEventListener('dragend', handleDragEnd);
    item.addEventListener('drop', handleDrop);
  });
});
</script>
    
<body>
    
<?php
// File and new size
function obtenerImagenReordenar($filename) {
    list($width, $height, $type) = getimagesize($filename);
    
    if( $type == IMAGETYPE_JPEG ) {
        $image = imagecreatefromjpeg($filename);
    }
    elseif( $type == IMAGETYPE_PNG ) {
        $image = imagecreatefrompng($filename);
    }
    elseif( $type == IMAGETYPE_GIF ) {
        $image = imagecreatefromgif($filename);
    }
    return $image;
    }
    
function scale_image($scale, $image, $width, $height) {
    $new_width = $width * $scale;
    $new_height = $height * $scale;
    return resize_image($new_width, $new_height, $image, $width, $height);
    }

function resize_image($new_width, $new_height, $image, $width, $height) {
    $image12 = 'fotosPublicaciones/hello.jpg';
    $new_imag = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($new_imag, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    return imagejpeg($new_imag, $image12);
    }
    
    $filename = "fotosPublicaciones/Amanda_1.jpg";
    list($width, $height, $type) = getimagesize($filename);
    $old_image = obtenerImagenReordenar($filename);
    $image_scaled = scale_image(0.8, $old_image, $width, $height);
?>
      <div draggable="true" class="box"><img src="<?php echo($image_scaled)?>"></div>
<!--    <div class="container">
      <div draggable="true" class="box"><img src="fotosPublicaciones/Amanda_1.jpg" width="350" height="450"></div>
      <div draggable="true" class="box"><img src="fotosPublicaciones/Cabaña_101.jpg" width="350" height="450"></div>
      <div draggable="true" class="box"><img src="fotosPublicaciones/1219a71a8f7b39ade55a5dcbb8b10125.jpg" width="350" height="450"></div>
    </div>
    -->
</body>

</html>