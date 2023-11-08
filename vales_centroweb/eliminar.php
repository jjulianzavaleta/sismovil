<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_centroWeb.php");

    $id  = basic_validations($_POST['a']);
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id)){
        $error = "Error: ID debe ser un numero entero";
    }else{
        $res = eliminarValesCentroWeb($id);
        if($res === false){
            $estado = "0";//error
            $error  = "Error: No se pudo eliminar el registro";
        }else{
            $estado = "1";//exito
        }
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;


?>
