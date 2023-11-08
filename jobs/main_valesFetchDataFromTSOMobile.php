<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../phps/conexion.php");
require_once("../phps/dvales_TSOMobileAPI.php");
require_once("../webserviceREST/callTSOMobileAPI.php");

exectFetchKilometrajeValesFromTSOMobile();


function exectFetchKilometrajeValesFromTSOMobile(){
	
	$days_interval 	  = 2;
	$vales_consumidos = getValesConsumidosInterval($days_interval);
	
	if( !empty($vales_consumidos) ){
		echo sizeof($vales_consumidos)." resultados:<br><br>";
		foreach($vales_consumidos as $vale){
			echo "idvale: ".$vale['idvale']." - fechaConsumo: ".$vale['consumo_fechaconsumo']."<br>";
			callTSOMobileAPI_execFromJob( $vale['idvale'] );
		}
	}else{
		echo "Ningun vale en el rango indicado";
	}
	
}