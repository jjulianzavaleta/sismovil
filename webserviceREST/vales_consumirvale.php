<?php

/* 
	INPUT : id_conection, ...
	OUTPUT: respuesta
*/
//$_POST['id_conection'] = "kkkRwF^MQa!vv6ssH5%S=canessa19";
include_once("vales_validationes.php");

//$_POST['data'] = '{"idusuario":"2","idvale":"30","unidad_medida":0,"longitud":0,"latitud":0,"observacion":"text","kilometraje":"555","detalle":[{"iditem":70,"cantidad":56,"voucher_img":"img.jpg","voucher_nro":"vt-rt5"},{"iditem":71,"cantidad":46,"voucher_img":"img3.jpg","voucher_nro":"vt-rt78"}]}';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if( !isset($_POST['data']) ){
	generate_msg_error("Parametros invalidos");
}

include_once("../phps/validaciones.php");
include_once("../phps/conexion.php");
include_once("../phps/dvales_webServices.php");
include_once("callTSOMobileAPI.php");
include_once("callRFC_ConsumoVales.php");
include_once("../phps/setup.php");

$include_images = true;
if( isset($_POST['not_include_images']) )$include_images = false;

try {
	$data = $_POST['data'];	
	$obj  = objectToArray(json_decode($data));//recibido de la aplicacion movil

	if($include_images){
		upload_mandatory_images_consumo_vale($obj['detalle'], $obj['idvale']);
		upload_additonal_images_consumo_vale($obj['mapping_extra_images'], $obj['idvale']);
	}

	$result_database_operations = save_result_from_app($obj, $include_images);
	
	if($result_database_operations){
		call_external_services($obj['idvale']);		
		echo json_encode( array("respuesta" => "exito") );
	}else{
		generate_msg_error("No se pudo guardar los datos");
	}
} catch (Exception $e) {
	generate_msg_error("No se pudo guardar los datos\nError:".$e->getMessage());    
}

function upload_mandatory_images_consumo_vale($detalle, $idvale){
	upload_images_consumo_vale($detalle, $idvale, "uploaded_file_", "img_");
}

function upload_additonal_images_consumo_vale($detalle, $idvale){
	if( sizeof($detalle) > 0 )
		upload_images_consumo_vale($detalle, $idvale, "uploaded_file_extras_", "img_extras_");
}

function upload_images_consumo_vale($detalle, $idvale, $variable_file, $name_to_save){
	
	$i=0;
	foreach($detalle as $item){
		$dir_subida = ProjectManager::rootDirectory().'/files/vales/';
		$dir_subida.= $idvale."/";
			
		if(isset($_FILES[$variable_file.''.$i]['tmp_name'])){
			$file = $_FILES[$variable_file.''.$i]['tmp_name'];
			if (!file_exists($file)){
				generate_msg_error('0X1 No se recibio image para item: '.$i);
			}					
			
			if (!file_exists($dir_subida)) {			
				if(!mkdir($dir_subida, 0700, true)){			
					generate_msg_error('Fallo al crear las carpetas...'.$i);
				}
			}	
				
			$info = pathinfo(basename( $_FILES[$variable_file.''.$i]['name']));
			$dir_subida = $dir_subida . basename( $name_to_save.''.$item['iditem'].'.'.$info['extension']);
				
			if(move_uploaded_file($_FILES[$variable_file.''.$i]['tmp_name'], $dir_subida)) {		
			} else{
				generate_msg_error("No se pudo guardar el voucher. Image number: ".$i." - ".$name_to_save.". Error code: ".$_FILES[$variable_file."".$i]["error"]);
			}
				
			$i++;
		}else{
			generate_msg_error('0X2 No se recibio image para item: '.$i);
		}
	}
}

function call_external_services($idvale){
	
	callTSOMobileAPI_exec( $idvale );
	callRFC_ConsumoVales_exec( $idvale );
}