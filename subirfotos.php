<?php
include_once 'E:/Descargas/google-api-php-client--PHP7.0/vendor/autoload.php';

function subirDocumentoDrive($documento,$descripcion){
    // Variables de credenciales.
    $claveJSON = '1qSuy0f5WOGd1FCqSv7emcPtHclT39XvE';
    $pathJSON = 'messaging-67753-a6c4365e67b6.json';
    
    https://drive.google.com/drive/folders/1qKTBAOXqB3d90OSubbp6Nvh10CQdFpXb?usp=sharing
    //configurar variable de entorno
    putenv('GOOGLE_APPLICATION_CREDENTIALS='.$pathJSON);

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setScopes(['https://www.googleapis.com/auth/drive.file']);
    try{		
        //instanciamos el servicio
        $service = new Google_Service_Drive($client);

        //instacia de archivo
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($documento);

        //obtenemos el mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        $mime_type=finfo_file($finfo, $documento);

        //id de la carpeta donde hemos dado el permiso a la cuenta de servicio 
        $file->setParents(array($claveJSON));
        $file->setDescription($descripcion);
        $file->setMimeType($mime_type);

        $result = $service->files->create(
          $file,
          array(
            'data' => file_get_contents($documento),
            'mimeType' => $mime_type,
            'uploadType' => 'media',
          )
        );
        /* FICHERO SUBIDO A GOOGLE DRIVE */
        echo "Fichero subido a Google Drive.";
    }catch(Google_Service_Exception $gs){
        $m=json_decode($gs->getMessage());
        echo $m->error->message;
    }catch(Exception $e){
        echo $e->getMessage();
      
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $documento = htmlspecialchars($_FILES['documento']['name']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    
    // Subimos el documento a nuestro servidor.
    if(move_uploaded_file($_FILES['documento']['tmp_name'], $documento)){
        if (subirDocumentoDrive($documento,$descripcion)){
			echo "Fichero subido al servidor.";
		}
/*        
        if (unlink($documento)){
            echo "3.- Fichero eliminado del servidor";
        }else{
            echo 'Error: No se ha podido eliminar el documento "'.$documento.'" en el servidor.';
        }		
    }else{
        echo "Error: Se ha producido un error, intentelo de nuevo.";
    }
*/    
	}
}
?>
<html>
    <head>
        <title>Subir ficheros a Google drive.</title>
    </head>
    <body>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <label for="documento">Documento:</label>
            <input type="file" name="documento" required /><br>
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" required /><br>
            <input type="submit" name="enviar" value="Subir documento a Google Drive" />
        </form>
    </body>
</html>
