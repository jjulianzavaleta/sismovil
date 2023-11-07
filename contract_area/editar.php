<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include("../phps/conexion.php");
    include("../phps/dcontract_area.php");

    $descripcion  = basic_validations($_POST['a']);
    $id           = $_POST['b'];
	$codigo       = strtoupper($_POST['c']);
    $error        = "";

    $estado = "0";//0:error

    if(!isNumber($id)){
        $error = "Error: ID debe ser un numero entero";
    }else{
        $res = updateContractArea($id,$descripcion,$codigo);
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