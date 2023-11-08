<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_usuarioWeb.php");

	$cod_conductor        = $_POST['b'];
	$nombres              = $_POST['c'];
	$num_doc_identidad    = $_POST['d'];
	$isflujoconsumidor    = $_POST['e'];
	$error                = "";

    $estado = "0";//0:error

    $res = registrarUsuarioEquipoWeb( $cod_conductor, $nombres, $num_doc_identidad, $isflujoconsumidor);
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