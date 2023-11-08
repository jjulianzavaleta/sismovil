<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include("../phps/conexion.php");
    include("../phps/dcontract_area.php");

    $descripcion  = basic_validations($_POST['a']);
	$codigo       = strtoupper($_POST['c']);	
    $error        = "";

    $estado = "0";//0:error

    $res = registrarContractArea($descripcion,$codigo);
    if($res === false){
        $estado = "0";//error
        $error  = "Error: No se pudo guardar el registro";
    }else{
        $estado = "1";//exito
    }

    $response = array("estado" => $estado,"error" => $error);
    $json = json_encode($response);
    echo  $json;




?>