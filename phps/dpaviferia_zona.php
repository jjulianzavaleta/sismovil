<?php
/**
 * Created by PhpStorm.
 * User: zod
 * Date: 04/08/15
 * Time: 01:49 AM
 */

include_once("conexion.php");

/**
 * @return array|bool
 */
function getAllZonas(){

    $sql = "select * from paviferia_zona";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getZonaById($idzona){

    $sql = "select * from paviferia_zona where id =  $idzona";

    $link = conectarBD();
    $data = queryBD($sql,$link,true);
    $link = null;

    return $data;
}

/**
 * @param $id
 * @param $descripcion
 * @return bool
 */
function registrarZona( $id, $descripcion, $direccion, $telefonos){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_zona(id,descripcion,direccion,telefono) values ($id,'$descripcion','$direccion','$telefonos')";

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
function getNewIdZona(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_zona ";

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
function eliminarZona($id){

    $sql = "delete from paviferia_zona where id = $id";

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
function updateZona($id,$descripcion, $direccion, $telefono){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update paviferia_zona set descripcion='$descripcion', direccion = '$direccion', telefono = '$telefono' where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"descripcion"=>$descripcion);
}
