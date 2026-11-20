<?php
//    $nombreSesion="SES".rand(1, 9999);
//    session_name($nombreSesion);
    session_start();
    if (!isset($_SESSION['idiomapermanente'])) {
        $_SESSION['idiomapermanente'] = 'es';
    }

    $_SESSION['idioma'] = $_SESSION['idiomapermanente'];

//    $_SESSION['idioma']=$idioma;
//    echo $nombreSesion;

    /* Connect To Database*/
	require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    if (gethostname() == "DESKTOP-6LCGB06"){
        // Entorno local
        $file_path = "localhost/HMIPrueba";
    } else {
        // Entorno de producciÃ³n
        $file_path = "https://plusdata.info";
    }
?>

<script src="trad/traducciones.php"></script>

<script>
     function traducir(texto) {
      if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
      }
      return texto;
    }

    function aplicarTraducciones() {
        document.querySelectorAll('[data-trad]').forEach(el => {
          const clave = el.getAttribute('data-trad');
        // Si es un input con placeholder
        if (el.hasAttribute('placeholder')) {
          el.setAttribute('placeholder', traducir(clave));
        } else {
          // Para otros elementos, traducimos el texto interno
          el.innerText = traducir(clave);
        }
        });
      }

      // Ejecutamos al cargar la página
    window.onload = aplicarTraducciones();
</script>
<?php
    $_SESSION['busquedaPublica'] = 0;
	$email=$_POST['emailLog'];
	$password=$_POST['pwdLog'];
	$count=mysqli_query($con,"SELECT idanunciante, password, ficha, menuinicio, activada FROM anunciantes WHERE email='$email'");
	if(mysqli_num_rows($count) < 1)
	{	echo '<script>alert(traducir("NO SE ENCUENTRA REGISTRADO, POR FAVOR, SI LO DESA HAGALO"));</script>';
	} else {
        $row = mysqli_fetch_array($count);
        $verif = password_verify($password, $row['password']);
        if ($verif){
            $activada=mysqli_query($con,"SELECT activada, estado FROM anunciantes WHERE email='".$email."'");
            $estadoCta = mysqli_fetch_array($activada);
            if ($estadoCta['activada'] === "SI"){
                if ($estadoCta['estado'] <> 1){
                    echo '<script type="text/javascript">alert("El acceso a su cuenta ha sido suspendido de manera preventiva. Contacte con el administrador.");</script>';
                    echo ('<script type="text/javascript">'."window.location.href = 'indexNew.php'</script>");
                } else {
                    $_SESSION['anunciante'] = $row['idanunciante'];
                    $_SESSION['idFicha'] = $idFicha = $row['ficha'];
                    $_SESSION['menuinicio'] = $row['menuinicio'];
                    $_SESSION['paisseleccionado'] = "";
                    $_SESSION['emailReg'] = $email;
                    $menuinicio = $row['menuinicio'];
                    $_SESSION['conectado']="C";
                    
                    if (Funciones::obtenerValorParametro($con, 'MENU_NEW')){
                        $_SESSION['menuiniciohome'] = "indexNew.php";
                    } else {
                        $_SESSION['menuiniciohome'] = "index.php";
                    }

                    $queryFicha = mysqli_query($con, "SELECT clientetipo FROM fichas where idficha=$idFicha");
                    $rowFicha = mysqli_fetch_array($queryFicha);
                    if ($rowFicha){
                        $_SESSION['clientetipo'] = $rowFicha['clientetipo'];
                        $_SESSION['tipocliReg'] = $rowFicha['clientetipo'];
                    }
                    //
                    if (Funciones::obtenerValorParametro($con, 'SEG_FACT_ACCESO')){
                        $estado=Funciones::ejecutar2FA($email,'IN',$con);
//                        echo '<script type="text/javascript">alert("POR FAVOR, BUSQUE CÓDIGO EN SU CORREO");</script>';
                        echo ('<script type="text/javascript">'."window.location.href = 'confirm2FA.php?email=$email&vuelta=IN'</script>");
                        return true;
                    }
                    //
                    echo '<script type="text/javascript">alert(traducir("Bienvenido"))</script>';
                    echo ('<script type="text/javascript">'."window.location.href = '$menuinicio'</script>");
                }
            } else {
                echo '<script type="text/javascript">alert("Su cuenta no se encuentra activada. Por favor, consulte su correo para activarla.");</script>';
                echo ('<script type="text/javascript">'."window.location.href=indexNew.php</script>");
            }
        } else {
            echo '<script type="text/javascript">alert("Clave incorrecta. Intentelo nuevamente.");</script>';			
        }
	}
	echo ('<script type="text/javascript">'.
		  "window.location.href = 'indexNew.php'</script>");
?>
    <script src="trad/appTraduccion.js"</script>

