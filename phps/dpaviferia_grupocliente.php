<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 28/09/2015
 * Time: 10:57 PM
 */

include_once("conexion.php");


function getAllGruposPaviferiaCliente(){

    $sql = "select * from paviferia_grupocliente";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarGrupoPaviferiaCliente( $id, $descripcion, $descuento, $idusuario){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "insert into paviferia_grupocliente(id,descripcion,descuento,usuarioregistra,fecharegistra)
            values ($id,'$descripcion',$descuento,$idusuario,GETDATE())";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function updateGrupoPaviferiaCliente($id,$descripcion,$descuento, $idusuario){

    $descripcion = mb_strtoupper($descripcion, 'UTF-8');

    $sql = "update paviferia_grupocliente
            set descripcion='$descripcion', descuento = $descuento, usuariomodifica = $idusuario, fechamodifica = GETDATE()
            where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarGrupoPaviferiaCliente($id){

    $sql = "delete from paviferia_grupocliente where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function getNewIdGrupoPaviferiaCliente(){

    $id = 0;

    $sql = "select MAX(id) as id from paviferia_grupocliente ";

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