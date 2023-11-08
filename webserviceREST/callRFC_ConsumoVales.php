<?php 

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('max_execution_time', '0');

/*ini_set("xdebug.var_display_max_children", '-1');
ini_set("xdebug.var_display_max_data", '-1');
ini_set("xdebug.var_display_max_depth", '-1'); 
if(isset($_GET['run']))
	callRFC_ConsumoVales_exec( 4581 );
callRFC_ConsumoVales_save( 1271 );*/
 
if( isset($_GET['idvale']) )
	callRFC_ConsumoVales_save($_GET['idvale']);
	
if( isset($_GET['idvaleJob']) )
	callRFC_ConsumoVales_save($_GET['idvaleJob'], true);

function callRFC_ConsumoVales_exec( $idvale ){	
	$url = "/webserviceREST/callRFC_ConsumoVales.php?idvale=".$idvale;
	triggerWgetCall2($url, $idvale);
}

function callRFC_ConsumoVales_execFromJob( $idvale ){	
	$url = "/webserviceREST/callRFC_ConsumoVales.php?idvaleJob=".$idvale;
	triggerWgetCall2($url, $idvale, true);
}

function callRFC_ConsumoVales_save( $idvale, $fromJob = false ){
	
	require_once("../phps/libreriasphp/nusoap/nusoap.php");
	require_once("../phps/conexion.php");
	require_once("../phps/dvales_RFCConsumoVales.php");
	
	$vale			=	 getDataVale_forRFCConsumoVale($idvale);
	
	if( empty($vale) )die("Vale no encontrado");

    $wsdl_url = "http://GSFECCPRD01.gsf.com.pe:8000/sap/bc/srt/rfc/sap/zws_mm_ifcu_consumo_app/400/zws_mm_ifcu_consumo_app/zws_mm_ifcu_consumo_app";
    $endpoint = "http://GSFECCPRD01.gsf.com.pe:8000/sap/bc/srt/rfc/sap/zws_mm_ifcu_consumo_app/400/zws_mm_ifcu_consumo_app/zws_mm_ifcu_consumo_app";
    $soap_client = new nusoap_client($wsdl_url);
    $soap_client->setCredentials("RFC_PICHIMU", "7LR)deEn", 'basic');

	$soap_client->setEndpoint($endpoint);
	$soap_client->soap_defencoding = 'UTF-8';
	$soap_client->decode_utf8 = FALSE;
	
	$RFC_FUNC 		= "ZMMRFC_IFCU_CONSUMO_APP";
	
	$flujo 			=	$vale['flujo'];
	$equipo 		=	$vale['equnr'];
	$estacion 		=	$vale['nroestacion'];
	$fecha 			=	$vale['fecha'];
	$hora 			=	$vale['hora'];
	$cantidad 		=	$vale['menge_chofer'];
	$combustible 	=	$vale['materialnombre'];
	
	$consumo_unidadmedida = $vale['consumo_unidadmedida'];
	$kilometraje 		  =	"";
	$odometro_kilom		  = "";
	if( $consumo_unidadmedida == "1" ){
		$odometro_kilom =	$vale['kilom'];
	}else{
		$kilometraje 	=	$vale['kilom'];
	}
	
	$rendimiento    =	!empty($vale['rendimiento_estandar'] && $vale['rendimiento_estandar'] != '.00' && $vale['rendimiento_estandar'] != '0.00' )?$vale['rendimiento_estandar']:"";

    $params1 = array(
	  "FLUJO"     			=> $flujo,
	  "EQUIPO"     			=> $equipo,
	  "ESTACION"     		=> $estacion,
	  "FECHA"     			=> $fecha,
	  "HORA"     			=> $hora,
	  "CANTIDAD"     		=> $cantidad,
	  "PUNTO_MEDIDA_COM"    => "",
	  "COMBUSTIBLE"     	=> $combustible,
	  "PUNTO_MEDIDA_KM"     => "",
	  "KILOMETRAJE"     	=> $kilometraje,
	  "PUNTO_MEDIDA_H"     	=> "",
	  "HORAS"     			=> $odometro_kilom,
	  "PUNTO_MEDIDA_RENDIMIENTO"     => "",
	  "RENDIMIENTO"     	=> $rendimiento,
	  "CENTRO"     			=> "",
	  "CENTRO_COSTO"     	=> ""
	);
	/*$params1 = array(
	  "FLUJO"     			=> "2",
	  "EQUIPO"     			=> "03-CAMIPA-00000002",
	  "ESTACION"     		=> "C50",
	  "FECHA"     			=> "2021-01-07",
	  "HORA"     			=> "13:49:00",
	  "CANTIDAD"     		=> "10",
	  "PUNTO_MEDIDA_COM"    => "",
	  "COMBUSTIBLE"     	=> "DIESEL-2",
	  "PUNTO_MEDIDA_KM"     => "",
	  "KILOMETRAJE"     	=> "174",
	  "PUNTO_MEDIDA_H"     	=> "",
	  "HORAS"     			=> "",
	  "PUNTO_MEDIDA_RENDIMIENTO"     => "",
	  "RENDIMIENTO"     	=> "",
	  "CENTRO"     			=> "",
	  "CENTRO_COSTO"     	=> "",
	);*/
	$params2 = array(
	  "CREAD0"     			=> "",
	  "MENSAJE_ERROR"		=> ""
	);
	$params = array(
		"ET_RETURN" => 	array(	"item"	=>	$params2	),
		"IT_DATOS"	=>	array(	"item"	=>	$params1	)
	);
	
	$soap_return = $soap_client->call($RFC_FUNC, $params);

	if ($soap_client->fault) {
		echo '<h2>Fault '.$RFC_FUNC.'</h2><pre>';
		print_r($soap_return);
		$status = 0;
		saveResponseRFCConsumo($idvale, json_encode($params), json_encode($soap_return), $status, $fromJob);
		echo "ERROR RFC ZMMRFC_IFCU_CONSUMO_APP<BR>";
		echo '</pre>';
	} else {
		// Check for errors
		$err = $soap_client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Error '.$RFC_FUNC.'</h2><pre>' . $err . '</pre>';
			$status = 0;
			saveResponseRFCConsumo($idvale, json_encode($params), json_encode($soap_return), $status, $fromJob);
		} else {
			// Process the result
			process_result($soap_return, $idvale, $params, $fromJob);
		}
	}	
}

