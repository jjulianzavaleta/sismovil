<?php

/* 
	INPUT : id_conection, idusuario
	OUTPUT: vales_validos
*/

include_once("vales_validationes.php");

/// INICIO - test borrar
//$_POST['idusuario'] = "2";
/// FIN - test borrar

if( !isset($_POST['idusuario']) ){
	generate_msg_error("Parametros invalidos");	
}

include("../phps/conexion.php");
include("../phps/dvales_webServices.php");

$vales_validos = getValesValidosByChofer($_POST['idusuario']);

if($vales_validos === false ){
    generate_msg_error("No se puedo obtener la data");
}else{

    $data = array("respuesta"       => "exito",
                  "data"            => $vales_validos);

    $json = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo  $json;
}