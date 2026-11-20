<?php
      if(!isset($_POST['enviar'])){
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Subir imagen</title>
</head>
 
<body>
<form method='post' action='?subida' enctype='multipart/form-data'>
    <table width="100%" border="0" cellspacing="10">
        <tr> 
            <td align="center"><h1 style="text-align:center;">Subir imagen</h1></td>
        </tr>
        <tr>
          <td align="center">Selecciona la imagen<br /><input name="img1" type="file" /></td>
        </tr>

        <tr>
            <td align="center">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" ><input name='enviar' type='submit' value='Subir' />
        </tr>
    </table>
</form>
<?php
   } else {
      //SUBIMOS LA IMAGEN
      if (is_uploaded_file($_FILES['img1']['tmp_name'])){
          echo "tmp_file: ".$_FILES['img1']['tmp_name'].'<br>';
      //recojo la imagen
      $imagen = $_FILES['img1']['name'];
      echo "img1: ".$imagen.'<br>';
      //Obtengo el nombre de la imagen y la extensión de la foto
      $imagen1 = explode(".",$imagen);
      if(($imagen1[1] == "jpg") || ($imagen1[1] == "png")){
              //Genero un nombre aleatorio con números y se asigno la extensión obtenida anteriormente
              $imagen2 = rand(0,9).rand(100,9999).rand(100,9999).".".$imagen1[1];
            //Coloco la imagen en la carpeta correspondiente con el nuevo nombre
            move_uploaded_file($_FILES['img1']['tmp_name'], "banners/".$imagen2);
            echo "imagen2: ".$imagen2.'<br>';
            //Asigno a la foto permisos
            $ruta="banners/".$imagen2;
            chmod($ruta,0777);
                //MANDAMOS LA URL AL FORMULARIO
//            echo "<script>
//                  window.opener.document.getElementById('img1').value = 'localhost/HMIPrueba/banners/".$imagen2."';
//                    alert('Imagen subida correctamente');
//                  window.close();
//                  </script>";
            }else{
                  echo "<br><br><p align='center'><span style='color:red;font-size:28px;'>Solo se permiten fotograf&iacute;as .jpg y .png</span></p>";
                  echo "<p align='center'><a href='subir_imagenes.php'><span style='font-size:28px;'>Volver atr&aacute;s</span></a></p>";
            }
      }                 
}
?></center>
</body>
</html>