<?php

/* 
	INPUT : id_conection, username, password
	OUTPUT: userid
*/

/*$_POST['id_conection']  = "kkkRwF^MQa!vv6ssH5%S=canessa19";
$_POST['username']		= "70367955";
$_POST['password']		= "2323";
$_POST['new_password']	= "testpassword";*/
include_once("vales_validationes.php");


if( !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['new_password']) ){
	generate_msg_error("Parametros invalidos");	
}

include("../phps/conexion.php");
include("../phps/dvales_webServices.php");

$validarAcceso          = validarAccesoChofer($_POST['username'],$_POST['password']);

if($validarAcceso === false ){
    generate_msg_error("Datos de acceso invalidos");
}else{
	
	$respuesta = changePassword($_POST['username'],$_POST['new_password']);

    $data = array("respuesta"       => $respuesta==true?"exito":"error",
                  "idusuario"       => $validarAcceso['idusuario']);

    $json = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo  $json;
}