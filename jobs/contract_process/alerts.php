<?php

function exectAlerts_contratos(){
	notify45DaysbeforeContractExpires();
	notify30DaysbeforeContractExpires();
	notify15OrLessDaysbeforeContractExpires();
}

function notify45DaysbeforeContractExpires(){
	/*
	 - Obtener lista de contratos a vencer en 45 dias
	 - Enviar notificacion
	*/
	$nro_dias_para_vencer = 45;
	notifyContractExpiresInDays($nro_dias_para_vencer);
}

function notify30DaysbeforeContractExpires(){
	/*
	 - Obtener lista de contratos a vencer en 30 dias
	 - Enviar notificacion
	*/
	$nro_dias_para_vencer = 30;
	notifyContractExpiresInDays($nro_dias_para_vencer);
}

function notify15OrLessDaysbeforeContractExpires(){
	/*
	 - Obtener lista de contratos a vencer en 15 dias o menos
	 - Enviar notificacion
	*/
	$nro_dias_para_vencer = 15;
	$rangoMenorIgual = true;
	notifyContractExpiresInDays($nro_dias_para_vencer,$rangoMenorIgual);
}

function notifyContractExpiresInDays($nro_dias_para_vencer,$rangoMenorIgual=false){
	
	$contratos_por_vencer = getContratosVencidos_alert($nro_dias_para_vencer,$rangoMenorIgual);
	$status = "";
	
	foreach($contratos_por_vencer as $contrato){
		$status_email = sendNotification_ContratoPorVencer($contrato['id'],$contrato['diasparacaducar']);
		
		if($status_email === true){
			$status.= "Contrato id: ".$contrato['id']." -- Exito.<br>";
		}else{
			$status.= "Contrato id: ".$contrato['id']." -- Error: ".$status_email['error']."<br>";
		}			
	}
	
	//Send status
	if( empty($status) ){
		$status = "Data is empty.";
	}
	$body = "Filtros: <br> Nro dias por vencer: ".$nro_dias_para_vencer.
		         " y rango menor igual es ".($rangoMenorIgual?"true":"false")."<br><br>".
				 " ".$status;
	$title = "Status Contratos Emailer Alerts ".date("Y-m-d");
	send_status_by_email($title, $body);
}
