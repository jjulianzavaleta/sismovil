<?php

function getPermissionsUsuarioVales($usuario){

    $pos = strpos($usuario, '@');
    if ($pos === false) {
        return array();
    }else{
        $usuario = str_replace(substr($usuario, $pos), "", $usuario);
    }

    $sql = "select * from vales_usuarioshabilitados where usuario = '$usuario' and activo = 1";
    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;
}

function getAllValesUsuarios(){

    $sql = "select * from vales_usuarioshabilitados";

    $link = conectarBD();
    $data = queryBD($sql,$link);
    $link = null;

    return $data;

}

function registrarValesUsuario( $usuario,$permission_planner,$permission_driver,$permission_reportes,$correo,$permission_admin){

    $usuario = mb_strtolower($usuario, 'UTF-8');

    $sql = "insert into vales_usuarioshabilitados(usuario,permission_planner,permission_driver,permission_reportes,activo,correo,permission_admin) values ('$usuario',$permission_planner,$permission_driver,$permission_reportes,1,'$correo',$permission_admin)";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}

function eliminarValesUsuario($id){

    $sql = "delete from vales_usuarioshabilitados where id = $id";

    $link = conectarBD();
    $res  = queryBD($sql,$link,true);
    $link = null;

    if($res === false){
        return false;
    }else{
        return true;
    }
}

function updateValesUsuario($id, $permission_planner,$permission_driver,$permission_reportes,$activo,$correo,$permission_admin){

    $sql = "update vales_usuarioshabilitados set permission_planner = $permission_planner,permission_driver = $permission_driver,permission_reportes =$permission_reportes,correo = '$correo',activo = $activo, permission_admin=$permission_admin where id=$id";

    $link = conectarBD();
    $res = queryBD($sql,$link,true);
    $link = null;

    if($res === false)
        return false;
    else
        return true;
}