<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 20/08/2015
 * Time: 08:40 PM
 */
include("../phps/validateSession.php");
include("../phps/validaciones.php");
include("../phps/dpaviferia_descuento.php");

$tipo             = $_POST['b'];
$descuento        = $_POST['c'];
$minimo           = $_POST['d'];
$maximo           = $_POST['e'];
$idformapago      = $_POST['f'];
$idgrupo          = $_POST['g'];

$error        = "";

$estado = "0";//0:error

if(!is_numeric($minimo) || !is_numeric($maximo) || !isNumber($tipo) || !is_numeric($descuento) || !is_numeric($idgrupo)){

    $error = "Error: Minimo, Maximo, Descuento deben de ser un numeros";

}else{

    if(!existsDescuento($tipo,$minimo,$maximo,$idformapago,$idgrupo)){

        $res = registrarDescuentoPaviferia($tipo,$descuento,$minimo,$maximo,$idformapago,$idgrupo,$_SESSION['id']);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro".$_SESSION['mm'];
        }else{
            $estado = "1";//exito
        }

    }else{
        $error = "Error: Descuento ya existe";
    }

}

$response = array("estado" => $estado,"error" => $error);
$json = json_encode($response);
echo  $json;




?>