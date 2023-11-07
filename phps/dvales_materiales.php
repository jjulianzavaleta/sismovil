<?php

function getAllValesMateriales(){

    $sql = "select * from vales_material";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarValesMateriales( $nombre,$cod_sap){

    $sql = "insert into vales_material(nombre,cod_sap) values ('$nombre','$cod_sap')";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarValesMateriales($id){

    $sql = "delete from vales_material where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateValesMateriales($id, $nombre, $cod_sap){

    $sql = "update vales_material set nombre = '$nombre',cod_sap = '$cod_sap' where id= $id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}
