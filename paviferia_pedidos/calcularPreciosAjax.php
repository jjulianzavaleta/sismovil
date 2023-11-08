<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 31/08/2015
 * Time: 11:22 PM
 */
include("../phps/dpaviferia_pedido.php");
include("../phps/validaciones.php");

if(!isset($_POST['a']) || !isset($_POST['b']) || !isset($_POST['c'])){
    $res       = "Error";
    $lstpedido = array();
    $pedido    = array();
}else{
    $lstpedido       = $_POST['a'];
    $modpago         = $_POST['b'];
    $fechaceventa    = $_POST['c'];
    $nro_documento   = $_POST['d'];

    $lstpedido = objectToArray(json_decode($lstpedido));

    $pedido    = calcularPedido($lstpedido,$modpago,$fechaceventa,$nro_documento);
    $lstpedido = calcularDetallePedido($lstpedido,$modpago,$fechaceventa,$nro_documento);

    $res = "Exito";
    if($lstpedido === false || $pedido === false){
        $res = "Error";
    }
}



$respuesta = array("respuesta" => $res, "detallepedido"=>json_encode($lstpedido), "pedido" => json_encode($pedido));
$json = json_encode($respuesta);
echo  $json;