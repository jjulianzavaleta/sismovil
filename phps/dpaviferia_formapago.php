<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 23/08/2015
 * Time: 06:02 PM
 */
include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllFormaPago(){

    $sql = "select * from paviferia_formapago order by id";

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
function registrarFormaPago( $id, $descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_formapago(id,descripcion) values ($id,'$descripcion')";

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
function getNewIdFormaPago(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_formapago ";

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
function eliminarFormaPago($id){

    $sql = "delete from paviferia_formapago where id = $id";

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
function updateFormaPago($id,$descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update paviferia_formapago set descripcion='$descripcion' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}
