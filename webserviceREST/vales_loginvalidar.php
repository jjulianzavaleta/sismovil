<?php

/* 
	INPUT : id_conection, username, password
	OUTPUT: userid
*/

include_once("vales_validationes.php");

/// INICIO - test borrar
//$_POST['username'] = "40943847";
//$_POST['password'] = "200001";
/// FIN - test borrar

if( !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['versionCode']) ){
	generate_msg_error("Parametros invalidos. Verifique que tenga instalada la última versión de la App.");	
}

include("../phps/conexion.php");
include("../phps/dvales_webServices.php");

$valesSetup				= getSetupAppMovil();
$warning				= "";

if( !empty($valesSetup) ){
	$vales_app_version_db = floatval($valesSetup['version_code']);
	$vales_app_version_rest = floatval($_POST['versionCode']);
	$stop_app = $valesSetup['stop_app'];
	
	if( $vales_app_version_db >  $vales_app_version_rest ){
		if($stop_app === "true"){
			generate_msg_error("Existe una nueva versión de la app, debe instalarla para continuar.");	
		}else{
			$warning = "Existe una nueva versión de la app, por favor instalela para evitar problemas de compatibilidad";
		}
	}
}

$validarAcceso          = validarAccesoChofer($_POST['username'],$_POST['password']);

if($validarAcceso === false ){
    generate_msg_error("Datos de acceso invalidos");
}else{

    $data = array("respuesta"       => "exito",
                  "idusuario"       => $validarAcceso['idusuario'],
				  "warning"			=> $warning);

    $json = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo  $json;
}