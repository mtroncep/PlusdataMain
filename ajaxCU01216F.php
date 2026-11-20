<meta charset="utf-8"/>
<?php
// Rescatamos el parámetro que nos llega mediante la url que invoca xmlhttp
//$pais=$_POST["pais"];
$pais='Argentina';
$resultadoConsulta = '';
$msg = 'El pais recibido en segundo plano ahora es '.$pais;
if ($pais) {
    $link = mysqli_connect("localhost", "marcetro", "marce1107", "marcetro_propiedadesma");
    mysqli_select_db($link, "prueba1");
    $tildes = $link->query("SET NAMES 'utf8'"); //Para que se muestren las tildes correctamente
    $result = mysqli_query($link, "SELECT COD_NUM, DESCRIPCION
                FROM paises where DESCRIPCION="+$pais);

	while ($fila = mysqli_fetch_array($result)){
	$resultadoConsulta .= ';'.$fila['COD_NUM'].','.$fila['DESCRIPCION'];
	}

//Devolvemmos la cadena de respuesta
echo $msg.$resultadoConsulta;

mysqli_free_result($result);
mysqli_close($link);
} else {
    echo 'No se han recibido datos';
}
?>