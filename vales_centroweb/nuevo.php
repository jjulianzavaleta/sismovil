<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_centroWeb.php");

	$centro_costo   = $_POST['b'];
	$denominacion   = $_POST['c'];
    $error          = "";

    $estado = "0";//0:error

    $res = registrarValesCentroWeb($centro_costo,$denominacion);
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