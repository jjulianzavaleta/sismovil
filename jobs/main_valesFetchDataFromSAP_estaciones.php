<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../phps/libreriasphp/nusoap/nusoap.php");
include_once("../phps/conexion.php");
include_once("../phps/dvales_centroWeb.php");
include_once("../phps/dvales_usuarioWeb.php");
include_once("../phps/dvales_equipoWeb.php");
include_once("../phps/dvales_grifos.php");

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('max_execution_time', '0');

$time_start = microtime(true);

call_rfc_chimu_block2();

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' seconds';

function call_rfc_chimu_block2(){
	//$wsdl_url = "http://gsfeccqas01.gsf.com.pe:8000/sap/bc/srt/wsdl/srvc_0050568F4DCC1EEB90FED36F68A089F4/wsdl11/allinone/ws_policy/document?sap-client=400";
	$wsdl_url = "http://GSFECCPRD01.gsf.com.pe:8000/sap/bc/srt/rfc/sap/zws_mm_estaciones_app/400/zws_mm_estaciones_app/zws_mm_estaciones_app";
	$endpoint = "http://GSFECCPRD01.gsf.com.pe:8000/sap/bc/srt/rfc/sap/zws_mm_estaciones_app/400/zws_mm_estaciones_app/zws_mm_estaciones_app";

    include_once("RFC_credentials.php");
    $soap_client = new nusoap_client($wsdl_url);
    $soap_client->setCredentials($RFC_USERNAME, $RFC_PASSWORD, 'basic');
	$soap_client->setEndpoint($endpoint);
	$soap_client->soap_defencoding = 'UTF-8';
	$soap_client->decode_utf8 = FALSE;
	
	call_rfc_estaciones($soap_client);
}

function call_rfc_estaciones(&$soap_client){
	
	$RFC_FUNC = "ZMMRFC_ESTACIONES_APP";
	
	$params = array(
	  "NRO_ESTACION"    => "",
	  "CENTRO"  		=> "",
	  "DESCRIPCION"     => "",
	  "FLUJO" 			=> "",
	  "ET_ESTACIONES"   => ""
	);
	
	$soap_return = $soap_client->call($RFC_FUNC, $params);

	if ($soap_client->fault) {
		echo '<h2>Fault '.$RFC_FUNC.'</h2><pre>';
		print_r($soap_return);
		echo '</pre>';
	} else {
		// Check for errors
		$err = $soap_client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Error '.$RFC_FUNC.'</h2><pre>' . $err . '</pre>';
		} else {
			// Process the result
			process_result_estaciones($soap_return);
		}
	}	
}
function process_result_estaciones($soap_return){
	
	if( !isset($soap_return['item']) || empty($soap_return['item']) ){
		echo "ERROR PROCESANDO RFC ESTACIONES ";
		return false;
	}
	
	$data = $soap_return['item'];
	$res  = upsertEstacionesFromRFC_full($data);
	if($res)
		echo "EXITO RFC ESTACIONES - ".sizeof($data)." elementos afectados<BR>";
    else
		echo "ERROR RFC ESTACIONES<BR>";
}
