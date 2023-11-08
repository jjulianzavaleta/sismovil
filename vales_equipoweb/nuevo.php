<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_equipoWeb.php");

	$equnr          = $_POST['b'];
	$txt_hequi      = $_POST['c'];
	$kostl          = $_POST['d'];
	$license_num    = $_POST['e'];
	$sttxt          = $_POST['f'];	
	$contadortipo   = $_POST['h'];	
    $error          = "";

    $estado = "0";//0:error

    $res = registrarValesEquipoWeb($equnr,$txt_hequi,$kostl,$license_num,$sttxt,$contadortipo);
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