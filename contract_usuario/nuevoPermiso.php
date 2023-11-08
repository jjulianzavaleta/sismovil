<?php
include("../phps/validateSession.php");
include("../phps/validaciones.php");
include_once("../phps/conexion.php");
include_once("../phps/dContract_permisosAdicionales.php");

$idUsuario                  = $_POST['a'];
$permission_crear           = $_POST['b'];
$permission_aprobar         = $_POST['c'];
$permission_reportes        = $_POST['d'];
$permission_responsablearea = $_POST['e'];
$idArea						= $_POST['f'];
$error        = "";

$estado = "0";//0:error

if(!isNumber($idUsuario) || !isNumber($idArea)){
    $error = "Error: ID debe ser un numero entero";
}else{
    $res = insertPermisoAdicional($idUsuario,$permission_crear,$permission_aprobar,$permission_reportes,$permission_responsablearea,$idArea);
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