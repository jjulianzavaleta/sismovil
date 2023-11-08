<?php

    include("../phps/validateSession.php");
    include("../phps/validaciones.php");
	include_once("../phps/conexion.php");
    include_once("../phps/dcontract_usuarios.php");

    $usuario      = basic_validations($_POST['a']);
	$permission_crear           = $_POST['c'];
	$permission_aprobar         = $_POST['d'];
	$permission_reportes        = $_POST['e'];
	$correo				        = $_POST['f'];
	$permission_admin		    = $_POST['g'];
	$permission_admin		    = $_POST['g'];
	$permission_responsablearea = $_POST['h'];
	$area						= $_POST['i'];
	$tipo_usuario				= $_POST['j'];
    $error        = "";

    $estado = "0";//0:error

    $res = registrarContractUsuario($usuario,$permission_crear,$permission_aprobar,$permission_reportes,$correo,$permission_admin,$permission_responsablearea,$area,$tipo_usuario);
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