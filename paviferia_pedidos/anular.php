<?php

include("../phps/validateSession.php");
include("../phps/validaciones.php");
include("../phps/dpaviferia_pedido.php");

$idpedido   = $_POST['a'];
$estadoCot  = $_POST['b'];

$estado = 2;//0:error
$error  = "";

if(!isNumber($idpedido) || !isNumber($estadoCot)){

    $error = "Error: ID y Estado Cotizacion deben ser numeros enteros";

}else{
    $res = cambiarEstadoPedidoPaviferia($idpedido,$estadoCot,$_SESSION['id']);
    if($res === false){
        $estado = "0";//error
        $error  = "Error: No se pudo cambiar el estado de la Cotizacion";
    }else{
        $estado = "1";//exito
    }
}

$response = array("estado" => $estado,"error" => $error);
$json = json_encode($response);
echo  $json;




?>