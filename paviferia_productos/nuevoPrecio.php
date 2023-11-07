<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 17/09/2015
 * Time: 01:48 AM
 */
    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
    include("../phps/dpaviferia_productos.php");

    $idProducto       = $_POST['e'];
    $fechaFin         = $_POST['d'];
    $fechaIni         = $_POST['c'];
    $precioBase       = $_POST['b'];
    $descripcion      = basic_validations($_POST['a']);
    $error            = "";

    $estado = "0";//0:error

    if(!empty($fechaFin) && !empty($fechaIni) && !empty($descripcion) && !is_numeric($precioBase) && !isNumber($idProducto)){

        $error = "Error: Datos enviados no cumplen parametros";

    }else{
        $res = registrarPrecioProductoPaviferia($idProducto,$descripcion,$precioBase,$fechaIni,$fechaFin,$_SESSION['id']);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo guardar el registro";
        }else{
            $estado = "1";//exito
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>