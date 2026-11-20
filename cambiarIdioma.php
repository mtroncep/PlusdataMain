<?php

session_start();

if (isset($_POST['idioma'])) {
    
    $_SESSION['idiomapermanente'] = $_POST['idioma'];
    $_SESSION['idioma'] = $_POST['idioma'];

}

echo 'OK';
