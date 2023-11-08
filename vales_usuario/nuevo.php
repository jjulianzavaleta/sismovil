<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dvales_usuarios.php");

    $usuario      = basic_validations($_POST['a']);
	$permission_planner         = $_POST['c'];
	$permission_driver          = $_POST['d'];
	$permission_reportes        = $_POST['e'];
	$correo				        = $_POST['f'];
	$permission_admin		    = $_POST['g'];
    $error        = "";

    $estado = "0";//0:error

    $res = registrarValesUsuario($usuario,$permission_planner,$permission_driver,$permission_reportes,$correo,$permission_admin);
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