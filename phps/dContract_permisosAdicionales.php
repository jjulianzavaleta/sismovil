<?php

function getAllPermisosAdicionales(){

    $sql = "select caj.* , cuh.usuario, ca.descripcion as area
            from contract_areajefaturas caj
            inner join contract_usuarioshabilitados cuh on caj.idusuariohabilitado = cuh.id
            inner join contract_area ca on ca.id = caj.idarea
            order by idusuariohabilitado asc";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function insertPermisoAdicional($idUsuario,$permission_crear,$permission_aprobar,$permission_reportes,$permission_responsablearea,$idArea){

    $sql = "INSERT INTO contract_areajefaturas
                       (idusuariohabilitado
                       ,idarea
                       ,permission_crear
                       ,permission_aprobar
                       ,permission_reportes
                       ,permission_responsablearea)
                 VALUES
                       ($idUsuario
                       ,$idArea
                       ,$permission_crear
                       ,$permission_aprobar
                       ,$permission_reportes
                       ,$permission_responsablearea)";

    $link = conectarBD();
    $res = queryBD($sql,$link);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function getArePermisAdicional($id, &$link){

    $sql = "select caj.idarea
            from contract_areajefaturas caj
            where caj.id = $id";

    $data = queryBD($sql,$link);

    return $data[0]['idarea'];
}

function deletePermisoAdicional($id, $userName){

    $link = conectarBD();

    $permisoMain = getPermisosMain($userName,$link);
    $areaPermisoAdicional = getArePermisAdicional($id, $link);

    if( $areaPermisoAdicional == $permisoMain['idarea'])
        return false;

    $sql = "DELETE FROM contract_areajefaturas WHERE id = $id";

    $res = queryBD($sql,$link);
    $link = null;

    if($res === false)
        return true;
    else
        return true;
}

function getPermisosAdicionalesByUser($userName){

    $pos = strpos($userName, '@');
    if ($pos === false) {
        return array();
    }else{
        $userName = str_replace(substr($userName, $pos), "", $userName);
    }

    $sql = "select caj.* , cuh.usuario, ca.descripcion as area
            from contract_areajefaturas caj
            inner join contract_usuarioshabilitados cuh on caj.idusuariohabilitado = cuh.id
            inner join contract_area ca on ca.id = caj.idarea
            where cuh.usuario = '$userName'";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function getPermisosAdicionalesByArea($idArea, &$link, $onlyResponsableArea){

    $sql_responsable_area = "";
    if($onlyResponsableArea)
        $sql_responsable_area = " and caj.permission_responsablearea = 1";

    $sql = "select cuh.id, cuh.usuario, cuh.correo
            from contract_areajefaturas caj
            inner join contract_usuarioshabilitados cuh on caj.idusuariohabilitado = cuh.id
            where caj.idarea = $idArea".$sql_responsable_area;

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function isPermisoPrincipalDuplicado($userName, &$link){

    $sql = "select caj.idarea as areaSecond, cuh.idarea as areMain
            from contract_usuarioshabilitados cuh
            inner join contract_areajefaturas caj on caj.idusuariohabilitado = cuh.id
            where cuh.usuario = '$userName'";

    $data = queryBD($sql,$link);

    if($data === false)
        return false;

    $isDuplicated = false;
    foreach ($data as $d){
        if( $d['areaSecond'] == $d['areMain'] ){
            $isDuplicated = true;
        }
    }

    return $isDuplicated;

}

function getPermisosMain($userName, &$link){

    $sql = "select cuh.id, cuh.idarea, 
                   cuh.permission_crear, cuh.permission_aprobar, 
                   cuh.permission_reportes, cuh.permission_responsablearea
            from contract_usuarioshabilitados cuh
            where cuh.usuario = '$userName'";

    $data = queryBD($sql,$link);

    return $data[0];
}

function duplicarPermisoPrincipal($userName, &$link){

    $permisosMain = getPermisosMain($userName, $link);

    if(empty($permisosMain)){
        return false;
    }

    $idUsuario                  = $permisosMain['id'];
    $permission_crear           = $permisosMain['permission_crear'];
    $permission_aprobar         = $permisosMain['permission_aprobar'];
    $permission_reportes        = $permisosMain['permission_reportes'];
    $permission_responsablearea = $permisosMain['permission_responsablearea'];
    $idArea                     = $permisosMain['idarea'];

    return insertPermisoAdicional($idUsuario,$permission_crear,$permission_aprobar,$permission_reportes,$permission_responsablearea,$idArea);

}

function getPermisoAdicional($userName, $idarea, &$link){

    $sql = "select caj.idarea,
                   caj.permission_crear, caj.permission_aprobar,
                   caj.permission_reportes, caj.permission_responsablearea
            from contract_usuarioshabilitados cuh
            inner join contract_areajefaturas caj on caj.idusuariohabilitado = cuh.id
            where cuh.usuario = '$userName' and caj.idarea = $idarea";

    $data = queryBD($sql,$link);

    if(empty($data))
        return false;
    else
        return $data[0];
}

function setPermisoMain($userName, $idarea, $permisoAdicional, &$link){

    $permission_crear           = $permisoAdicional['permission_crear'];
    $permission_aprobar         = $permisoAdicional['permission_aprobar'];
    $permission_reportes        = $permisoAdicional['permission_reportes'];
    $permission_responsablearea = $permisoAdicional['permission_responsablearea'];

    $sql = "update contract_usuarioshabilitados
            set idarea = $idarea,
                permission_crear = $permission_crear,
                permission_aprobar = $permission_aprobar,
                permission_reportes = $permission_reportes,
                permission_responsablearea = $permission_responsablearea
            where usuario = '$userName'";

    $res = queryBD($sql,$link);

    if($res === false)
        return false;
    else
        return true;
}

function setAreaComoPrincipal($userName, $idarea, &$link){

    $permisoAdicional = getPermisoAdicional($userName, $idarea, $link);

    if($permisoAdicional === false)
        return false;

    return setPermisoMain($userName, $idarea, $permisoAdicional, $link);
}

function setMainArea($userName, $idarea){
    //1° Checar si area en usuarioshabiltiados existe em areasjefaturas
    //Si no existe crearla con los mismos permisos
    //2° Setear area seleccionada con los permisos usuarioshabiltiados

    $pos = strpos($userName, '@');
    if ($pos === false) {
        return array();
    }else{
        $userName = str_replace(substr($userName, $pos), "", $userName);
    }

    $link = conectarBD();

    $isPermisoPrincipalDuplicado = isPermisoPrincipalDuplicado($userName, $link);

    $duplicado = true;
    if(!$isPermisoPrincipalDuplicado){
        $duplicado = duplicarPermisoPrincipal($userName, $link);
    }

    if($duplicado){
        return setAreaComoPrincipal($userName, $idarea, $link);
    }else{
        return false;
    }


}