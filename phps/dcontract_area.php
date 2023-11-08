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
function getAllContractAreas(){

    $sql = "select * from contract_area";

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
function registrarContractArea( $descripcion,$codigo){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into contract_area(descripcion,codigo) values ('$descripcion','$codigo')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

/**
 * @param $id
 * @return bool
 */
function eliminarContractArea($id){

    $sql = "delete from contract_area where id = $id";

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
function updateContractArea($id,$descripcion,$codigo){

    $descripcion =  mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update contract_area set descripcion='$descripcion',codigo='$codigo' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion,"codigo"=>$codigo);
}
