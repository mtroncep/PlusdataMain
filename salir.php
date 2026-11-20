<?php
	session_start();
    if (isset($_SESSION['menuiniciohome']) && (!is_null($_SESSION['menuiniciohome']))){
        echo '<script>window.location.href = "'.$_SESSION['menuiniciohome'].'";</script>';
    } else {
        session_destroy();
    }
?>