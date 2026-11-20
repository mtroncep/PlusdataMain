<!doctype html>
<?php
session_start();

/*
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=documento.pdf");
readfile("informes/estadoAnunciosGlobal/EstadoAnuncios_Anunciante_8_16-05-2024.pdf");
*/
/*
$arrFiles = array();
$handle = opendir('informes/estadoAnunciosGlobal');
if ($handle) {
    while (($entry = readdir($handle)) !== FALSE) {
        $arrFiles[] = $entry;
    }
}
closedir($handle);
*/
/*
$arrFiles = glob('informes/estadoAnunciosGlobal/*_8_*.pdf');
var_dump($arrFiles);
*/
/*
//Directorio a listar
$path = 'informes/estadoAnunciosGlobal';
//Abre un enlace al directorio
$dir = opendir($path);
//Iteramos el directorio
while($file=readdir($dir)){
    if(!is_dir($file)){
        //Guardamos en un array el archivo y la fecha del mismo
        $data[] = array($file, date("Y-m-d H:i:s",filemtime($path.'/'.$file)));
        //obtenemos las fechas de cada archivo, que sirven para ordenar el array anterior con la funcion array_multisort
        $dates[] = date("Y-m-d H:i:s",filemtime($path.'/'.$file));
    }
}
//cerramos el enlace al directorio
closedir($dir);
//ordenamos el array data según las fechas almacenadas en $dates
array_multisort($dates, SORT_DESC, $data);

//Mostramos el resultado despues de ordenar
echo "<pre>";
echo "Arreglo de fechas para ordenar";
echo "<br/>";
print_r($dates);

echo "Archivos ordenados";
echo "<br/>";
print_r($data);
echo "</pre>";
*/
$dir = "informes/estadoAnunciosGlobal/"; //path o ruta de nuestro directorio 
chdir($dir);
array_multisort(array_map('filemtime', ($files = glob("*_".$_SESSION['anunciante']."_*.pdf"))), SORT_DESC, $files);
echo "<table>";
foreach($files as $filename)
{
 echo "<tr>";
 echo "<td><a href='mostrarArchivo.php?path=".$filename."' target='_blank'>".substr($filename, 0, -4)."</a></td>";
 echo "</tr>";
 
}
echo "</table>";
echo '<br>';

?>
<a href="<?php echo ($_SESSION['menuinicio'])?>">Volver</a></body>
