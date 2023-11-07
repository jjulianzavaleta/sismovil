<?php

/* 
	INPUT : idvale
	OUTPUT: valecabecera, valedetalle_materiales, valedetalle_asignaciones
*/

include_once("vales_validationes.php");

/// INICIO - test borrar
//$_POST['idvale'] = "34";
/// FIN - test borrar

if( !isset($_POST['idvale']) ){
	generate_msg_error("Parametros invalidos");	
}

include("../phps/conexion.php");
include("../phps/dvales_webServices.php");

$valecabecera             = getValeCabecera($_POST['idvale']);
$valedetalle_materiales   = getValeDetalle_Materiales($_POST['idvale']);
$valedetalle_asignaciones = getValeDetalle_Asignaciones($_POST['idvale']);

if( $valecabecera === false ){
    error("No se puedo obtener la data");
}else{

    $data = array("respuesta"                => "exito",
                  "valecabecera"             => $valecabecera,
				  "valedetalle_materiales"   => $valedetalle_materiales,
				  "valedetalle_asignaciones" => $valedetalle_asignaciones);

    $json = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo  $json;

}