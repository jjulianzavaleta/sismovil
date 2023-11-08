<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 11:31 PM
 */


/**
 * @return array|bool
 */
function getAllTipoContratos(){

    $sql = "select * from contract_tipocontrato";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

/**
 * @param $id
 * @param $descripcion
 * @return bool
 */
function registrarTipoContrato( $descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into contract_tipocontrato(descripcion) values ('$descripcion')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}

/**
 * @return int
 */
function getNewIdTipoContrato(){

    $id = 0;

    $sql = "select MAX(id) as id from contract_tipocontrato ";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    if($data === false){
        $id = null;
    }else{
        $id = intval($data['id']) + 1;
    }

    return $id;
}



/**
 * @param $id
 * @return bool
 */
function eliminarTipoContrato($id){

    $sql = "delete from contract_tipocontrato where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

/**
 * @param $id
 * @param $descripcion
 * @return array|bool
 */
function updateTipoContrato($id,$descripcion){

    $descripcion =  mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update contract_tipocontrato set descripcion='$descripcion' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}
