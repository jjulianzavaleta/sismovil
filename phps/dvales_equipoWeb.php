<?php

function getAllCentroCostoWebForCombobox(){

    $sql = "select * from vales_centroweb order by ktext asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getCentroCostoName($id){
	
	$sql = "select ktext from vales_centroweb where id = ".$id;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;
	
	if(!empty($data))
		return $data[0]['ktext'];
	else
		return false;
}

function getAllValesEquipoWeb(){

    $sql = "select ve.medida_contador, ve.id, ve.kostl, ve.equnr, ve.txt_hequi, ve.license_num, ve.sttxt , cc.ktext
            from vales_equipoweb ve
            inner join vales_centroweb cc on cc.id = ve.kostl";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarValesEquipoWeb( $equnr,$txt_hequi,$kostl,$license_num,$sttxt,$contadortipo){

    $sql = "insert into vales_equipoweb(equnr,txt_hequi,kostl,license_num,sttxt,medida_contador) values ('$equnr','$txt_hequi','$kostl','$license_num','$sttxt',$contadortipo)";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function getIdCentroCosto($KOSTL, &$link){
	
	$sql = "select id from vales_centroweb where kostl = '".$KOSTL."'";
	
    $res = queryBD($sql,$link,true, false);

   if($res === false)
        return false;
    else{
		if(empty($res))
			return 3;//CENTRO DE COSTO = NO EXISTE
		else
			return $res['id'];
	}
	
}

function eliminarValesEquipoWeb($id){

    $sql = "delete from vales_equipoweb where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateValesEquipoWeb($id, $equnr,$txt_hequi,$kostl,$license_num,$sttxt,$contadortipo){

    $sql = "update vales_equipoweb set equnr='$equnr',txt_hequi='$txt_hequi',kostl='$kostl',license_num='$license_num',sttxt='$sttxt',medida_contador=$contadortipo where id= $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function exists_element_in_db_2($equnr, &$link){
	
	$sql = "select id from vales_equipoweb where equnr = '".$equnr."'";

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

function upsertEquipoWebFromRFC_full($data){
    $link = conectarBD();

    $array_chunks = array_chunk($data, 900);

    startTransaction($link);

    foreach ($array_chunks as $chunk){
        $res = upsertEquipoWebFromRFC($chunk, $link);
        if(!$res)break;
    }

    if($res === false){
        return finishTransaction($link, false);
    }else{
        return finishTransaction($link, true);
    }

}

function upsertEquipoWebFromRFC($data, &$link){
	
	$toCreate = array();
	$toUpdate = array();
	
	foreach($data as $d){
		$exists         = exists_element_in_db_2($d['EQUNR'], $link);
		$id_centrocosto = getIdCentroCosto($d['KOSTL'], $link);
		
		$d['idcentrocosto']    =$id_centrocosto;
		
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
			$data_sql.= "('".$d['EQUNR']."','".utf8_encode(pg_escape_string($d['EQKTX']))."','1',".$d['idcentrocosto'].",'".$d['PLACA']."'),";
		}
		$data_sql = substr($data_sql, 0, -1);
		$sql_create = "insert into vales_equipoweb(equnr,txt_hequi,sttxt,kostl,license_num) values $data_sql;";
	}
	
	$sql_update = "";
	if( !empty($toUpdate) ){		
		foreach($toUpdate as $d){
			$sql_update.= "update vales_equipoweb set equnr = '".$d['EQUNR']."',txt_hequi = '".utf8_encode(pg_escape_string($d['EQKTX']))."', sttxt ='1', kostl = ".$d['idcentrocosto'].", license_num = '".$d['PLACA']."' where id = ".$d['id'].";";
		}
	}
	
	$sql = $sql_create.$sql_update;
    $res = queryBD($sql,$link,true, false, true);

    if($res === false)
        return false;
    else
        return true;
}
