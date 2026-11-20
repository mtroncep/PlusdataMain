<?php
    require_once("conexion.php");

    $texto = "select * from paises where estado = 1 order by DESCRIPCION";
    $query = mysqli_query($con, $texto);
?>
<style>
    nav.navbar {
        background-color: #4cffff;
    }

    tr, td {
        border: medium 1px #000000;
    }

    tr.perfil_email{
        font-size: 5px;
    }

    td.perfil_tipocliente{
        font-size: 12px;
    }

    td.perfil_cliente{
        font-size: 12px;
    }

    .encabezado {
        width: auto;
        height: 25px;
        align-content: center;
        text-align: center;
    }

    .flag {
        width: 50px;        /* tamaño uniforme */
        height: 30px;       /* proporción estándar */
        object-fit: cover;  /* recorta lo mínimo para que quede prolijo */
        border-radius: 3px; /* opcional, queda más prolijo */
        display: inline-block;
}
</style>

<div class="containerPaises" id="containerPaises" style="width: 100%; margin: 0; padding: 0;">
<!--    <div class="row">
        <div class="col"> -->
            <div class="PerfilPaises" style="padding-top: 5px">
                <div class="row" align="right">
                    <div class="col">
                        <p style="font-size:  clamp(14px, 2.5vw, 28px);" data-trad="Elija país">Elija país</p>
                    </div>
                    <?php
                    $finales=0;
                    while($row = mysqli_fetch_array($query)){	
                        $bandera=$row['bandera'];
                        $moneda=$row['moneda'];
                        $moneda1=$row['moneda1'];
                        $pais=$row['DESCRIPCION'];
                        $codPais=$row['COD_NUM'];
                    ?>
                    <div class="col" align="center">
                        <a style="text-decoration:none" href="setEntorno.php?codPais=<?php echo $codPais?>&moneda=<?php echo $moneda ?>&moneda1=<?php echo $moneda1 ?>">
                        <?php if (isset($_SESSION['codPais']) && $codPais === $_SESSION['codPais']){?>
                            <img  src = data:image/png;base64,<?php echo base64_encode($bandera);?> 
                            title="<?php echo $pais?>" style="width: 40%; height: auto; filter: drop-shadow(4px 4px 8px rgba(0,0,0,0.8)); object-fit: cover;"></a>
                        <?php } else { ?>
                            <img src = data:image/png;base64,<?php echo base64_encode($bandera);?> 
                            title="<?php echo $pais?>" style="width: 40%; height: auto; display: inline-block; object-fit: cover;"></a>
                        <?php } ?>
                        <div class="row">
                            <div class="col">
                                <span style="font-family: Arial, sans-serif; font-size: 13px;"><strong><?php echo $pais; ?></strong></span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
        </div>
</div>
