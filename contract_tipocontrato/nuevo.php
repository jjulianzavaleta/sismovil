<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include("../phps/dcontract_tipoContratos.php");

    $descripcion  = basic_validations($_POST['a']);
    $error        = "";

    $estado = "0";//0:error

    $res = registrarTipoContrato($descripcion);
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