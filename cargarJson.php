<?php
    $json = file_get_contents('paises.json');
    $data = json_decode($json, true);
    foreach($data as $row) {
        $nameES = $row['nameES'];
        $nameEN = $row['nameEN'];
        $iso2 = $row['iso2'];
        $iso3 = $row['iso3'];
        $phoneCode = $row['phoneCode'];

        echo ("insert into codigosintl (nameES,nameEN,iso2,iso3,phoneCode) values ('$nameES','$nameEN','$iso2','$iso3','+$phoneCode')"."<br>");
    
    }
?>
