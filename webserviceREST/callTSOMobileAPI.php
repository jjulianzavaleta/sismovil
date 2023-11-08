<?php 
 
/*if(isset($_GET['run']))
	callTSOMobileAPI_exec( 4581 );
callTSOMobileAPI_getKilometraje( 1227 );*/
 
if( isset($_GET['idvale']) )
	callTSOMobileAPI_getKilometraje($_GET['idvale']);
	
if( isset($_GET['idvaleJob']) )
	callTSOMobileAPI_getKilometraje($_GET['idvaleJob'], true);

function callTSOMobileAPI_exec( $idvale ){	
	$url = "/webserviceREST/callTSOMobileAPI.php?idvale=".$idvale;
	triggerWgetCall($url, $idvale);
}

function callTSOMobileAPI_execFromJob( $idvale ){	
	$url = "/webserviceREST/callTSOMobileAPI.php?idvaleJob=".$idvale;	
	triggerWgetCall($url, $idvale, true);
}

function callTSOMobileAPI_getKilometraje( $idvale, $fromJob = false ){
	
	require_once('../phps/libreriasphp/guzzlehttp/vendor/autoload.php'); 
	require_once('../phps/conexion.php');
	require_once('../phps/dvales_TSOMobileAPI.php');
	
	$placa 				= getPlacaVehiculoByVale($idvale);
	
	if( empty($placa) )die("Error: No se encontro placa");
	
	$lastFechaConsumo 	= calculateLastFechaConsumoByPlaca($placa, $idvale, $fromJob);
	
	global $tsomobile__idvale;
	global $tsomobile__placa;
	global $tsomobile__endpoint;
	global $tsomobile__fromJob;
	$tsomobile__idvale	=	$idvale;
	$tsomobile__placa	=	$placa;
	$tsomobile__fromJob =   $fromJob;

	$token 		= "9HYws8vYxlh9BkkZ2XmAihEy8hN20cI0";
	$fromDate 	= $lastFechaConsumo;
	$toDate 	= date("Y-m-d")."%20".date("H:i:s");	
	$endpoint	= 'http://api.chimuagropecuaria.com.pe/GET/mileage.php?token='.$token.'&plate='.$placa.'&date_from='.$fromDate.'&date_to='.$toDate.'&fromSV=true';
	$tsomobile__endpoint	=	$endpoint;

	$client = new GuzzleHttp\Client(['timeout' => 60]);
	// Send an asynchronous request.
	$request = new \GuzzleHttp\Psr7\Request('GET', $endpoint );
	$promise = $client->sendAsync($request)->then(function ($res) {
		
		global $tsomobile__idvale;
		global $tsomobile__placa;
		global $tsomobile__endpoint;
		global $tsomobile__fromJob;
		
		$statuscode    = $res->getStatusCode();
		$text_response = $res->getBody();
		
		if ($statuscode == 200) {
			$response = json_decode($res->getBody(),true);
		 
			$key_response =   $response['response'];
			
			if( $response['status'] == "ok" && isset($key_response['mileage']) ){
				$milage       =   $key_response['mileage'];
				if( is_numeric($milage) && !empty($milage) ){
					saveKilometrajeFrmTSOMobile($tsomobile__idvale, $milage, $tsomobile__placa, $tsomobile__fromJob, $text_response, $tsomobile__endpoint);
					echo "Success new kilometraje: ".$milage."<br>".$tsomobile__endpoint;
				}else{
					saveSomethingWentWrongFrmTSOMobile($tsomobile__idvale, $text_response, $tsomobile__fromJob, $tsomobile__endpoint);
					echo "Error failed to get new kilometraje: ".$milage."<br>".$tsomobile__endpoint;
				}				
			}else{
				saveSomethingWentWrongFrmTSOMobile($tsomobile__idvale, $text_response, $tsomobile__fromJob, $tsomobile__endpoint);
				echo "Error failed to get new kilometraje: ".($res->getBody())."<br>".$tsomobile__endpoint;
			}
			
		}else{
			saveSomethingWentWrongFrmTSOMobile($tsomobile__idvale, $text_response, $tsomobile__fromJob, $tsomobile__endpoint);
			echo "Error failed http status code is ".$statuscode."<br>".$tsomobile__endpoint;;
		}
	});

	try {
		$promise->wait();
	} catch (Exception $e) {
		saveSomethingWentWrongFrmTSOMobile($idvale, $e->getMessage(), $tsomobile__fromJob, $tsomobile__endpoint);
		die("Sistema Contratos - callTSOMobileAPI_getKilometraje method ERROR: ".$e->getMessage());
	}
}

function calculateLastFechaConsumoByPlaca($placa, $idvale, $fromJob = false){
	
	$lastFechaConsumo = getLastFechaConsumoByPlaca($placa, $idvale, $fromJob);
	
	if( empty($lastFechaConsumo) ){
		return date('Y-m-d',(strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ) ))."%20".date("H:i:s");;
	}else{
		return formatFecha($lastFechaConsumo);
	}
}

function formatFecha($fecha){
	try {
		$pieces = explode(".", $fecha);
		$fecha  = $pieces[0];
		$fecha  = str_replace(" ","%20",$fecha);
	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
		return date("Y-m-d")."%2000:00:00";;
	}finally {
		return $fecha;
	}
}

function triggerWgetCall($url, $idvale, $fromJob = false){
	require_once("../phps/setup.php");
	$url = ProjectManager::projectURL().$url;
	try {		
		exec("wget -bqc -O /dev/null -o /dev/null " . $url);
	} catch (Exception $e) {
		saveSomethingWentWrongFrmTSOMobile($idvale, $e->getMessage(), $fromJob, "No API call was made");
	}
}