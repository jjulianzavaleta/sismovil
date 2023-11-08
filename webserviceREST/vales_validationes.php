<?php
header ('Content-type: text/html; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

if(!isset($_POST['id_conection'])){
    generate_msg_error("Acceso no Autorizado Code01xF");	
}else{
    if($_POST['id_conection'] === "kkkRwF^MQa!vv6ssH5%S=canessa19"){

    }else{
        generate_msg_error("Acceso no Autorizado Code02xF");		
    }
}

function generate_msg_error($message){
    $error = array("respuesta" => "Error, no se pudo realizar la accion solicitada.", "error" => $message);
    $json = json_encode($error);
    echo  $json;
	die();
}