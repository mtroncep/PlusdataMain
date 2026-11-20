<?php
session_start();
if (isset($_SESSION['codPais'])){
    unset($_SESSION['codPais']);
}