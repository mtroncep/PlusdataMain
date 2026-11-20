insert<?php
	session_start();
	session_unset();
	require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

	$clientetipo=$_SESSION['tipocliReg']=$_POST['tipocliReg'];
    $emailReg=$_SESSION['emailReg']=$_POST['emailReg'];
	$pwdReg=$_SESSION['pwdReg']=$_POST['pwdReg'];
	$nombreReg=$_SESSION['nombreReg']=$_POST['nombreReg'];
    $telReg=$_SESSION['telReg']=$_POST['telReg'];
    $menuinicio=$_SESSION['menuinicio']='index.php';
/*
    if(!isset($_SESSION['conectado'])){
        echo ('<script type="text/javascript">'."window.location.href = 'index.php'</script>");
    }
    */
    $codigo="";
/*
    var_dump($_POST);
    echo '<br>';
    return;
*/
    if ($clientetipo != "O"){
        $nroInmob = 0;
    } else {
        $nroInmob=$_SESSION['nroInmob']=$_POST['nroInmob'];
    }

	$count=mysqli_query($con,"SELECT idanunciante, password FROM anunciantes WHERE email='$emailReg'");
    if(mysqli_num_rows($count) < 1)
	{
// Enviamos el mail de confirmación de mail, para autorizar registro del chabon...
        $activada="NO";
        if (Funciones::obtenerValorParametro($con,'VERIFICAR_EMAIL')){
           $envio_SMS=Funciones::obtenerValorParametro($con,'ENVIO_SMS');
           $estadoSMS=0;
           if ($envio_SMS){
               $estadoSMS=Funciones::OTPEnvioCodSMS($telReg,0);
           }
           $_SESSION['estadoSMS'] = $estadoSMS;
           include "envioMailRegistro.php";
//           return;
        } else {
            $enviado=1;
            $activada="SI";
        }
        if ($enviado){
            // Grabamos la ficha con datos mínimos...
            $insertFicha="insert into fichas (tipoficha,emaillaboral,anuncianteinmob,clientetipo,telmovil,nombrerz) values 
            (2,".",'".$emailReg."',".$nroInmob.",'".$clientetipo."','".$telReg."','".$nombreReg."')";
            mysqli_query($con,$insertFicha);
            $idFicha = mysql_insert_id($con);
/*            
            if (mysqli_query($con,$insertFicha)){
                $sqlMax = "SELECT MAX(idficha) as maximo from fichas";
                $maxCodNum = mysqli_query($con,$sqlMax);
                if ($maxCodNum->num_rows == 1) {
                    $maxCodNumStr = $maxCodNum->fetch_assoc();
                    $idFicha=$maxCodNumStr['maximo'];
                }
            }
*/
            $password=password_hash($pwdReg, PASSWORD_DEFAULT); // encrypted password
            $activacion=password_hash($emailReg, PASSWORD_DEFAULT);	    // encrypted email+timestamp
            mysqli_query($con,"INSERT INTO anunciantes (email,password,activacion,menuinicio,codigo,ficha,activada) VALUES('$emailReg','$password','$activacion','indiceNavParticularNew.php','$codigo','$idFicha','$activada')");
            $nroAnunciante = mysql_insert_id($con);
            $_SESSION['anunciante']=$nroAnunciante;
            echo '<script type="text/javascript">alert("POR FAVOR, TOME UN MOMENTO Y COMPLETE SU FICHA");</script>';
            $_SESSION['idFicha'] = $idFicha;
            $_SESSION['clientetipo'] = $clientetipo;
            $_SESSION['registro'] = 1;
            echo ('<script type="text/javascript">'."window.location.href = 'modificarFicha.php?idFicha=".$idFicha
                      ."&anunciante=".$nroAnunciante."'</script>");
            } else {
            // Ficha ya existe
                echo '<script type="text/javascript">alert("USTED POSEE ASOCIADA UNA FICHA, POR FAVOR, SI CORRESPONDE COMPLETELA");</script>';
                echo ('<script type="text/javascript">'."window.location.href = modificarFicha.php?idFicha=".$idficha
                      ."&anunciante=".$nroAnunciante."&registro="."1"."</script>");
            }
            echo '<script type="text/javascript">alert("REGISTRO CORRECTO POR FAVOR, INGRESE NUEVAMENTE");</script>';
            echo ('<script type="text/javascript">'."window.location.href = 'salir.php'</script>");
        
	} else {
		$row = mysqli_fetch_array($count);
		$verif = password_verify($pwdReg, $row['password']);
		if ($verif){
			$_SESSION['busquedaPublica'] = 0;
			$_SESSION['anunciante'] = $row['idanunciante'];
			echo '<script type="text/javascript">alert("YA SE ENUENTRA REGISTRADO CON ANTERIORIDAD - Bienvenido");</script>';
			echo ('<script type="text/javascript">'.
				  "window.location.href = 'index.php'</script>");
		} else {
			echo '<script type="text/javascript">alert("YA SE ENCUENTRA REGISTRADO CON ANTERIOIRDAD - Clave incorrecta");</script>';			
			echo ('<script type="text/javascript">'."window.location.href = 'salir.php'</script>");
		}
	}
	echo ('<script type="text/javascript">'."window.location.href = 'index.php'</script>");
?>
