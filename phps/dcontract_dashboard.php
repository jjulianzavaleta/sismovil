<?php

function getTop3AreasSolCreadas($year,$max_areas){
	
	$areas = getAreasMasActivas($max_areas,$year);
	
	$data = array();
	foreach($areas  as $area){
		$data[$area['reqgen_a_areasolicitante']] = getRegistrosByAreaAndMonth($area['reqgen_a_areasolicitante'],$year);
	}
	
	return $data;
}

function getRegistrosByAreaAndMonth($area,$year){

	$sub_sql = "(select count(*) as nro
				 from contract_solcontrato 
				 where datosgenerales_estado in (0,1,2,3,4,5)
				 AND MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=XXXX
				 AND YEAR(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=".$year."
				 AND reqgen_a_areasolicitante='".$area."')";
				 
	$link = conectarBD();
	
	$sql = "select 
				".str_replace("XXXX", "1", $sub_sql)." as enero,
				".str_replace("XXXX", "2", $sub_sql)." as febrero,
				".str_replace("XXXX", "3", $sub_sql)." as marzo,
				".str_replace("XXXX", "4", $sub_sql)." as abril,
				".str_replace("XXXX", "5", $sub_sql)." as mayo,
				".str_replace("XXXX", "6", $sub_sql)." as junio,
				".str_replace("XXXX", "7", $sub_sql)." as julio,
				".str_replace("XXXX", "8", $sub_sql)." as agosto,
				".str_replace("XXXX", "9", $sub_sql)." as septiembre,
				".str_replace("XXXX", "10", $sub_sql)." as octubre,
				".str_replace("XXXX", "11", $sub_sql)." as noviembre,
				".str_replace("XXXX", "12", $sub_sql)." as diciembre";

	
    $res = queryBD( $sql, $link);
	
	if($res === false){
		return false;
	}else{
	  return $res[0];
	}
}

function getAreasMasActivas($max_areas,$year){	
	
	$link = conectarBD();
	
	$sql = " select TOP ".$max_areas."reqgen_a_areasolicitante, count(*)
			from contract_solcontrato
			where datosgenerales_estado in (0,1,2,3,4,5) 			
			and YEAR(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=".$year."
			group by reqgen_a_areasolicitante
			order by count(*) desc";
	
    $res = queryBD( $sql, $link);
	
	if($res === false){
		return false;
	}else{
	  return $res;
	}
}

function getRegistradosbyMonth($year,$estados,$fecha_procesado=false,$anulado=true,$fecha_anulado=false,$userId=0){
	
	$sql_date = "MONTH(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=XXXX";
	if($fecha_procesado)
		$sql_date = "MONTH(procesado_time)=XXXX";
	if($fecha_anulado)
		$sql_date = "MONTH(anulado_fecha)=XXXX AND anulado=1";
	
	$sql_anulado = "AND anulado=0";
	if($anulado===false)
		$sql_anulado = "";
	
	$sql_user = "";
	if(!empty($userId))
		$sql_user = " AND datosgenerales_usuarioregistra=".$userId;
	
	$sub_sql = "(select count(*) as nro
				from contract_solcontrato 
				where datosgenerales_estado in (".implode(",", $estados)." )
					  AND ".$sql_date."
					  ".$sql_anulado."
					  AND YEAR(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=".$year.$sql_user.")";
	
	$link = conectarBD();
	
	$sql = "select 
				".str_replace("XXXX", "1", $sub_sql)." as enero,
				".str_replace("XXXX", "2", $sub_sql)." as febrero,
				".str_replace("XXXX", "3", $sub_sql)." as marzo,
				".str_replace("XXXX", "4", $sub_sql)." as abril,
				".str_replace("XXXX", "5", $sub_sql)." as mayo,
				".str_replace("XXXX", "6", $sub_sql)." as junio,
				".str_replace("XXXX", "7", $sub_sql)." as julio,
				".str_replace("XXXX", "8", $sub_sql)." as agosto,
				".str_replace("XXXX", "9", $sub_sql)." as septiembre,
				".str_replace("XXXX", "10", $sub_sql)." as octubre,
				".str_replace("XXXX", "11", $sub_sql)." as noviembre,
				".str_replace("XXXX", "12", $sub_sql)." as diciembre";

    $res = queryBD( $sql, $link);

	if($res === false){
		return false;
	}else{
	  return $res[0];
	}
}

function getContratosPorVencer($nro_dias_para_vencer,$menorIgual=false,$userId=0){
	
	$operador = " = ";
	if($menorIgual)
		$operador = " <= ";
	
	$sql_user = "";
	if(!empty($userId))
		$sql_user = " AND datosgenerales_usuarioregistra=".$userId;
	
	$link = conectarBD();
	
	$sql = " SELECT count(*) as nro
			 FROM contract_solcontrato
			 WHERE anulado = 0 
			       AND termiesp_c_fechafin IS NOT NULL
				   AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime)) >= 0
			       AND datosgenerales_estado in (4) 
				   AND DATEDIFF(day,GETDATE(), CAST(termiesp_c_fechafin AS datetime))".$operador.$nro_dias_para_vencer."
			       AND anulado=0".$sql_user;
    $res = queryBD( $sql, $link);

	if($res === false){
		return false;
	}else{
	  return $res[0]['nro'];
	}
}

function getContratosByEstados($estados,$year,$userId=0,$noDate=false){
	
	$link = conectarBD();
	
	$sql_user = "";
	if(!empty($userId))
		$sql_user = " AND datosgenerales_usuarioregistra=".$userId;

    $sql_date = " AND YEAR(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=".$year;
    if($noDate)
        $sql_date = "";

	$sql = "select count(*) as nro
			from contract_solcontrato 
			where datosgenerales_estado in (".implode(",", $estados)." )
				  ".$sql_date.
				  "AND anulado=0".$sql_user;
	
    $res = queryBD( $sql, $link);
	
	if($res === false){
		return false;
	}else{
	  return $res[0]['nro'];
	}
}

function getContratosAnulados($year,$userId=0){
	
	$link = conectarBD();
	
	$sql_user = "";
	if(!empty($userId))
		$sql_user = " AND datosgenerales_usuarioregistra=".$userId;
	
	$sql = "select count(*) as nro
			from contract_solcontrato
			where anulado = 1
			and YEAR(CAST(SUBSTRING(datosgenerales_fecharegistra,0,11) AS date))=".$year.$sql_user;
	
    $res = queryBD( $sql, $link);
	
	if($res === false){
		return false;
	}else{
	  return $res[0]['nro'];
	}
}

function cal_max($data_from_area){
	
	$data_max = array();
	foreach($data_from_area as $data){
		$data_max[] = max($data);
	}
	if( empty($data_max))
		return 0;
	else
		return max($data_max);
}