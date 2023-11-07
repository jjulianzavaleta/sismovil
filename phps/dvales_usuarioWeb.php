<?php

function getAllValesUsuarioWeb(){

    $sql = "select * from vales_usuarioweb";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarUsuarioEquipoWeb( $cod_conductor, $nombres, $num_doc_identidad, $isflujoconsumidor){

    $sql = "insert into vales_usuarioweb ( cod_conductor, name1, num_doc_identidad, estado, isflujoconsumidor) values ( '$cod_conductor', '$nombres', '$num_doc_identidad', 1, $isflujoconsumidor )";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarUsuarioEquipoWeb($id){

    $sql = "delete from vales_usuarioweb where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateUsuarioEquipoWeb($id, $nombres, $num_doc_identidad, $cod_conductor, $estado, $isflujoconsumidor){

    $sql = "update vales_usuarioweb set cod_conductor='$cod_conductor', name1='$nombres', num_doc_identidad='$num_doc_identidad', estado=$estado, isflujoconsumidor=$isflujoconsumidor where id= $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function exists_element_in_db_1( $cod_conductor, &$link){

	$sql = "select id from vales_usuarioweb where cod_conductor = '".$cod_conductor."'";

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

function upsertUsuarioWebFromRFC_full($data){
    $link = conectarBD();

    $array_chunks = array_chunk($data, 900);

    startTransaction($link);

    foreach ($array_chunks as $chunk){
        $res = upsertUsuarioWebFromRFC($chunk, $link);
        if(!$res)break;
    }

    if($res === false){
        return finishTransaction($link, false);
    }else{
        return finishTransaction($link, true);
    }

}

function upsertUsuarioWebFromRFC($data, &$link){
	
	$toCreate = array();
	$toUpdate = array();
	
	foreach($data as $d){
		$d['STCD2'] = extract_dni($d['STCD2']);
		$exists = exists_element_in_db_1( $d['KUNNR'], $link);
		
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
		    if(empty($d['STCD2']))
                $d['STCD2'] = "NO_DNI_PROVIDED_".$d['KUNNR'];
			$data_sql.= "('".$d['KUNNR']."','".utf8_encode(pg_escape_string($d['NAME1']))."','".$d['STCD2']."',1),";
		}
		$data_sql = substr($data_sql, 0, -1);
		$sql_create = "insert into vales_usuarioweb(cod_conductor,name1,num_doc_identidad,estado) values $data_sql;";
	}
	
	$sql_update = "";
	if( !empty($toUpdate) ){		
		foreach($toUpdate as $d){
			$sql_update.= "update vales_usuarioweb set cod_conductor = '".$d['KUNNR']."',name1 = '".utf8_encode(pg_escape_string($d['NAME1']))."', num_doc_identidad = '".$d['STCD2']."' where id = ".$d['id'].";";
		}
	}
	
	$sql = $sql_create.$sql_update;
    $res = queryBD($sql,$link,true, false, true);

    if($res === false)
        return false;
    else
        return true;
}

function extract_dni($dni_with_letter){
	
	if(empty($dni_with_letter))return "";
	
	if(strpos("-", $dni_with_letter) !== false){
		$pieces = explode("-", $dni_with_letter);
		return $pieces[1];
	} else{
		return preg_replace('/[^0-9]/', '', $dni_with_letter);
	}
}
