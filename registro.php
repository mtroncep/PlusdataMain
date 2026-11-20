<?php
	session_start();
//	session_unset();
/*
    unset($_SESSION['tipocliReg']);
    unset($_SESSION['emailReg']);
    unset($_SESSION['pwdReg']);
    unset($_SESSION['nombreReg']);
    unset($_SESSION['telReg']);
    unset($_SESSION['menuinicio']);
    unset($_SESSION['nroInmob']);
*/
    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

	$clientetipo=$_SESSION['tipocliReg'];
    $emailReg=$_SESSION['emailReg'];
	$pwdReg=$_SESSION['pwdReg'];
	$nombreReg=$_SESSION['nombreReg'];
    $telReg=$_SESSION['telReg'];
    $menuinicio=$_SESSION['menuinicio']='indexNew.php';

    if ($clientetipo != "O"){
        $nroInmob = 0;
    } else {
        $nroInmob=$_SESSION['nroInmob'];
    }

	$count=mysqli_query($con,"SELECT idanunciante, password FROM anunciantes WHERE email='$emailReg'");
//    $count=0;
//    if($count < 1)
    if(mysqli_num_rows($count) < 1)
	{
// Enviamos el mail de confirmaciÃ³n de mail, para autorizar registro del chabon...
    $activada="SI";

    $password=password_hash($pwdReg, PASSWORD_DEFAULT); // encrypted password
    $activacion=password_hash($emailReg, PASSWORD_DEFAULT);	    // encrypted email+timestamp
    mysqli_query($con,"INSERT INTO anunciantes (fechaaceptacionterminos,email,password,activacion,menuinicio,activada) VALUES( now(),'$emailReg','$password','$activacion','indiceNavParticularNew.php','$activada')");
    // Obtengo id del anunciante para asociarlo a la ficha existente o nueva...
    $anunciante=mysqli_query($con,"SELECT idanunciante FROM anunciantes WHERE email='$emailReg'");
    $row = mysqli_fetch_array($anunciante);
    $nroAnunciante = $row['idanunciante'];

    $ficha = mysqli_query($con, "SELECT * FROM fichas where emaillaboral='$emailReg'");
    $row = mysqli_fetch_array($ficha);

    if(mysqli_num_rows($ficha) < 1)
        {
    // Ficha no existe
        $_SESSION['anunciante']=$nroAnunciante;
//        $sqlMax = "SELECT MAX(idficha) as maximo from fichas";
//        $maxCodNum = mysqli_query($con,$sqlMax);
        $idFicha = Funciones::obtenerNumerador("FICHA",$con);
        error_log("ID FICHAS ------> ".$idFicha,0);
/*
        if ($maxCodNum->num_rows == 1) {
            $maxCodNumStr = $maxCodNum->fetch_assoc();
            ++$maxCodNumStr['maximo'];
            $idFicha=$maxCodNumStr['maximo'];
        }
*/
        if ($clientetipo <> 'P'){
            $insertFicha="insert into fichas (idficha,tipoficha,anunciante,emaillaboral,anuncianteinmob,clientetipo,telmovil,nombrerz) values 
            (".$idFicha.",2,".$nroAnunciante.",'".$emailReg."',".$nroInmob.",'".$clientetipo."','".$telReg."','".$nombreReg."')";
        } else {
            $insertFicha="insert into fichas (idficha,tipoficha,anunciante,emaillaboral,anuncianteinmob,clientetipo,telmovil,nombrerz,
            contactonombre,contactotel,contactoemail) values 
            (".$idFicha.",2,".$nroAnunciante.",'".$emailReg."',".$nroInmob.",'".$clientetipo."','".$telReg.
                "','".$nombreReg."',".
                "'DUEÑO',"."'".$telReg."','".$emailReg."')";
        }
//                echo $insertFicha.'<br>';
        mysqli_query($con,$insertFicha);
        error_log("Insert fichas: ".$insertFicha,0);

        echo '<script type="text/javascript">alert("POR FAVOR, TOME UN MOMENTO Y COMPLETE SU FICHA");</script>';
        $_SESSION['idFicha'] = $idFicha;
        $_SESSION['clientetipo'] = $clientetipo;
        $_SESSION['registro'] = 1;
/*                
        $comando = "window.location.href = modificarFicha.php?idFicha=".$idFicha
              ."&anunciante=".$nroAnunciante."&registro="."1";
        error_log("Llamdo: ".$comando,0);
*/
//                echo ('<script type="text/javascript">'.$comando.";</script>");
        echo ('<script type="text/javascript">'."window.location.href = 'modificarFicha.php?idFicha=".$idFicha
              ."&anunciante=".$nroAnunciante."'</script>");
        echo '<script type="text/javascript">alert("¡REGISTRO CORRECTO! POR FAVOR, INGRESE NUEVAMENTE");</script>';
        echo ('<script type="text/javascript">'."window.location.href = 'salir.php'</script>");
        return;
        }
    }
	echo ('<script type="text/javascript">'."window.location.href = 'indexNew.php'</script>");
?>
