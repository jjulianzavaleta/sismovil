<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 20/08/2015
 * Time: 08:39 PM
 */

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_descuento.php");

    $descuento  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id) || !is_numeric($descuento)){

        $error = "Error: ID y Descuento deben ser numeros";

    }else{
        $res = updateDescuentoPaviferia($id,$descuento,$_SESSION['id']);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo actualizar el registro";
        }else{
            $estado = "1";//exito
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>