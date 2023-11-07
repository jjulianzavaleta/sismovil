<?php
/**
 * Created by PhpStorm.
 * User: Zod
 * Date: 28/09/2015
 * Time: 11:34 PM
 */

include_once("conexion.php");


function getAllClientesPaviferiaDescuentos(){

    $sql = "select paviferia_clientesdescuentos.*, paviferia_grupocliente.descripcion as grupo
            from paviferia_clientesdescuentos
            inner join paviferia_grupocliente on paviferia_grupocliente.id = paviferia_clientesdescuentos.idgrupo";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarClientesPaviferiaDescuento( $nrodocumento, $nombre_rzsocial, $idgrupo,  $idusuario){

    $nombre_rzsocial = mb_strtoupper($nombre_rzsocial, 'UTF-8');

    $sql = "insert into paviferia_clientesdescuentos(nrodocumento,nombre_rzsocial,idgrupo,usuarioregistra,fecharegistra)
            values ('$nrodocumento','$nombre_rzsocial',$idgrupo,$idusuario,GETDATE())";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function updateClientesPaviferiaDescuento( $id,$nrodocumento, $nombre_rzsocial, $idgrupo,  $idusuario){

    $nombre_rzsocial = mb_strtoupper($nombre_rzsocial, 'UTF-8');

    $sql = "update paviferia_clientesdescuentos set
            nrodocumento='$nrodocumento',nombre_rzsocial='$nombre_rzsocial',idgrupo=$idgrupo,
            usuariomodifica=$idusuario,fechamodifica=GETDATE()
            where id = $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarClientePaviferiaDescuento( $id){

    $sql = "delete from paviferia_clientesdescuentos where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}