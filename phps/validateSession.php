<?php
session_start();
if(!isset($_SESSION['login'])){
    session_destroy();
    die( "Error, su sesión a expirado");

}
