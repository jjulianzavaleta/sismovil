<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_materiales.php");

	$nombre         = $_POST['b'];
	$cod_sap        = $_POST['c'];
    $error          = "";

    $estado = "0";//0:error

    $res = registrarValesMateriales($nombre,$cod_sap);
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