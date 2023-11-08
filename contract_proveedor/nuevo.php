<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include("../phps/conexion.php");
    include("../phps/dcontract_proveedor.php");

    $razon_social  	= basic_validations($_POST['a']);
	$ruc  			= basic_validations($_POST['c']);
    $id_new       	= "";
    $desc_new     	= "";
    $error        	= "";

    $estado = "0";//0:error

    $res = registrarProveedor($ruc,$razon_social);
    if($res === false){
        $estado = "0";//error
        $error  = "Error: No se pudo guardar el registro";
    }else{
        $estado = "1";//exito
    }

    $response = array("estado" => $estado, "error" => $error);
    $json = json_encode($response);
    echo  $json;




?>