function process_result($soap_return, $idvale, $params, $fromJob=false){
	
	if( !isset($soap_return['ET_RETURN']['item']) || empty($soap_return['ET_RETURN']['item']) ){
		echo "ERROR PROCESANDO RFC ZMMRFC_IFCU_CONSUMO_APP";
		$status = 0;
		saveResponseRFCConsumo($idvale, json_encode($params), "Empty response", $status, $fromJob);
		return false;
	}
	
	$data = $soap_return['ET_RETURN']['item'];
	
	$success_response = false;
	foreach($data as $d){
		if( $d['CREAD0'] == "X" ){
			$success_response = true;
		}
	}
	
	if($success_response){
		$status = 1;
		saveResponseRFCConsumo($idvale, json_encode($params), json_encode($soap_return), $status, $fromJob);
		echo "EXITO RFC ZMMRFC_IFCU_CONSUMO_APP<BR>";
    }else{
		$status = 0;
		saveResponseRFCConsumo($idvale, json_encode($params), json_encode($soap_return), $status, $fromJob);
		echo "ERROR RFC ZMMRFC_IFCU_CONSUMO_APP<BR>";
	}
	
}

function triggerWgetCall2($url, $idvale, $fromJob = false){
	include_once("../phps/setup.php");
	try {
		$url = ProjectManager::projectURL().$url;
		exec("wget -bqc -O /dev/null -o /dev/null " . $url);
	} catch (Exception $e) {
		saveResponseRFCConsumo($idvale, "No data", "No API call was made", "0", $fromJob);
	}
}
