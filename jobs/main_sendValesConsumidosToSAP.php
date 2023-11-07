<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../phps/conexion.php");
require_once("../phps/dvales_RFCConsumoVales.php");
require_once("../webserviceREST/callRFC_ConsumoVales.php");

exectSendConsumoValesToSAP();


function exectSendConsumoValesToSAP(){
	
	$days_interval 	  = 1;
	$vales_consumidos = getValesConsumidosInterval_rfc($days_interval);
	
	if( !empty($vales_consumidos) ){
		echo sizeof($vales_consumidos)." resultados:<br><br>";
		foreach($vales_consumidos as $vale){
			echo "idvale: ".$vale['idvale']." - fechaConsumo: ".$vale['consumo_fechaconsumo']."<br>";
			callRFC_ConsumoVales_execFromJob( $vale['idvale'] );
		}
	}else{
		echo "Ningun vale en el rango indicado";
	}
	
}
