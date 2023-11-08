<?php
/**
 * Created by PhpStorm.
 * User: Escalab
 * Date: 17/09/2015
 * Time: 01:11 PM
 */

include("../phps/dpaviferia_productos.php");
include("../phps/validaciones.php");

if(!isset($_POST['a']) || !isset($_POST['b'])){
    $res       = "Error";
    $estado    = 0;
}else{
    $fechapedido       = $_POST['a'];
    $idproducto          = $_POST['b'];

    $res = hasPrecioProductoInFecha($idproducto,$fechapedido);

    if($res === false){
        $estado = 0;
    }else{
        $estado = 1;
    }
}


$respuesta = array("estado" => $estado);
$json = json_encode($respuesta);
echo  $json;