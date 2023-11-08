<?php

function getAllValesCentroWeb(){

    $sql = "select * from vales_centroweb";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarValesCentroWeb( $kostl,$ktext){

    $sql = "insert into vales_centroweb(kostl,ktext) values ('$kostl','$ktext')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);

    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarValesCentroWeb($id){

    $sql = "delete from vales_centroweb where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateValesCentroWeb($id, $kostl,$ktext){

    $sql = "update vales_centroweb set ktext = '$ktext',kostl = '$kostl' where id= $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function exists_element_in_db($KOSTL, &$link){

    $sql = "select id from vales_centroweb where kostl = '".$KOSTL."'";

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

function upsertValesCentroWebFromRFC_full($data){
    $link = conectarBD();

    $array_chunks = array_chunk($data, 900);

    startTransaction($link);

    foreach ($array_chunks as $chunk){
        $res = upsertValesCentroWebFromRFC($chunk, $link);
        if(!$res)break;
    }

    if($res === false){
        return finishTransaction($link, false);
    }else{
        return finishTransaction($link, true);
    }

}

function upsertValesCentroWebFromRFC($data, &$link){

    $toCreate = array();
    $toUpdate = array();

    foreach($data as $d){
        $exists = exists_element_in_db($d['KOSTL'], $link);

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
            $data_sql.= "('".$d['KOSTL']."','".utf8_encode($d['KTEXT'])."'),";
        }
        $data_sql = substr($data_sql, 0, -1);
        $sql_create = "insert into vales_centroweb(kostl,ktext) values $data_sql;";
    }

    $sql_update = "";
    if( !empty($toUpdate) ){
        foreach($toUpdate as $d){
            $sql_update.= "update vales_centroweb set kostl = '".$d['KOSTL']."',ktext = '".utf8_encode($d['KTEXT'])."' where id = ".$d['id'].";";
        }
    }

    $sql = $sql_create.$sql_update;
    $res = queryBD($sql,$link,true, false, true);

    if($res === false)
        return false;
    else
        return true;
}
