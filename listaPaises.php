<?php

/**

 * Obtiene todos los usuarios de la base de datos

 */

require 'funciones.php';



$paises = funciones::listaPaises();

if ($paises) {

	$datos["estado"] = 1;

    $datos["paises"] = $paises;

   // print_r($paises);

    } else {

      print json_encode(array(

            "estado" => 2,

            "mensaje" => "Ha ocurrido un error"

      ));

    }

?>



<!DOCTYPE html>

<html>

<head>

<style>

th {

  border: 3px solid powderblue;

}

tr {

  border: 3px solid powderblue;

}

#t_paises {

 /* border: 3px solid black; */

}

#divp {

     overflow:scroll;

     height:400px;

     width:550px;

}

.tabp {

  background-color: #fafafa;

  margin: 1rem;

  padding: 1rem;

  /*border: 2px solid #ccc;*/

  /* IMPORTANTE */

  text-align: center;

}

</style>

</head>

<body>

	

<p>&nbsp;</p>
 <table width="299" border="0" align="left">

  <tbody>

    <tr>

      <th width="278" scope="col"><p>TABLA DE PAISES</p></th>

    </tr>

  </tbody>

</table>
<p>&nbsp;</p>
<div id="divp">

  <table width="492" align="center" id="t_paises">

    <tbody>

    <tr>

      <th width="38" scope="col">COD</th>

      <th width="30" scope="col">SIG</th>

      <th width="408" scope="col">DESCRIPCIÓN</th>

    </tr>

		<?php foreach ($paises as $pais): ?>

    <tr>

      <td style="border: 1px solid black;" align="center"><?php echo $pais['COD_NUM'] ?></td>

      <td style="border: 1px solid black;" align="center"><?php echo $pais['COD_ALFA'] ?></td>

      <td style="border: 1px solid black;"><?php echo $pais['DESCRIPCION'] ?></td>

    </tr>

	    <?php endforeach; ?>

  </tbody>

 </table>
</div>
<table width="200" border="1" align="center">
  <tbody>
    <tr>
      <th scope="col"><input type="submit" name="submit" id="submit" value="Enviar"></th>
      <th scope="col"><input type="submit" name="submit2" id="submit2" value="Enviar"></th>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
