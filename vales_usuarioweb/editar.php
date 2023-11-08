<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_usuarioWeb.php");

    $id                   = $_POST['a'];
	$nombres              = $_POST['b'];
	$num_doc_identidad    = $_POST['c'];
	$cod_conductor        = $_POST['d'];
	$estado_d             = $_POST['e'];
	$isflujoconsumidor	  = $_POST['f'];
    $error          = "";

    $estado = "0";//0:error

    if(!isNumber($id)){
        $error = "Error: ID debe ser un numero entero";
    }else{
        $res = updateUsuarioEquipoWeb($id, $nombres, $num_doc_identidad, $cod_conductor, $estado_d, $isflujoconsumidor);
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