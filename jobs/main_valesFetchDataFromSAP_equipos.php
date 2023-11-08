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

call_rfc_chimu_block1();

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' seconds';

function call_rfc_chimu_block1(){
	//$wsdl_url = "http://solman.san-fernando.com.pe:8600/sap/bc/srt/wsdl/srvc_9EC0303176021EDA80976C7BD32942BF/wsdl11/allinone/ws_policy/document?sap-client=400";
	$wsdl_url = "http://solman.san-fernando.com.pe:8700/sap/bc/srt/rfc/sap/zpmws_combustible/400/service/binding";
	$endpoint = "http://solman.san-fernando.com.pe:8700/sap/bc/srt/rfc/sap/zpmws_combustible/400/service/binding";

    include_once("RFC_credentials.php");
    $soap_client = new nusoap_client($wsdl_url);
    $soap_client->setCredentials($RFC_USERNAME, $RFC_PASSWORD, 'basic');
	$soap_client->setEndpoint($endpoint);
	$soap_client->soap_defencoding = 'UTF-8';

	call_rfc_equipos($soap_client);
}

function call_rfc_equipos(&$soap_client){
	
	$RFC_FUNC = "ZPMRFC_DATOS_EQUIPO";
	
	$params = array(
	  "IP_ALL"    => "X",
	  "IP_BUKRS"  => "CHIM",
	  "IP_PLACA"  => "",
	  "ET_EQUIPO" => ""
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
			process_result_equipos($soap_return);
		}
	}	
}

function process_result_equipos($soap_return){
	
	if( !isset($soap_return['item']) || empty($soap_return['item']) ){
		echo "ERROR PROCESANDO RFC EQUIPO ";
		return false;
	}
		
	$data = $soap_return['item'];
	$res  = upsertEquipoWebFromRFC_full($data);
	if($res)
		echo "EXITO RFC EQUIPO - ".sizeof($data)." elementos afectados<BR>";
    else
		echo "ERROR RFC EQUIPO<BR>";
}
