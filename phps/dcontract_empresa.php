<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 11:31 PM
 */

include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllEmpresas(){

    $sql = "select * from contract_empresa";

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
function registrarEmpresa( $descripcion){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into contract_empresa(descripcion) values ('$descripcion')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}

/**
 * @param $id
 * @return bool
 */
function eliminarEmpresa($id){

    $sql = "delete from contract_empresa where id = $id";

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
function updateEmpresa($id,$descripcion){

    $descripcion =  mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update contract_empresa set descripcion='$descripcion' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}
