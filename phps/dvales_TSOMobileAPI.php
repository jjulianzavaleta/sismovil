<?php

function getPlacaVehiculoByVale($idvale){

    $sql = "select placa from vales_vale where id = ".$idvale;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(empty($data)){
		return "";
	}else{
		return $data[0]['placa'];
	}

}

function getLastFechaConsumoByPlaca($placa, $idvale, $fromJob = false){
	
	$sql_filterByFechaConsumo = "";
	if($fromJob)
		$sql_filterByFechaConsumo = " AND consumo_fechaconsumo < (select consumo_fechaconsumo from vales_vale where id = ".$idvale.")";
	
	$sql = "select TOP 1 convert(varchar, consumo_fechaconsumo, 25) as consumo_fechaconsumo 
			from vales_vale 
			where placa = '".$placa."' and estado = 3 and id != ".$idvale.$sql_filterByFechaConsumo.
			" order by consumo_fechaconsumo desc;";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(empty($data)){
		return "";
	}else{
		return $data[0]['consumo_fechaconsumo'];
	}
}

function saveKilometrajeFrmTSOMobile($idvale, $kilometraje, $placa, $tsomobile__fromJob, $response, $endpoint){
	
	$fromJob = $tsomobile__fromJob?1:0;
	
	$sql = "update vales_vale set tsomobile_kilometraje = '".$kilometraje."', tsomobile_byjob = ".$fromJob.", tsomobile_fechaconsulta = GETDATE(), tsomobile_response = '".$response."', tsomobile_endpoint = '".$endpoint."', tsomobile_somethingwentwrong = 0 where id = ".$idvale;

    $link = conectarBD();
    $res = queryBD($sql,$link,true);

    if($res === false)
        return false;
    else
        return true;
	
}

function saveSomethingWentWrongFrmTSOMobile($idvale, $response, $tsomobile__fromJob, $endpoint){
	
	$fromJob = $tsomobile__fromJob?1:0;
	
	$sql = "update vales_vale set tsomobile_fechaconsulta = GETDATE(), tsomobile_somethingwentwrong = 1, tsomobile_byjob = ".$fromJob.", tsomobile_response = '".$response."', tsomobile_endpoint = '".$endpoint."' where id = ".$idvale;

    $link = conectarBD();
    $res = queryBD($sql,$link,true);

    if($res === false)
        return false;
    else
        return true;
	
}

function getValesConsumidosInterval($days){
	
	$sql = "select id as idvale, convert(varchar, consumo_fechaconsumo, 25) as consumo_fechaconsumo
			from vales_vale
			where consumo_fechaconsumo >=  DATEADD(DAY,-".$days.",GETDATE())
			order by consumo_fechaconsumo desc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(empty($data)){
		return "";
	}else{
		return $data;
	}
}
