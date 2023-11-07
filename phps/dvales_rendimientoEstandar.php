<?php

function getEquipoByPlaca_re($placa){

	if( empty($placa) )return array();
	
    $sql = "select id from vales_equipoweb where upper(TRIM(license_num)) = '".strtoupper(trim($placa))."'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if( !empty($data) && sizeof($data) == 1 ){
		return $data[0]['id'];
	}else{
		return array();
	}

}

function update_equipos_rendimieinto_estandar($data,$userId){
	
	if( empty($data) ) return false;
	
	$sql = "";
	foreach($data as $element){
		$sql.= "UPDATE vales_equipoweb SET ruta = '".$element['ruta']."', rendimiento_estandar = '".$element['re']."', re_fecha_updated = GETDATE(), re_userid_updated = ".$userId." WHERE id = ".$element['idequipo'].";";
	}
	
	$link = conectarBD();
			
    $res = queryBD($sql,$link);	
	
	$link = null;
	
	return !($res===false);
}