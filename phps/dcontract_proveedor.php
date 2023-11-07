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
function getAllProveedores(){

    $sql = "select * from contract_proveedor";

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
function registrarProveedor($ruc, $razon_social){

    $razon_social = mb_strtoupper($razon_social, 'UTF-8');

    $sql = "insert into contract_proveedor(ruc,razon_social) values ('$ruc', '$razon_social')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("idproveedor"=>$id,"ruc"=>$ruc, "razon_social"=> $razon_social);
}

/**
 * @param $id
 * @return bool
 */
function eliminarProveedor($id){

    $sql = "delete from contract_proveedor where idproveedor = $id";

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
function updateProveedor($id,$ruc,$razon_social){

	$razon_social =  mb_strtoupper($razon_social, 'UTF-8');

    $sql = "update contract_proveedor set ruc='$ruc', razon_social = '$razon_social' where idproveedor=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return array("id"=>$id,"ruc"=>$ruc,"razon_social"=>$razon_social);
}
