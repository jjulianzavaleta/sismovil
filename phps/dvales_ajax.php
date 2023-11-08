<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("conexion.php");
include_once("dvales_create.php");
include_once("validaciones.php");

if (isset($_POST['cod'])) {
	
    if($_POST['cod'] == 1){//Crear-Actualizar
        $salida = updsert_vale($_POST);
    }else if($_POST['cod'] == 2){//Anular
        $salida = anular_vale($_POST);
    }else if($_POST['cod'] == 3){
		$salida = getCentroCostoDatabyEquipo($_POST['idequipo']);
	}else if($_POST['cod'] == 4){
		$salida = deshacer_emicion($_POST['idvale']);
	}else if($_POST['cod'] == 5){
		$salida = update_kilometraje($_POST['idvale'],$_POST['new_value'],$_POST['idusuario'],$_POST['isLastValeConsumidoByEquipo'],$_POST['idequipo'],$_POST['new_observacion']);
	}else if($_POST['cod'] == 6){
		$salida = getRFCResponse_process($_POST['idvale'],$_POST['iteration'],$_POST['max_iteration']);
	}else if($_POST['cod'] == 7){
		$salida = undoConsumido($_POST['idvale']);
	}else if($_POST['cod'] == 8){
		$salida = getKilometrajeEquipo($_POST['idequipo']);
	}

    if($salida === false) {
        $res = array("estado" => 0, "error" => "Error, no se pudo guardar los datos");
    }else{
        $res = array("estado" => 1, "data" => $salida);
    }

    echo json_encode($res);
}

function updsert_vale($data){
	
	if( empty($data['id']) ){
		return create_vale($data);
	}else{
		return update_vale($data);
	}
}

function getRFCResponse_process($idvale, $iteration ,$max_iteration ){
	$lastResponse = getRFCResponse($idvale);
	
	if( !empty($lastResponse) ){
		$rfcresponse = process_response_RFC($lastResponse['response'], $idvale);
		return array( "rfcresponse" =>  $rfcresponse);
	}else{
		if($iteration == $max_iteration){
			undoConsumido($idvale);
		}
		return false;
	}
}

function process_response_RFC($response, $idvale){
	
	if( empty($response) ) return "";
	
	$success_response = false;
	$msg_error		  = "";
	
	try {
		$json = json_decode($response, true);
		$output1 = $json['ET_RETURN']['item'];
		$output2 = $json['IT_DATOS']['item'];
		
		foreach($output1 as $d){
			if( $d['CREAD0'] == "X" ){
				$success_response = true;
			}else{
				$msg_error.= " ".$d['MENSAJE_ERROR'];
			}
		}
		
		$fecha_enviada = $output2['FECHA']." ".$output2['HORA'];
	} catch (Exception $e) {
		$msg_error = "Error in php method process_response_RFC. Details: ".$e->getMessage();
        undoConsumido($idvale);
	}		
	
	if( $success_response ){
		return "RFC Consumo exitoso.\nFecha Consumo: ".$fecha_enviada;
	}else{
		undoConsumido($idvale);
		return "RFC Error: ".$msg_error."\nFecha Consumo: ".$fecha_enviada;;
	}
}

