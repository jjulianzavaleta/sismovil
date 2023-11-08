<?php
include("../phps/validateSession.php");
include("../phps/validaciones.php");
include_once("../phps/conexion.php");
include_once("../phps/dContract_permisosAdicionales.php");

$id                  = $_POST['a'];
$usuario             = $_POST['b'];
$error        = "";

$estado = "0";//0:error

if(!isNumber($id)){
    $error = "Error: ID debe ser un numero entero";
}else{
    $res = deletePermisoAdicional($id, $usuario);
    if($res === false){
        $estado = "0";//error
        $error  = "Error: No se pudo eliminar el registro. Verifique el permiso principal no es el mismo que trata de eliminar.";
    }else{
        $estado = "1";//exito
    }
}

$response = array("estado" => $estado,"error" => $error);
$json = json_encode($response);
echo  $json;




?>