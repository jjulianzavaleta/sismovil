<?php

include("../phps/validateSession.php");
include("../phps/validaciones.php");
include_once("../phps/conexion.php");
include_once("../phps/dContract_permisosAdicionales.php");

$usuario    = $_POST['a'];
$area       = $_POST['b'];
$error        = "";

$estado = "0";//0:error

$res = setMainArea($usuario, $area);
if($res === false){
    $error  = "Error: No se pudo guardar el registro";
}else{
    $estado = "1";//exito
}

$response = array("estado" => $estado,"error" => $error);
$json = json_encode($response);
echo  $json;




?>