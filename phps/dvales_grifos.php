<?php

function getAllValesGrifos(){

    $sql = "select * from vales_grifo";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarValesGrifos( $nombre,$descripcion){

    $sql = "insert into vales_grifo(nombre,descripcion) values ('$nombre','$descripcion')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarValesGrifos($id){

    $sql = "delete from vales_grifo where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateValesGrifos($id, $nombre, $descripcion){

    $sql = "update vales_grifo set nombre = '$nombre',descripcion = '$descripcion' where id= $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function exists_element_in_db5($nroestacion, &$link){
	
	$sql = "select id from vales_grifo where nroestacion = '".$nroestacion."'";

    $res = queryBD($sql,$link,true, false);

    if($res === false)
        return false;
    else{
		if(empty($res))
			return false;
		else
			return $res['id'];
	}
	
}

function upsertEstacionesFromRFC_full($data){
    $link = conectarBD();

    $array_chunks = array_chunk($data, 900);

    startTransaction($link);

    foreach ($array_chunks as $chunk){
        $res = upsertEstacionesFromRFC($chunk, $link);
        if(!$res)break;
    }

    if($res === false){
        return finishTransaction($link, false);
    }else{
        return finishTransaction($link, true);
    }

}

function upsertEstacionesFromRFC($data, &$link){
	
	$toCreate = array();
	$toUpdate = array();
	
	foreach($data as $d){
		$exists = exists_element_in_db5($d['NRO_ESTACION'], $link);
		
		if($exists===false){
			$toCreate[] = $d;
	    }else{
			$d['id']    = $exists;
			$toUpdate[] = $d;			
		}		
	}
	
	$sql_create = "";
	if( !empty($toCreate) ){
		$data_sql = "";
		foreach($toCreate as $d){
			$data_sql.= "('".$d['NRO_ESTACION']."','".($d['DESCRIPCION'])."','".$d['FLUJO']."'),";
		}
		$data_sql = substr($data_sql, 0, -1);
		$sql_create = "insert into vales_grifo(nroestacion,nombre,flujo) values $data_sql;";
	}
	
	$sql_update = "";
	if( !empty($toUpdate) ){		
		foreach($toUpdate as $d){
			$sql_update.= "update vales_grifo set nroestacion = '".$d['NRO_ESTACION']."',nombre = '".($d['DESCRIPCION'])."', flujo = '".$d['FLUJO']."' where id = ".$d['id'].";";
		}
	}
	
	$sql = $sql_create.$sql_update;
    $res = queryBD($sql,$link,true, false, true);

    if($res === false)
        return false;
    else
        return true;
}
