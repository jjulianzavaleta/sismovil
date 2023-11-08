<?php
/**
 * Created by PhpStorm.
 * User: Escalab
 * Date: 28/08/2015
 * Time: 03:00 PM
 */
include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllGruposPaviferia(){

    $sql = "select * from paviferia_grupo";

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
function registrarGrupoPaviferia( $id, $descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_grupo(id,descripcion) values ($id,'$descripcion')";

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
function getNewIdGrupoPaviferia(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_grupo ";

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
function eliminarGrupoPaviferia($id){

    $sql = "delete from paviferia_grupo where id = $id";

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
function updateGrupoPaviferia($id,$descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update paviferia_grupo set descripcion='$descripcion' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}
